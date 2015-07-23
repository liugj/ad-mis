<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests\Admin\UserRequest;
use App\Http\Controllers\Controller;

use Auth;
use App\Basic;
use App\User;
use App\Recharge;
use App\Consumption;
use App\ConsumptionDaily;
use DB;
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
        return view('admin.user.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('admin.user.create', ['user'=> new User(), 'basic'=> new Basic()]);
    }
    public function lists(Request $request) {
        $status = $request->input('status', 0);
        $users = User ::with('basic')
            ->where('administrator_id',  Auth :: admin()->get()->id)
            ->where('status', $status)
            ->orderBy ('updated_at', 'DESC')
            ->paginate(10)
            ->toArray();
        $users['rows'] = $users['data'];
        unset($users['data']);
        return $users;
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
            'company'   =>'required|min:2|max:128',
            'phone'   =>'required|min:2|max:64',
            'address'   =>'required|min:2',
            ];
        if ($id >0) {
            unset($validator['password']);
        }        
        $this->validate($request, $validator);
        $data = $request->all();
        if ($id >0) {
            User :: where ('id', $id)->update(['name'=> $data['name'], 'email'=> $data['email']] );
            Basic :: where('id', $id)->update(['company'=>$data['company'], 'phone'=>$data['phone'], 'address'=> $data['address']]);
            return ['success'=>TRUE, 'message'=>'修改客户信息成功'];
        }else{
            $data['password'] = bcrypt($data['password']);
            $data['administrator_id'] = Auth :: admin()->get()->id;

             User :: create($data)->basic()->create(['company'=>$data['company'], 'phone'=>$data['phone'], 'address'=> $data['address']]);
            
            return ['success'=>TRUE, 'message'=>'添加客户信息成功'];
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
    public function edit($id)
    {
        //
        return view('admin.user.create', ['user'=> User ::find ($id), 'basic'=> Basic ::find($id)]);
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
    public function recharge(UserRequest $request, $id) {
        $format = $request->input('format');
        if ($format == 'json') {
            $recharges = Recharge  :: where('user_id',  $id)
                ->orderBy ('updated_at', 'DESC')
                ->paginate(10)
                ->toArray();
            $recharges['rows'] = $recharges['data'];
            unset($recharges['data']);
            return $recharges;
        }else{
            return view('admin.user.recharge', ['basic' => Basic ::find($id), 'user'=> Auth::admin()->get()]);
        }
    }
    public function consume(UserRequest $request, $id) {
        $format = $request->input('format');
        if ($format == 'json') {
            $recharges = Consumption  :: with(['plan'=>function($q){
                return $q->select(['id','name', 'status']);
                },
                'idea'=> function ($q) {
                    return $q->select(['id','name', 'status']);
                    }])
                ->where('user_id',  $id)
                ->orderBy ('updated_at', 'DESC')
                ->paginate(10)
                ->toArray();
            $recharges['rows'] = $recharges['data'];
            unset($recharges['data']);
            return $recharges;
        }else{
            return view('admin.user.consume', ['basic' => Basic ::find($id), 'user'=> Auth::admin()->get()]);
        }
    }
    public function report(UserRequest $request, $id) {
        $format = $request->input('format');
        if ($format == 'json') {
            $reports = ConsumptionDaily :: where('user_id',  $id)
                ->where('date', $request->input('date'))
                ->where('consumable_type', 'App\Network')
                ->select('consumable_type', 'date', 
                        DB::raw('sum(open_total) as open_total'), 
                        DB::raw('sum(install_total) as install_total'), 
                        DB::raw('sum(download_total) as download_total'), 
                        DB::raw('sum(click_total) as click_total'), 
                        DB::raw('sum(exhibition_total) as exhibition_total'),
                        DB::raw('sum(consumption_total) as consumption_total')
                        )
                ->groupBy('date')
                ->orderBy('date', 'DESC')
                ->paginate(10)
                ->toArray();

            $reports['rows'] = $reports['data'];
            unset($reports['data']);
            return $reports;
        }else{
            return view('admin.user.report', ['basic' => Basic ::find($id), 'user'=> Auth::admin()->get()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        //
        $result =  User :: findOrFail($id)->update(['status'=> $request->input('status')]);
        return ['success'=>TRUE];
    }
}
