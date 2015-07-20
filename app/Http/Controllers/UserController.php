<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Validator;
use Auth;
use App\Basic;
use App\User;
use Log;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('user.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store()
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        //
    }
    public function profile()
    {
        echo public_path();
        $basic = Basic :: find(Auth::id());
        return view('user.profile',['user'=>Auth::user(), 'basic'=>$basic]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update($id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy($id)
    {
        //
    }
    public function postChangePassword(Request $request) {
        $validator = validator :: make($request->all(), [
                'oldPassword'=>'required|min:6|max:32',
                'password' =>'required|min:6|max:32|confirmed',
                // 'confirmPassword'=>'confirmed:newPassword',
                ]);
        $user =  User ::find(Auth::user()->id());
        Log ::info('request:', $request->all());
        Log ::info('user:', $user->toArray());
        $validator->after(function($validator) use($user, $request) {
                if (!password_verify($request->input('oldPassword'), $user->password)){
                $validator->errors()->add('oldPassword', '旧密码错误，请核实后重新输入');
                }
                }
                );
        if ($validator->fails()) {
            $this->throwValidationException(
                    $request, $validator
                    );
        }
        $user -> password = bcrypt($request->input('password'));
        $user->save();

        return ['success'=>TRUE, 'message'=>'密码修改成功'];
    }
    public function getChangePassword() {
        return view('user.change_password'); 
    }
    public function getChange() 
    {
       $user =  Auth :: user()->get();
       $basic = Basic ::find($user->id);
       return view('user.create', ['user'=>$user, 'basic'=>$basic]);
    }
    public function postChange(Request $request)
    {
        $this->validate($request,[
        'name'=>'required|min:2|max:64',
        'company'=>'required|min:2|max:128',
        'address'=>'required|min:2|max:64',
        'phone'=>'required|min:2',
        ]);
        $id = Auth :: user()->get()->id;
        $user  = User :: find($id);;
        $basic = Basic :: find($id);;
        $user->name = $request->input('name');

        $basic->company = $request->input('company');
        $basic->phone = $request->input('phone');
        $basic->address = $request->input('address');
        $basic->save();
        $user->save();
        return ['success'=>TRUE, 'message'=>'信息成功'];
    }
}
