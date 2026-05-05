<?php

namespace App\Actions\Fortify;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Laravel\Fortify\Contracts\LoginResponse as LoginResponseContract;

class LoginResponse implements LoginResponseContract
{
    /**
     * Create an HTTP response that represents the object.
     */
    public function toResponse($request): JsonResponse|RedirectResponse
    {
        $user = auth()->user();

        if ($request->wantsJson()) {
            return new JsonResponse('', 200);
        }

        // Redirect based on user role
        if ($user->hasRole('manager')) {
            return redirect()->route('manager-dashboard');
        }

        if ($user->hasRole('hr_admin')) {
            return redirect()->route('dashboard');
        }

        // Default redirect for employees
        return redirect()->route('dashboard');
    }
}
