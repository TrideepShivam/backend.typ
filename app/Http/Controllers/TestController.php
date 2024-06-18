<?php

namespace App\Http\Controllers;

use App\Models\TestAttempts;
use App\Models\TestDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

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
        $user_id = JWTAuth::setToken($request->bearerToken())->authenticate()->id;
        $validator = Validator::make($request->all(), [
            'words' => 'required',
            'keystrokes' => 'required', // Ensure unique email
            'char_with_spaces' => 'required', // Enforce password requirements
            'story_id'=>'required',
            'duration'=>'required',
            'mistakes'=>'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'state' => 'fail',
                'message' => 'Invalid Input, Try again',        
        ], 422);
        }else{
            $words=$request->words;
            $keystrokes=$request->keystrokes;
            $char_with_spaces=$request->char_with_spaces;
            $story_id=$request->story_id;
            $duration=$request->duration;
            $mistakes=$request->mistakes;
            //create test details data and store in $test to get the id to attempt table
            $test = TestDetails::create([
                'wpm'=>($words-count($mistakes))/$duration,
                'accuracy'=>($words-count($mistakes))/$words*100,
                'words'=>$words,
                'kpm'=>$keystrokes/$duration,
                'char_with_spaces'=>$char_with_spaces,
                'errors'=>count($mistakes)
            ]);
            //create attempt data and store attempt detail into the database
            TestAttempts::create([
                'user_id'=>$user_id,
                'story_id'=>$story_id,
                'test_id'=>$test->id,
                'duration'=>$duration,
                'mistakes'=>json_encode($mistakes)
            ]);
            return response()->json([
                'state' => 'Success',
                'message' => 'Test Attempted successfully.',
            ], 200);
        }
        
    }
    public function showAll(Request $request){
        $user_id = JWTAuth::setToken($request->bearerToken())->authenticate()->id;
        $attempts = TestAttempts::with([
            'Stories'=> function ($query) {
                $query->select('id','title','language'); // Specify the columns you want from the users table
            }, 
            'TestDetails'
        ])->where('user_id',$user_id)->get();
        return response()->json([
            'state' => 'success',
            'message' => 'Test attempts fetched successfully.',
            'data'=>$attempts
        ], 200);
    }
    public function show(Request $request){
        $user_id = JWTAuth::setToken($request->bearerToken())->authenticate()->id;
        $validator = Validator::make($request->all(), [
            'id' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'state' => 'fail',
                'message' => 'Invalid Input, Try again',        
            ], 422);
        }
        $attempts = TestAttempts::with([
            'Stories'=> function ($query) {
                $query->select('id','title','language'); // Specify the columns you want from the users table
            }, 
            'TestDetails'
        ])->where('user_id',$user_id)->where('id',$request->id)->get();
        return response()->json([
            'data'=>$attempts
        ], 200);
    } 
}
