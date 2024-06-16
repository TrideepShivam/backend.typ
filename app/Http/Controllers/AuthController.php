<?php
    namespace App\Http\Controllers;
    use Illuminate\Http\Request;
    use Illuminate\Support\Facades\Auth;
    use Illuminate\Support\Facades\Hash;
    use Illuminate\Support\Facades\Validator;
    use App\Models\User;

    use App\Http\Controllers\Controller;
    
    class AuthController extends Controller
    {
        /**
         * Create a new AuthController instance.
         *
         * @return void
         */
        public function __construct()
        {
            $this->middleware('auth:api', ['except' => ['login','register']]);
        }
        public function register(Request $request)
        {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string',
                'email' => 'required|string', // Ensure unique email
                'password' => 'required|confirmed|string|min:8', // Enforce password requirements
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'state' => 'fail',
                    'message' => 'Invalid Input',
                    'error'=>$validator->errors()
            
            ], 422);
            }
            $existingUser = User::where('email', $request->email)->first();
            if ($existingUser) {
                return response()->json([
                    'state' => 'error',
                    'message' => 'User with this email already exists.',
                ], 409); // 409 Conflict status code
            }else{
                $user = User::create([ // Create a new user model instance
                    'name' => $request->name,
                    'email' => $request->email,
                    'password' => Hash::make($request->password), // Hash the password securely
                ]);
            }
            

            // Optionally generate a token upon registration (less secure):
            $token = auth()->login($user); // Attempt login to generate token

            return response()->json([
                'state' => 'success',
                'message' => 'User created successfully',
                'user'=>auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
            ],200);
        }
        /**
         * Get a JWT via given credentials.
         *
         * @return \Illuminate\Http\JsonResponse
         */
        public function login()
        {
            $credentials = request(['email', 'password']);
    
            if (! $token = auth()->attempt($credentials)) {
                return response()->json([
                    'state' => 'fail',
                    'message'=>'User id or password is invalid',
                ], 401);
            }
    
            return response()->json([
                'state' => 'success',
                'message' => 'Logged in successfully',
                'user'=>auth()->user(),
                'access_token' => $token,
                'token_type' => 'bearer',
            ],200);
        }
        
        public function logout(){
            
            auth()->logout();

            return response()->json([
                'state' => 'success',
                'message' => 'Successfully logged out'
            ],200);
        }
    }
?>