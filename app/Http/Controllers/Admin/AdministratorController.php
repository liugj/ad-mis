<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Admin;
use Auth;
class AdministratorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('admin.administrator.index');
    }
    function lists(Request $request) {
        $administrator_id = Auth :: admin()->get()->id;
        $administrators = Admin:: where('administrator_id', $administrator_id)
            ->orderBy ('updated_at', 'DESC')
            ->paginate(10)
            ->toArray();
        $administrators['rows'] = $administrators['data'];
        unset($administrators['data']);
        return $administrators;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('admin.administrator.create', ['administrator'=> new Admin()]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
        //
        $id      = $request->input('id', 0) ;
        $validator = [
                'email'    => 'required|max:64|email|unique:administrators,email,' .($id>0?$id: 'NULL') . ',id',
                'name'     => 'required|min:2|max:64',
                'password'   => 'required|min:6|max:32|confirmed',
                'status'   =>'required|numeric'
                ];
        if ($id >0) {
            unset($validator['password']);
        }        
        $this->validate($request, $validator);
        $administrator_id = Auth :: admin()->get()->id;

        if ($id>0) {
             return ['success'=>Admin :: find($id)->update($request->all()), 'message'=>'修改用户成功', 'id'=>$id];
        }else{
            $attributes = $request->all() ; 
            $attributes['password'] = bcrypt($attributes['password']);
            Admin :: create( ['administrator_id'=> $administrator_id] + $attributes);
            return ['success'=>TRUE,'message'=>'添加用户成功'];
        }
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $qequest, $id)
    {
        //
        return view('admin.administrator.create', ['administrator'=>  Admin ::find($id)]);
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
}
