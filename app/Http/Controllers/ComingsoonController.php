<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\DB;


class ComingsoonController extends Controller
{
    public function index()
    {
      return view('comingsoon');
    }

    public function register()
    {
      if(request()->email == null) {
         session()->flash('popup', [
           'title' => 'Ermmm',
           'caption' => 'Not fill in anything is not cool.'
         ]);
      }
      else {
         $validator = Validator::make(request()->all(), [
           'email' => 'required|email|max:255|unique:early_excess_invite_list',
         ]);

        if($validator->fails()){
           session()->flash('popup', [
              'title' => 'Ermmm',
              'caption' => 'You had already signed up for early access invite!'
           ]);
        }
        else {
           session()->flash('popup', [
              'title' => 'Hooray!',
              'caption' => 'Early access is yours! We’ll be in touch.'
           ]);
           DB::table('early_excess_invite_list')->insert(
             ['email' => request()->email]
           );
         }
      }

      return redirect('/coming-soon');
    }
}
