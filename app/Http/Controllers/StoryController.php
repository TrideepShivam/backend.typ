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
            'language' => 'required',
            'level' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'state' => 'Error',
                'message' => 'Invalid Input. Try Again',
                'error'=>$validator->errors()
            ], 422);
        }
        $stories = Stories::where('language', $request->language) // Filter by language
        ->where('level', $request->level)->pluck('title');

        if ($stories) {
            return response()->json($stories);
        } else {
            // No content found
            return response()->json([
                'state' => 'Error',
                'message' => 'No content found.',
            ], 404);
        }
    }
    public function show(Request $request){
        
        $validator = Validator::make($request->all(), [
            'language' => 'required',
            'level' => 'required',
            'story'=>'required'
        ]);
        if ($validator->fails()) {
            return response()->json([
                'state' => 'Error',
                'message' => 'Invalid Input. Try Again',
                'error'=>$validator->errors()
            ], 422);
        }
        $stories = Stories::where('language', $request->language) // Filter by language
        ->where('level', $request->level) // Filter by level
        ->where('title',$request->story)->first();

        if ($stories) {
            return response()->json($stories);
        } else {
            // No content found
            return response()->json([
                'state' => 'Error',
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
                'levels'=>$levels,
                'languages'=>$languages
            ]);
        } else {
            // No content found
            return response()->json([
                'state' => 'Error',
                'message' => 'No data found.',
            ], 404);
        }
    }
}
