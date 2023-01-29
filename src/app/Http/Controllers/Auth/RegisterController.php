<?php

namespace App\Http\Controllers\Auth;

use App\Connection;
use App\User;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use App\Jobs\SendVerificationEmail;

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
    protected $redirectTo = '/register/terms';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected function partialValidator(Request $request, $step)
    {
        switch ($step) {
            case 'email':
                $this->validate($request, [
                    'email' => 'required|string|email|max:255|unique:users',
                ]);
                break;
            case 'name':
                $this->validate($request, [
                    'first_name' => 'required|string|max:255',
                    'last_name' => 'required|string|max:255',
                ]);
                break;
            case 'password':
                $this->validate($request, [
                    'password' => 'required|string|min:6',
                ]);
                break;
        }
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
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
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
        $user = User::create([
            'first_name' => $data['first_name'],
            'last_name' => $data['last_name'],
            'location' => $data['location'],
            'gender' => $data['gender'],
            'age' => $data['age'],
            'email' => $data['email'],
            'email_token' => base64_encode($data['email']),
            'password' => bcrypt($data['password']),
            'username' => $this->makeUsername($data),
            'validated' => 0,
            'reported' => 0,
            'active' => 0,
            'type' => config('constance.user_types')['NORMAL']
        ]);

        if (!empty($data['connect_with'])) {
            $friend = User::find($data['connect_with']);
            if ($friend) {
                Connection::addConnectionBetween($user, $friend, ['accepted' => true]);
            }
        }

        return $user;
    }

    private function makeUsername($data)
    {
        $counter = null;
        do {
            $username = $data['first_name'] . $data['last_name'] . $counter;
            $username = str_replace(' ', '.', $username); // Replaces all spaces with hyphens.
            $username = preg_replace('/[^A-Za-z0-9\-]/', '', $username);
            $username = strtolower($username);
            $counter++;
        } while(User::where('username', $username)->exists());

        return $username;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));
        $job = (new SendVerificationEmail($user))->onConnection('database');
        dispatch($job);

        Auth::login($user);

        if ($request->ajax()) {
            return response([
                'redirect' => $this->redirectTo . '/' . $user->email_token
            ], 200);
        }

        return redirect($this->redirectTo);
    }

    public function verify($token)
    {
        $user = User::where('email_token', $token)->first();
        $user->validated = 1;
        if($user->save()){
            return view('emailconfirmed', ['user'=> $user]);
        }
    }
}
