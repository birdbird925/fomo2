<nav class="nav-menu">
    <div class="header-position">
        <div class="menu-tab close-nav">Close</div>

        <ul>
            @if(sizeof($navMenus) != 0)
                @foreach($navMenus as $menu)
                    <li>
                        <a href="{{$menu->link}}">
                            {{$menu->text}}
                        </a>
                    </li>
                @endforeach
            @endif
            @if(Auth::check())
                <li><a href="/account">Account</a></li>
                <li><a href="/logout">logout</a></li>
            @else
                <li><a class="login-tab">Login</a></li>
            @endif
        </ul>

        <div class="ship-to">
            Ship to
            <br>
            <a href="#">{{$geo->country}}</a>
        </div>
    </div>
</nav>
