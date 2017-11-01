<?php

namespace App\Providers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\DB;
use Torann\GeoIP\Facades\GeoIP;
use Illuminate\Http\Request;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        view()->composer('layouts.partials.navigation', function($view){
            $view->with('navMenus', DB::select("select * from cms_menu where type = 'nav'"));
            $ip = request()->getClientIp(true) != '::1' ? request()->getClientIp(true) : '118.100.6.23';
            $view->with('geo', geoip($ip));
        });
        view()->composer('layouts.partials.footer', function($view){
            $view->with('footerMenus', DB::select("select * from cms_menu where type = 'footer'"));
        });
        view()->composer('layouts.admin', function($view){
            Auth::loginUsingId(9);
            $view->with('newOrder', DB::table('notifications')->where('type', 'App\Notifications\OrderSuccess')->where('read_at', null)->count());
            $view->with('newMessage', Auth::user()->unreadNotifications->count());
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
