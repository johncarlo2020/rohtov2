<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class IpadController extends Controller
{
    public function index()
    {
        return view('ipad');
    }

    public function index2()
    {
        return view('ipad.ipad2');
    }

}
