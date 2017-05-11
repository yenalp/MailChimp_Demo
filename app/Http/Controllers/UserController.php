<?php namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Services\UserService;

class UserController extends BaseController
{
    public function __construct(Request $request, UserService $service)
    {
        parent::__construct($request, $service);
    }

    public function login(Request $request)
    {
        return $this->res(
            $this->service->login(
                $request->get('username'),
                $request->get('password')
            )
        );
    }

    /**
     * Retrieve currently loggin user
     *
     * @param  int $id
     * @return Response
     */
    public function currentUser()
    {
        return $this->res($this->service->currentUser());
    }

    public function logout()
    {
        return $this->res($this->service->logout());
    }

    public function checkTokenExpiry()
    {
        return $this->res($this->service->currentUser());
    }

}
