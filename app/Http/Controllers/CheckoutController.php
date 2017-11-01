<?php

namespace App\Http\Controllers;

use App\Repositories\Image\ImageRepository;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Notifications\OrderSuccess;
use Carbon\Carbon;
use App\CustomizeProduct;
use App\Order;
use App\OrderItem;
use Paypal;
use Redirect;

class CheckoutController extends Controller
{
    private $_apiContext;

    public function __construct()
    {
        $this->_apiContext = PayPal::ApiContext(
            config('services.paypal.client_id'),
            config('services.paypal.secret'));

		$this->_apiContext->setConfig(array(
			'mode' => 'sandbox',
			'service.EndPoint' => 'https://api.sandbox.paypal.com',
			'http.ConnectionTimeOut' => 30,
			'log.LogEnabled' => true,
			'log.FileName' => storage_path('logs/paypal.log'),
			'log.LogLevel' => 'FINE'
		));

    }

    public function validation()
    {
        if(sizeof(session('cart.item')) == 0)
            return 'empty';
        if(session('cart.shipping.location') == '')
            return 'shipping';
        // validation pass, add product image to session
        $image = json_decode(request('image'));
        $count = 0;
        foreach(session('cart.item') as $index=>$item) {
            session(["cart.item.$index.image" => $image[$count]]);
            $count++;
        }
        return 'true';
    }

    public function checkout()
    {
    	$payer = PayPal::Payer();
    	$payer->setPaymentMethod('paypal');

        $detail = PayPal::Details();
        $detail->setSubtotal(session('cart.total') - session('cart.shipping.cost'));
        $detail->setShipping(session('cart.shipping.cost'));

    	$amount = PayPal::Amount();
    	$amount->setCurrency('USD');
    	$amount->setTotal(session('cart.total'));
        $amount->setDetails($detail);

        $items = PayPal::ItemList();
        foreach(session('cart.item') as $cartItem) {
            $item = PayPal::Item();
            $item->setSku($cartItem['code']);
            $item->setName($cartItem['name']);
            $item->setDescription($cartItem['description']);
            $item->setQuantity(1);
            $item->setCurrency('USD');
            $item->setPrice($cartItem['price']);
            $items->addItem($item);
        }

        $transaction = PayPal::Transaction();
    	$transaction->setAmount($amount);
        $transaction->setDescription('Creating a payment');
        $transaction->setItemList($items);

    	$redirectUrls = PayPal:: RedirectUrls();
    	$redirectUrls->setReturnUrl(action('CheckoutController@getDone'));
    	$redirectUrls->setCancelUrl(action('CartController@index'));

    	$payment = PayPal::Payment();
    	$payment->setIntent('sale');
    	$payment->setPayer($payer);
    	$payment->setRedirectUrls($redirectUrls);
    	$payment->setTransactions(array($transaction));
        $payment->setExperienceProfileId($this->createWebProfile());

    	$response = $payment->create($this->_apiContext);
    	$redirectUrl = $response->links[1]->href;

    	return Redirect::to($redirectUrl);
    }

    public function getDone(Request $request)
    {
    	$id = $request->get('paymentId');
    	$token = $request->get('token');
    	$payer_id = $request->get('PayerID');

    	$payment = PayPal::getById($id, $this->_apiContext);

        // check shipping country here
        $payerInfo = $payment->getPayer()->getPayerInfo();
        $transaction = $payment->getTransactions()[0];
        $amount = $transaction->getAmount();
        $items = $transaction->getItemList()->getItems();
        $address = $transaction->getItemList()->getShippingAddress();

    	$paymentExecution = PayPal::PaymentExecution();
    	$paymentExecution->setPayerId($payer_id);
    	$executePayment = $payment->execute($paymentExecution, $this->_apiContext);

        $transactionID = $executePayment->getTransactions()[0]->getRelatedResources()[0]->getSale()->getId();
        if($executePayment->getState() == 'approved') {
            $order = Order::create([
                'user_id' => Auth::check() ? Auth::user()->id : null,
                'name' => $address->getRecipientName(),
                'email' => $payerInfo->getEmail(),
                'phone' => $address->getPhone() ? $address()->getPhone() : $payerInfo->getPhone(),
                'address_line_1' => $address->getLine1(),
                'address_line_2' => $address->getLine2(),
                'city' => $address->getCity(),
                'postcode' => $address->getPostalCode(),
                'state' => $address->getState(),
                'country' => $address->getCountryCode(),
                'shipping_cost' => $amount->getDetails()->getShipping(),
                // 'paypal_id' => $payment->getId(),
                'paypal_id' => $transactionID,
                'payment_status' => 1
            ]);

            $cartItemCode = [];
            foreach($items as $item)
                $cartItemCode[] = $item->sku;

            foreach(session('cart.item') as $cartItem) {
                if(in_array($cartItem['code'], $cartItemCode)) {
                    $component = json_decode($cartItem['product']);
                    $type_id = $component->customize_type->value;
                    $product = CustomizeProduct::create([
                        'name' => $cartItem['name'],
                        'components' => $cartItem['product'],
                        'image' => $cartItem['image'],
                        'images' =>$cartItem['images'],
                        'thumb' => $cartItem['thumb'],
                        'back' => $cartItem['back'],
                        'type_id' => $type_id,
                        'description' => $cartItem['description'],
                        'price' => $cartItem['price'],
                        'created_by' => Auth::check() ? Auth::user()->id : null,
                    ]);

                    $key = array_search($cartItem['code'],$cartItemCode);
                    $orderItem = $order->items()->create([
                        'product_id' => $product->id,
                        'price' => $items[$key]->price,
                        'quantity' => $items[$key]->quantity
                    ]);
                    $order->save();
                }

            }

            // remove session cart
            session()->forget("cart");
            // send mail
            $order->notify(new OrderSuccess($order));

            session()->flash('popup', [
                'title' => 'Hooray!',
                'caption' => 'You order is successfully placed.'
            ]);

            if(Auth::check())
                return redirect('/account');
            else
                return redirect('/cart');
        }
        else {
            return redirect('/cart');
        }
    }

    public function createWebProfile(){
    	$flowConfig = PayPal::FlowConfig();
    	$presentation = PayPal::Presentation();
    	$inputFields = PayPal::InputFields();
    	$webProfile = PayPal::WebProfile();
    	$flowConfig->setLandingPageType("Billing");
        // user_action=commit
    	$presentation->setLogoImage("http://localhost:8000/images/demo/paypal-logo.svg")->setBrandName("FOMO"); //NB: Paypal recommended to use https for the logo's address and the size set to 190x60.

    	$webProfile->setName("FOMO".uniqid())
    		->setFlowConfig($flowConfig)
    		// Parameters for style and presentation.
    		->setPresentation($presentation)
    		// Parameters for input field customization.
    		->setInputFields($inputFields);

    	$createProfileResponse = $webProfile->create($this->_apiContext);

    	return $createProfileResponse->getId(); //The new webprofile's id
    }
}
