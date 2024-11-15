<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminLoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/admin/dashboard';


    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        // dd(auth('admin')->user());
        $this->middleware('guest:admin')->except('logout');
    }



    public function login_form()
    {
       return view('auth.admin-login');
    }



     public function login(Request $request)
    {
        request()->validate([
            'email'=>'required|string|max:191',
            'password'=>'required|string|max:191',

        ]);

        $credentials = $request->only('email', 'password');
        
        if (Auth::guard('admin')->attempt($credentials)) {
            // Authentication passed...
            return redirect('admin/dashboard');
        }else{

        return back()->with('loginerror','Your email & password do not match our records!');

        }

    }



}
