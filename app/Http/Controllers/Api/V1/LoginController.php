<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Requests\LoginRequest;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;

class LoginController extends ApiController
{
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => 'required',
            'password' =>'required'
        ]);
    }
    
    public function login(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            return response()->json([
                'error' => '验证没有通过',
                'data' => $validator->errors()->toArray()
            ], 422);
        }

        $request->request->add([
            'username' => $request->email,
            'password' => $request->password,
            'grant_type' => 'password',
            'client_id' => config('personal.api_client_id'),
            'client_secret' => config('personal.api_client_secret'),
            'scope' => '*'
        ]);

        $proxy = Request::create('/oauth/token', 'POST');

        return Route::dispatch($proxy);
    }
}
