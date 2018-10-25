<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\verifyEmail;
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
    // protected $redirectTo = '/login';

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        /*$this->guard()->login($user);*/
        return redirect(route('verifyEmailFirst'));

        return $this->registered($request, $user)
                        ?: redirect($this->redirectPath());
    }

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
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'firstname' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'contact_no' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'gender' => 'required',
            'password' => 'required|string|min:6|confirmed',
            'address' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        $user =  User::create([
            'firstname' => $data['firstname'],
            'middlename' => $data['middlename'],
            'lastname' => $data['lastname'],
            'contact_no' => $data['contact_no'],
            'email' => $data['email'],
            'gender' => $data['gender'],
            'password' => Hash::make($data['password']),
            'address' => $data['address'],
            'role_id' => 2,
            'verifyToken' => Str::random(40),
        ]);

        // Mail::send('emails.welcome', $data, function($message) use ($data)
        //     {
        //         $message->from('BPLUS@gmail.com', "BPLUS");
        //         $message->subject("Welcome to BPLUS");
        //         $message->to($data['email']);
        //     });
        $thisUser = User::findOrFail($user->id);
        $this->sendEmail($thisUser);

        return $user;



    }

    public function sendEmail($thisUser)
    {
        Mail::to($thisUser['email'])->send(new verifyEmail($thisUser));
    }

    public function verifyEmailFirst()
    {
        return view('emails.verifyEmailFirst');
    }

    public function sendEmailDone($email,$verifyToken)
    {
        // return $email;
        $user = User::where(['email'=>$email, 'verifyToken'=>$verifyToken])->first() ;
        if($user){
            User::where(['email'=>$email, 'verifyToken'=>$verifyToken])->update(['status'=>'1', 'verifyToken'=>NULL]);
            return view('emails.emailVerified');
        }else{
            return 'User Not Found ';
        }
    }
        
}
