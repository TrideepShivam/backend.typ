<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Stories;

class StoryController extends Controller
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
    public function index(){
        return response()->json([
            'state' => 'fail',
            'message' => 'API not in use',
        ], 422);
    }
    public function show(Request $request){
        $token = !$request->header['Authorization'];
        if($token){
            return response()->json([
                'state' => 'fail',
                'message' => 'Not Authorized. Try Again',
            ], 401);
        }
        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'level' => 'required', 
            'capitalized' => 'required', 
            'numeric' => 'required', 
        ]);
        if ($validator->fails()) {
            return response()->json([
                'state' => 'fail',
                'message' => 'Invalid Input. Try Again',
                'error'=>$validator->errors()
            ], 422);
        }
        $stories = Stories::where('language', $request->language) // Filter by language
        ->where('capitalized', $request->capitalized) // Filter by capitalized
        ->where('numeric', $request->numeric) // Filter by numeric
        ->where('level', $request->level) // Filter by level
        ->inRandomOrder()->first();

        if ($stories) {
            return response()->json([
                'status' => 'success',
                'data' => $stories,
            ]);
        } else {
            // No content found
            return response()->json([
                'status' => 'error',
                'message' => 'No matching content found.',
            ], 404);
        }
    }
}
