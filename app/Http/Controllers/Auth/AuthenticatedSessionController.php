<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Redirect;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();
       
        return $this->manageUserLogin();
    }

    /**
     * Manage user login
     * @return mixed|RedirectResponse
     */
    private function manageUserLogin()
    {
        $getRole = UserRole::getRole();
        switch($getRole){
            case UserRole::ROLE_SUPER_ADMIN:
                return redirect()->intended(route('import.form', absolute: false));
                break;
            default:
                return $this->validateUserRedirection();
                break;
        }
    }

    /**
     * Manage user redirection
     * @return mixed|RedirectResponse
     */
    private function validateUserRedirection()
    {
        if(Auth::user()->new == User::NEW_USER){
            //update
            User::where('id', Auth::user()->id)->update(['new' => User::NOT_NEW_USER]);
            Auth::logout();
            return redirect()->intended(route('password.request', absolute: false));
        }else{
            return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
