<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    public function index(){
    	$title = "Welcome to mobile legends!";
    	//return view('pages.index', compact('title'));
    	return view('pages.index')->with('title',$title);
    }
    public function about(){
    	$title = "About hehe";
    	return view('pages.about', compact('title'));
    }
    public function services(){
    	$data = array(
    		'title' => 'Services',
    		'services' => ['Web DEsign','Programming','SEQ']
    	);
    	return view('pages.services')->with($data);

    }
}
