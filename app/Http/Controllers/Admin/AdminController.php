<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    //
    public function redirect(){
        if(Auth::user()->is_admin=="1"){
            return view('admin.index');
        }else{
            return view('welcome');
        }
       
    }
}
