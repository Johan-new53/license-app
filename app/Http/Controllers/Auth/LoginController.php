<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Laravel\Socialite\Facades\Socialite;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function name()
    {
        return 'name';
    }

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }

    public function redirectToMicrosoft()
    {
        return Socialite::driver('microsoft')->redirect();
    }

    // Callback dari Microsoft
    public function handleMicrosoftCallback(Request $request)
    {
        $microsoftUser = Socialite::driver('microsoft')->stateless()->user();
        $email = $microsoftUser->getEmail();
        $name  = $microsoftUser->getName();
        $officelocation = $microsoftUser->user['officeLocation'] ?? null;

        // ✅ ambil IP client
        $ip = $request->ip();

        // ✅ resolve ke hostname
        $hostname = @gethostbyaddr($ip);

        // Cek user di DB
        $user = \App\Models\User::where('email', $email)->first();



        if (!$user) {
            // Buat user baru
            $user = \App\Models\User::create([
               
                'name'  => $name,
                'email'     => $email,
                'password'  => bcrypt(bin2hex(random_bytes(8))), // password dummy
                
                'created_at'=> now(),
                'updated_at'=> now(),
            ]);
        }

        // Login user
        Auth::login($user);

        return redirect()->route('home');
    }
    
   


}
