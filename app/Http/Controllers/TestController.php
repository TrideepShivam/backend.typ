<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class TestController extends Controller
{
    /**
    * Create a new AuthController instance.
    *
    * @return void
    */
   public function __construct()
   {
       $this->middleware('auth:api');
   }
    public function store(Request $request){
        echo("called store");
        return response()->json([
            'state' => 'show',
            'message' => 'API not in use',
        ], 200);
    }
    public function show(){
        echo("called attempts");
         
            return response()->json([
                'state' => 'attempts',
                'message' => 'working',
            ], 200);
    }
}
