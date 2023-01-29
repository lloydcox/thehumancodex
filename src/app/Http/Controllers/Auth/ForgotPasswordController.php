<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordReset;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ForgotPasswordController extends Controller
{

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/passwords/email/info';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $email = $request->get('email');
        $user = User::where('email', $email)->first();

        // Validate email
        if (!$this->emailIsValid($email)) {
            return $this->sendResetLinkFailedResponse($request, 'passwords.user');
        }

        // Randomize password
        $temporaryPassword = str_random(18);

        // Send temporary password via email
        Mail::to($user)->send(new PasswordReset($temporaryPassword));

        // Set temporary user password
        $user->password = bcrypt($temporaryPassword);
        $user->save();

        return $this->sendResetLinkResponse();
    }

    public function showResetInfo()
    {
        return view('auth.passwords.info');
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkResponse()
    {
        return response([
            'redirect' => $this->redirectTo
        ], 200);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return response([
            'message' => trans($response)
        ], 422);
    }

    protected function emailIsValid($email)
    {
        $validator = Validator::make(compact('email'), [
            'email' => 'required|exists:users'
        ]);

        return !$validator->fails();
    }
}
