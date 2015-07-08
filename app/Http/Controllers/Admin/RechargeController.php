<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests\RechargeRequest;
use App\Http\Controllers\Controller;
use App\Recharge;
use Auth;
use DB;
use App\Basic;
class RechargeController extends Controller
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
    public function create(RechargeRequest $request)
    {
        //
        return view('admin.recharge.create',['user_id'=>$request->input('user_id')]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(RechargeRequest $request)
    {
        //
        $this->validate($request, [
          'user_id'=>'required|min:1',
          'money'  => 'required|numeric'
        ]);
        $administrator_id = Auth ::admin()->id();
        $recharge = $request->all() + array('administrator_id'=>$administrator_id);
        DB::transaction(function () use($recharge){
                Recharge ::create ($recharge);
                Basic :: where ('id', $recharge['user_id'])->increment('total', $recharge['money']);
                
                });
        return ['success'=>true, 'message'=>''];
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
}
