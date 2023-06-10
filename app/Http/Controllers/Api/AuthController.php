<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Users\RegisterRequest;
use App\Http\Requests\Users\LoginRequest;
use App\Repositories\UserRepository;
use Auth;
use Hash;
use Validator;

class AuthController extends Controller
{
    private $repository;

    /**
    *   define repository
    * @param UserRepository $repository
    */

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * Register User
     * @param Request $request
     * @return Json Token
     */
    public function register(RegisterRequest $request)
    {
        try {
            $data = $request->validated();

            if($request->hasFile('avatar')) {
                $file = $request->file('avatar');
                $data['avatar'] = $this->repository->uploadImage($file);
            }

            $data['password'] = Hash::make($data['password']);

            $user = $this->repository->create($data);

            return response()->json([
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'Something wrong',
            ], 500);
        }
    }


    /**
     * Login
     * @param Request $request
     * @return Json Token
     */
    public function login(LoginRequest $request)
    {
        try {


            if(!Auth::attempt($request->only(['email', 'password']))){
                return response()->json([
                    'status' => false,
                    'message' => 'Email & Password does not match with our record.',
                ], 400);
            }

            $user = Auth::user();

            return response()->json([
                'token' => $user->createToken("API TOKEN")->plainTextToken
            ], 200);

        } catch (\Throwable $th) {
            return response()->json([
                'message' => 'something wrong',
            ], 500);
        }
    }
}
