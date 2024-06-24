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
    public function index(Request $request){
        $validator = Validator::make($request->all(), [
            'level' => 'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'state' => 'fail',
                'message' => 'Invalid Input. Try Again',
                'error'=>$validator->errors()
            ], 422);
        }else{
            $stories = Stories::where('level', $request->level)->pluck('title'); // Filter by language
            if ($stories) {
                return response()->json([
                    'data' => $stories,
                ],200);
            } else {
                // No content found
                return response()->json([
                    'state' => 'error',
                    'message' => 'No story found.',
                ], 404);
            }
        }
    }
    public function show(Request $request){
        
        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'level' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'state' => 'fail',
                'message' => 'Invalid Input. Try Again',
                'error'=>$validator->errors()
            ], 422);
        }
        $stories = Stories::where('language', $request->language) // Filter by language
        ->where('level', $request->level) // Filter by level
        ->inRandomOrder()->first();

        if ($stories) {
            return response()->json([
                'state' => 'success',
                'message'=>'Story fetched successfully',
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
    public function levelLanguage(Request $request){
        $user_id = JWTAuth::setToken($request->bearerToken())->authenticate()->id;

        $levels = Stories::groupBy('level')->pluck('level');
        $languages = Stories::groupBy('language')->pluck('language');
        if ($levels&&$languages) {
            return response()->json([
                'data' => [
                    'levels'=>$levels,
                    'language'=>$languages
                ],
            ]);
        } else {
            // No content found
            return response()->json([
                'state' => 'error',
                'message' => 'No data found.',
            ], 404);
        }
    }
}
