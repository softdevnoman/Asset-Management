<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AssetController extends Controller
{
    function index()
    {
        return view('assets.index');
    }
}
