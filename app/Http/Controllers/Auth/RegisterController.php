<?php

namespace App\Http\Controllers\Auth;
use Illuminate\Http\Request;
use App\User;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

      public function register(Request $request)
        {

        //custom error messages 
        $messages=[
            "name.regex"=>"Name must contain Minimum five characters, at least one letter and one number",
            "password.regex"=>"Password must contain Minimum eight characters, at least one uppercase letter, one lowercase letter, one number and one special character"
        ];
        // validate input
        $validator= Validator::make($request->all(),[
            'name'=>"required|string|unique:users|regex:/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,15}$/",
            'email'=>"required|email",
            'password'=>"required|string|min:6|confirmed|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$/",
        ], $messages);

        // if validation fails
        if($validator->fails())
        {
            return response()->json($validator->errors(),400);
        }
        // check if email is taken
        $user = User::where('email', $request->get('email'))->first();
        if($user)
        {
            return response()->json(["message" => "The email has already been taken"],400);
        }
        // create user
        $newUser=User::create([
            'name'=>$request->get('name'),
            'email'=>$request->get('email'),
            'password'=>Hash::make($request->get('password')),

        ]);

        $token= JWTAuth::fromUser($newUser);

        return response()->json(compact('newUser','token'),201);
    }
 
}
