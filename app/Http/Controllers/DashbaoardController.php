<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashbaoardController extends Controller
{
    public function index(Request $request){
        if ($request->user()->hasRole('superadministrator')) {
            return view('dashboard');
        }
        if ($request->user()->hasRole('administrator')){
            return view('dashboard');
        }
        if ($request->user()->hasRole('user')){
        return redirect('/');
        }
    }
}
