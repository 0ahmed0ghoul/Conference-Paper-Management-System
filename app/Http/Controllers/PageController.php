<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{
    public function welcome(){return view('welcome');}
    public function contact(){return view('contact');}
    public function speakers(){return view('speakers');}
    public function schedule(){return view('schedule');}
}
