<?php
namespace Pulse\Api\Controllers;

use DB;
use Message;
use Password;
use Pulse\Models\User;
use Dingo\Api\Facade\API;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;
use Pulse\Api\Requests\User\SignupRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Pulse\Bus\Commands\User\CreateUserCommand;

class AuthController extends BaseController
{

    /**
     * Authorize User
     * @param  Request $request
     *
     * @return Response
     */
    public function authorizeUser(Request $request)
    {
        //Login Credentials
        $credentials = $request->only('email', 'password');

        try {
            //Try to Verify Credentials and Create a Token
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials', 'message' => "Invalid Credentials!"], 401);
            }
        } catch (JWTException $e) {
            //Something went wrong!
            return response()->json(['error' => 'could_not_create_token', 'message' => "Something went wrong!"], 500);
        }

        //Return the generated token
        return response()->json(compact('token'));
    }

    /**
     * Create User
     * @param  SignupRequest $request
     * @return Response
     */
    public function createUser(SignupRequest $request)
    {

        //Dispatch CreateUserCommand
        $user = dispatch(new CreateUserCommand(
            $request->get('name'),
            $request->get('username'),
            $request->get('email'),
            $request->get('password')
            ));

        //Something went wrong
        if (!$user) {
            return response()->json(['error' => 'could_not_create_user', 'message' => "Something went wrong!"], 500);
        }

        //Create JWT Token for the create user
        $token = JWTAuth::fromUser($user);

        //Return the Response
        return response()->json(compact('token'));
    }

    public function forgotPassword(Request $request)
    {
        $this->validate($request, ['email' => 'required|email']);
        $response = Password::broker()->sendResetLink($request->only('email'), function ($message) {
            $message->subject("Pulse | Reset Password");
        });

        switch ($response) {
            case Password::RESET_LINK_SENT:
            return response()->json(['message' => "Password reset link send!"]);

            case Password::INVALID_USER:
            default:
            return $this->response->error("Could not send password reset link.");
        }
    }

    public function resetPassword(Request $request)
    {
        $this->validate($request, ['token' => 'required', 'password' => 'required|min:6|confirmed']);

        $credentials = $request->only(
            'password', 'password_confirmation', 'token'
        );

        $resetPasswordRecord = DB::table('password_resets')->where('token', '=', $credentials['token'])->select('email')->first();

        if($resetPasswordRecord) {
            $credentials['email'] = $resetPasswordRecord->email;
        }

        $token = "";

        $response = Password::broker()->reset($credentials, function ($user, $password) use (&$token) {
            $user->forceFill([
                'password' => $password,
                'remember_token' => str_random(60),
            ])->save();

            $token = JWTAuth::fromUser($user);
        });

        switch ($response) {
            case Password::PASSWORD_RESET:
                return response()->json(compact('token'));

            default:
                return $this->response->error("Could not reset password.", 400);
        }
    }

}
