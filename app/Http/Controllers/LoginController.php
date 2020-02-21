<?php

namespace App\Http\Controllers;

use App\Http\Resources\UserResource;
use App\Interfaces\UserRepositoryInterface;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

//class LoginController extends Controller
class LoginController extends BaseAPIControllerExtended
{
    public function __construct(UserRepositoryInterface $repo)
    {
        // parent::__construct($repo, Video::class, 'Video');
        parent::__construct($repo, UserResource::class, 'Login');
    }

    public function login(Request $request)
    {
        /*$input = $request->only('email', 'password');
        $token = null;

        if (!$token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid Email or Password',
            ], 401);
        }

        return response()->json([
            'success' => true,
            'token' => $token,
        ]);*/

        try {
            $input = $request->only('email', 'password');
            $token = null;

            if (!$token = JWTAuth::attempt($input)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid Email or Password',
                ], 401);
            }

            return response()->json([
                'success' => true,
                'token' => $token,
            ]);
        } catch (Exception $e) {
            return $this->handleException('LOGIN ' . $this->resourceName . ' error', $e);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function logout(Request $request)
    {
        //$this->user = JWTAuth::parseToken()->authenticate();

        try {
            $this->user = JWTAuth::parseToken()->authenticate();

            JWTAuth::invalidate($this->user->id);

            return response()->json([
                'success' => true,
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'message' => 'Sorry, the user cannot be logged out',
            ], 500);
        }

        // try {
        //     JWTAuth::invalidate($request->token);

        //     return response()->json([
        //         'success' => true,
        //         'message' => 'User logged out successfully'
        //     ]);
        // } catch (JWTException $exception) {
        //     return response()->json([
        //         'success' => false,
        //         'message' => 'Sorry, the user cannot be logged out',
        //     ], 500);
        // }
    }
}
