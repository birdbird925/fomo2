<header>
    <div class="logo menu-tab"></div>
    <div class="pull-right nav-right">
        <ul>
            <li>
                <a href="/cart" class="cart">
                    <span class="item-count">
                        {{Session::has('cart.item') ? sizeof(Session::get('cart.item')) : 0}}
                    </span>
                </a>
            </li>
        </ul>
    </div>
</header>


<a href="/">
    <div id="logo" class="@yield('logo-class')">
        <svg id="Layer_1" data-name="Layer 1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 350 100">
            <path d="M183.29,23.58h9.54l16.52,33.64,16.52-33.64h9.54V75.51h-7.77V40.37L212.09,69.86h-5.48L191.06,40.37V75.51h-7.78Z"/>
            <path d="M116.35,23.56A26.44,26.44,0,1,0,142.78,50,26.44,26.44,0,0,0,116.35,23.56Zm0,44.62A18.18,18.18,0,1,1,134.52,50,18.18,18.18,0,0,1,116.35,68.18Z"/>
            <path d="M298.56,23.56A26.44,26.44,0,1,0,325,50,26.44,26.44,0,0,0,298.56,23.56Zm0,44.62A18.18,18.18,0,1,1,316.74,50,18.18,18.18,0,0,1,298.56,68.18Z"/>
            <polygon points="60.96 31 60.96 23.58 25 23.58 25 76.44 32.77 76.44 32.77 54.34 55.78 54.34 55.78 46.92 32.77 46.92 32.77 31 60.96 31"/>
        </svg>
    </div>
</a>
