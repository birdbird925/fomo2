@extends('layouts.app')

@section('logo-class')
    fixed
@endsection

@section('footer-class')
    {{sizeof(Session::get('cart.item')) > 0 ? 'hide' : ''}}
    mobile-hide
@endsection

@section('content')
    <div class="cart-wrapper">
        <div class="cart-body">
            <div class="title page-title">Your Cart</div>

            @if(sizeof(Session::get('cart.item')) == 0)
                <div class="empty-msg">
                    <p>Oops! Empty cart is not cool.</p>
                    <a href="/customize/">Built your first watch</a>
                </div>
            @else
                <table class="product-table table">
                    @foreach(Session::get('cart.item') as $index=>$item)
                        <tr>
                            <td class="image-col" align="center">
                                <div id="{{$item['code']}}" class="konvas-thumb" data-thumb="{{$item['thumb']}}"></div>
                            </td>
                            <td class="description-col col-md-4">
                                <div class="name">{{$item['name']}}</div>
                                <div class="description">{{$item['description']}}</div>
                            </td>
                            <td class="quantity-col">
                                1 piece
                            </td>
                            <td class="price-col">
                                $ {{$item['price']}}
                            </td>
                            <td class="control-col">
                                <a href="#" style="color:#eaebf0;">.</a>
                                {{-- <a href="/customize/{{$item['code']}}" class="edit">Edit</a> --}}
                                <form action="/cart/{{$index}}/remove" method="post">
                                    {{ csrf_field() }}
                                    <button type="submit" class="remove"></button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tabel>

                <table class="shipping-table">
                    <tr>
                        <td class="shipping-label">Shipping</td>
                        <td class="coutry">
                            <select name="shipping-country">
                                <option value="" {{Session::get('cart.shipping.location') == "" ? 'selected' : ''}}>SELECT</option>
                                <option data-price="20" {{Session::get('cart.shipping.location') == "UK/US" ? 'selected' : ''}}>UK/US</option>
                                <option data-price="10" {{Session::get('cart.shipping.location') == "ASIAN" ? 'selected' : ''}}>ASIAN</option>
                                <option data-price="0" {{Session::get('cart.shipping.location') == "EURO" ? 'selected' : ''}}>EURO</option>
                            </select>
                        </td>
                        <td class="price">
                            $ {{Session::get('cart.shipping.cost') > 0 ? Session::get('cart.shipping.cost') : 0}}
                        </td>
                        <td class="control">
                            <a id="shipping-trigger">Edit</a>
                        </td>
                    </tr>
                </table>
            @endif
        </div>
        @if(sizeof(Session::get('cart.item')) != 0)
        <div class="cart-footer">
            <div class="caption">TOTAL</div>
            <div class="total">
                $ <span>{{Session::get('cart.total')}}</span>
            </div>
            <form id="checkout-form" action="/checkout" method="post">
                {{ csrf_field() }}
                <a id="checkout-button" class="checkout">Proceed to checkout</a>
            </form>
            <form name="frmPayment" method="post" action="https://test2pay.ghl.com/IPGSG/Payment.aspx">
              <input type="hidden" name="TransactionType" value="SALE">
              <input type="hidden" name="PymtMethod" value="ANY">
              <input type="hidden" name="ServiceID" value="sit">
              <input type="hidden" name="PaymentID" value="100001"> <input type="hidden" name="OrderNumber" value="100001">
              <input type="hidden" name="PaymentDesc" value="Booking No: IJKLMN, Sector: KUL-BKI, First Flight Date: 26 Sep 2012">
              <input type="hidden" name="MerchantName" value="FOMO">
              <input type="hidden" name="MerchantReturnURL" value="http://rijiaconstruction.com">
              <input type="hidden" name="MerchantCallbackURL" value="http://rijiaconstruction.com">
              <input type="hidden" name="Amount" value="228">
              {{-- <input type="hidden" name="TaxAmt" value="1"> --}}
              <input type="hidden" name="CurrencyCode" value="MYR">
              <input type="hidden" name="CustIP" value="118.100.6.23">
              <input type="hidden" name="CustName" value="Looi">
              <input type="hidden" name="CustEmail" value="looi@gmail.com">
              <input type="hidden" name="CustPhone" value="60121235678">
              <input type="hidden" name="HashValue" value="fcd8b6b2ffa8a26d386213b8f23c74e4931d3fe425d804372957a99fc9c1e5f1">
              <input type="hidden" name="MerchantTermsURL" value="http://merchA.merchdomain.com/terms.html">
              <input type="hidden" name="LanguageCode" value="en">
              <input type="hidden" name="PageTimeout" value="780">
              <input type="submit" value="checkout">
            </form>
        </div>
        @endif
    </div>

@endsection
