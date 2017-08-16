<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Illuminate\Auth\Events\Registered;

class RegisterController extends ApiController
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->middleware('cors');
        $this->userRepository = $userRepository;
    }

    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        event(new Registered($user = $this->create($request->all())));

        if (! $user){
            return $this->errorRespond();
        }

        $request->request->add([
            'username' => $request->email,
            'password' => $request->password,
            'grant_type' => 'password',
            'client_id' => config('personal.api_client_id'),
            'client_secret' => config('personal.api_client_secret'),
            'scope' => '*'
        ]);

        $proxy = Request::create( '/oauth/token' , 'POST');

        return Route::dispatch($proxy);
    }

    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255|unique:users',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ],[
            'name.unique' => '该用户名已存在',
            'email.unique' => '该邮箱已存在',
        ]);
    }

}
