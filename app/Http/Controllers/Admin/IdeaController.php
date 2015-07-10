<?php

namespace App\Http\Controllers\Admin;
use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Idea;
use Cache;
class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        return view('admin.idea.index');
    }
    public function lists(Request $request) {
        $status = $request->input('status', 0);
        $ideas = Idea ::with(['click_action'=>function($q){
               return $q->select('id', 'name');
             },
             'plan' => function ($q) {
                 return $q->select('id', 'name', 'status');
                 },
             'size',    
             ])
            ->where('status', $status)
            ->orderBy ('updated_at', 'DESC')
            ->paginate(10)
            ->toArray();
        $ideas['rows'] = $ideas['data'];
        unset($ideas['data']);
        return $ideas;

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create($request)
    {
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(Request $request)
    {
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(Request $request, $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(Request $request, $id)
    {
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
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
        return [
            'success'=> Idea :: where('id', $id)->update($request->all()),
           // 'message' => $request->input('status') ==1 ?'广告创意停止成功': '广告创意投放成功'
        ];
    }
}
