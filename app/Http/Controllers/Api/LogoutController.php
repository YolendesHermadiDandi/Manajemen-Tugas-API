<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PHPOpenSourceSaver\JWTAuth\Facades\JWTAuth;

class LogoutController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        // Remove Token
        $RemoveToken = JWTAuth::invalidate(JWTAuth::getToken());

        if ($RemoveToken) {
            // Return response token
            return response()->json([
                'success' => true,
                'message' => 'Logout Berhasil'
            ]);
        }
    }
}
