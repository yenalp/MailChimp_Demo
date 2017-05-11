<?php namespace App\Services;

use App\Models\User;
use App\Services\BaseService;
use Illuminate\Support\Facades\Hash;
use App\Exceptions\LoginException;
use App\Exceptions\UserException;
use Illuminate\Support\Facades\Validator;

class UserService extends BaseService
{
    public function login($username, $password)
    {
        $user = User::where('user_name', '=', $username)->firstOrFail();
        if (!$user->canLogin()) {
            throw new LoginException(
                "Login Failed for User {$username}. Your account has been suspended",
                "Login failed for User  {$username} because the account has been disabled",
                401,
                null
            );
        }

        if (!Hash::check($password, $user->password)) {
            throw new LoginException(
                "Login Failed for User {$username}. Invalid password. Please try again.",
                "Login failed for User  {$username} because the password was incorrect",
                401,
                null
              );
        }

        $siteId = $this->context->siteId;

        if (!$user->canLoginToSite($siteId)) {
            throw new LoginException(
                "Login Failed for User {$username}. You are not authorized to visit this site.",
                "Login failed for User {$username} because the user type
                    {$user->user_type} was not in the allowed SITE_USER_TYPES",
                    401,
                    null
            );
        }

        /**
        * Check to see if the date period is longer than 2 hours
        */

        if ($user->isTokenExpired($siteId)) {
            $user->setNewToken($siteId);
        }

        $user->refreshTokenExpiry($siteId);
        return $user;
    }

    public function currentUser()
    {
        return $this->context->user;
    }

    public function logout()
    {
        return $this->context->user->logout($this->context->siteId);
    }

    public function updatePassword($id, $newPassword, $passwordConfirmation)
    {
        $user = User::findOrFail($id);

        $validator = Validator::make([
            'password' => $newPassword,
            'password_confirmation' => $passwordConfirmation
        ], [
            'password' => 'required|confirmed|min:8|max:100'
        ]);

        if ($validator->fails()) {
            throw new UserException(
                $validator->errors()->first('password'),
                '',
                422
            );
        }

        $user->update([
            // 'password' => Hash::make($newPassword)
            'password' => $newPassword
        ]);

        return $user;
    }
}
