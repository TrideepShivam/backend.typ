<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use App\Models\Stories;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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
                'state' => 'success',
                'message'=>'Data fetched successfully',
                'data' => $stories,
            ]);
        } else {
            // No content found
            return response()->json([
                'state' => 'error',
                'message' => 'No matching content found.',
            ], 404);
        }
    }
    public function levels(Request $request){
        $user_id = JWTAuth::setToken($request->bearerToken())->authenticate()->id;

        $levels = Stories::groupBy('level')->pluck('level');

        if ($levels) {
            return response()->json([
                'data' => $levels,
            ]);
        } else {
            // No content found
            return response()->json([
                'state' => 'error',
                'message' => 'No level found.',
            ], 404);
        }
    }
}
