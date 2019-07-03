<?php
namespace App\Http\Controllers\Auth;
use App\Http\Controllers\Controller;
use App\Services\AuthenticationService;
use App\User;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
class LoginController extends Controller
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
    
    protected $redirectTo = '/users';
    
    private $authenticationService;
    
    public function __construct(AuthenticationService $authenticationService)
    {
        $this->middleware('guest')->except('logout');
        $this->authenticationService = $authenticationService;
    }
    
    public function login(Request $request)
    {
        $this->validateLogin($request);
        
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
      
        if ($this->guard()->validate($this->credentials($request))) {
            $user = $this->guard()->getLastAttempted();
            
            if ($user->is_active && $this->attemptLogin($request)) {
                return $this->sendLoginResponse($request);
            } else {
                
                $this->incrementLoginAttempts($request);
                return redirect()
                    ->back()
                    ->withInput($request->only($this->username(), 'remember'))
                    ->with('error' , 'You must be active to login.');
            }
        }
        
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }
    
    public function authenticated(Request $request, User $user)
    {
        $this->authenticationService->storeLoginActivityOfUser($request, $user);
    }
}