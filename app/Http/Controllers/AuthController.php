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
                'password' => 'required|string|min:8', // Enforce password requirements
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 422);
            }

            $user = User::create([ // Create a new user model instance
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password), // Hash the password securely
            ]);

            // Optionally generate a token upon registration (less secure):
            // $token = auth()->login($user); // Attempt login to generate token

            return response()->json([
                'status' => 'success',
                'message' => 'User created successfully',
                // 'token' => $token, // Include token if using optional generation
            ]);
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
                return response()->json(['error' => 'Unauthorized'], 401);
            }
    
            return $this->respondWithToken($token);
        }
        
        public function logout(){
            auth()->logout();

            return response()->json(['message' => 'Successfully logged out']);
        }
        /**
         * Get the token array structure.
         *
         * @param  string $token
         *
         * @return \Illuminate\Http\JsonResponse
         */
        protected function respondWithToken($token)
        {
            return response()->json([
                'access_token' => $token,
                'token_type' => 'bearer',
            ]);
        }
    }
?>