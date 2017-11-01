<?php

namespace App\Http\Controllers;

use App\Repositories\Image\ImageRepository;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use App\CustomizeType;
use App\CustomizeStep;
use App\CustomizeComponent;
use App\CustomizeComponentOption;
use App\Image;

class CartController extends Controller
{
    public function index()
    {
        return view('cart');
    }

    public function removeItem($id)
    {
        session()->forget("cart.item.$id");
        $price = session('cart.shipping.cost') > 0 ? session('cart.shipping.cost') : 0;
        foreach(session('cart.item') as $item)
            $price += $item['price'];
        session(['cart.total'=>$price]);
        return redirect('/cart');
    }

    public function updateShipping()
    {
        $price = request('cost');
        foreach(session('cart.item') as $item)
            $price += $item['price'];
        session([
            'cart.shipping.location'=>request('location'),
            'cart.shipping.cost'=>request('cost'),
            'cart.total'=>$price
        ]);

        return session('cart.total');
    }
}
