<?php

namespace App\Http\Controllers\Admin\Auth;

use App\Admin;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Illuminate\Http\Request;
use Auth;
class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers;

    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       $this->middleware('adminGuest', ['except' => 'getLogout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed|min:6',
        ]);
    }

    public function postRegister(Request $request)
    {
        $validator = $this->validator($request->all());

        if ($validator->fails()) {
            $this->throwValidationException(
                $request, $validator
            );
        }

        Auth::admin()->login($this->create($request->all()));
        return response()->json(['success'=> TRUE ]);
        #return redirect($this->redirectPath());
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
       $user =  Admin::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
       return $user;
    }
    public function getLogout()
    {
        Auth::admin()->logout();

        return redirect(property_exists($this, 'redirectAfterLogout') ? $this->redirectAfterLogout : '/admin');
    }
    /**
     * postLogin 
     * 
     * @param Request $request 
     * @access public
     * @return mixed
     */
    public function postLogin(Request $request){
        $this->validate($request, [
                'email' => 'required|email', 'password' => 'required',
                ]);

        $credentials = $this->getCredentials($request);
        if (Auth::admin()->attempt($credentials, $request->has('remember'))) {
            return response()->json(['success'=> TRUE, 'message'=>'登录成功！']);
            // return redirect()->intended($this->redirectPath());
        }
        return response()->json(['success'=> FALSE, 'message'=>$this->getFailedLoginMessage()]);

        //return redirect($this->loginPath())
        //    ->withInput($request->only('email', 'remember'))
        //    ->withErrors([
        //            'email' => $this->getFailedLoginMessage(),
        //            ]);
    }
}
