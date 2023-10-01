<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;


class HomeController extends Controller
{
    public function index(){
        $user = Auth::user();
        return view('dashboard.index')->with(compact('user'));
    }

    public function components(){
        $user = Auth::user();
        return view('dashboard.components')->with(compact('user'));;
    }

}
