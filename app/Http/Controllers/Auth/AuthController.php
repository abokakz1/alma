<?php

namespace App\Http\Controllers\Auth;

use App\Models\Users;
use App\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $redirectToAdmin = '/admin';
    protected $redirectToBlog = '/blogs';

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */

    public function redirectToProvider($provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    public function handleProviderCallback($provider)
    {
        $user = Socialite::driver($provider)->user();

        $authUser = $this->findOrCreateUser($user, $provider);
        //dd($authUser);
        Auth::login($authUser, true);
        
        // return redirect($this->redirectToBlog);
        return back();
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    public function findOrCreateUser($user, $provider)
    {
        $authUser = Users::where("{$provider}_id", $user->id)->first();
        if ($authUser) {
            return $authUser;
        }
        elseif ( !empty($user->email) && Users::where('email', $user->email)->exists()){
            Users::where('email', $user->email)->update(["{$provider}_id" => $user->id]);
            
            return Users::where('email', $user->email)->first();
        }
        else {
            $username = $user->email;
            if(empty($user->email)){
                $username = explode(" ", $user->name)[0] . '_' . rand(1, 999);
            }
            $local = new Users([
                "fio" => $user->name,
                "email" => $user->email,
                "username" => $username,
                "{$provider}_id" => $user->id,
                "is_blocked" => 0
            ]);
            if($local->save()){
                return $local;
            }
        }
    }
}
