<?php

namespace App\Http\Controllers;

use Hash;
use Validator;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\PasswordReset;
use App\Events\UserForgotPassword;
use App\Http\Requests\ResetPasswordRequest;
use App\Http\Requests\ChangePasswordRequest;
use App\Http\Requests\SendResetPasswordEmailRequest;

class PasswordController extends Controller {

	public function showForgotPasswordPage() {
		return view('auth.password.forgot');
	}

	public function sendResetPasswordLink(SendResetPasswordEmailRequest $request) {
		$user = User::byEmail($request->get('email'))->first();

        if (!PasswordReset::hasPendingToken($user)) {
            $user->passwordResetToken()->create([
                'token' => str_random(128),
            ]);
        }

        // fire the event to send the email
        event(new UserForgotPassword($user));

        return response()->json([
        	'data' => [
        		'status' => 'success',
        		'message' => 'Password reset link has been emailed to you. Note: It is only valid for 5 minutes',
        	]
        ], 200);
	}

	public function showResetPasswordPage(PasswordReset $token, Request $request) {

		if (($user = User::byEmail($request->get('email'))->first()) && ($token->user->email === $request->get('email'))) {

			if (!$token->isExpired()) {
				return view('auth.password.reset')->with([
					'email' => $request->get('email'),
					'token' => $token->token,
				]);
			}

			$token->delete();
		}

		return redirect()->route('password.forgot');
	}

	public function resetPassword(PasswordReset $token, ResetPasswordRequest $request) {
		if (($user = User::byEmail($request->get('email'))->first()) && ($token->user->email === $request->get('email'))) {

			$token->user->update([
				'password' => bcrypt($request->get('password')),
			]);

			$token->delete();

			return response()->json([
				'data' => [
					'status' => 'success',
					'message' => 'Password successfully reset',
				]
			], 200);
		}

		return response()->json([
			'data' => [
				'status' => 'failed',
				'message' => 'Token and email do not match',
			]
		], 200);
	}

	public function showChangePasswordPage() {
		return view('auth.password.change');
	}

	public function changePassword(ChangePasswordRequest $request) {

		$user = $request->user()->update([
			'password' => bcrypt($request->get('password')),
		]);

		return response()->json([
			'data' => [
				'status' => 'success',
				'message' => 'Password successfully changed',
			]
		], 200);
	}

	public function checkCurrentPassword(Request $request) {

        if (Hash::check($request->get('currentPassword'), $request->user()->password)) {
            return response()->json([
            	'data' => [
            		'status' => 'success',
            		'message' => 'Matched with the current password',
            	]
            ], 200);
        }

        return response()->json([
            'data' => [
            	'status' => 'failed',
            	'message' => 'Does not match with the current password',
            ]
		], 200);
	}
}
