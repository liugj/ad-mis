<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ReportRequest;
use App\Http\Controllers\Controller;
use Auth;
use App\ConsumptionDaily;
use App\Industry;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
       // $consumptionsDaily =  ConsumptionDaily ::with(['idea'=>function ($query){return $query->select(['id','name']);}])
         //   ->where('user_id', Auth::id())
           // ->orderBy('updated_at', 'DESC')
           // ->paginate(10);
        return view('report.index');
    }
    function lists(ReportRequest $request){
        $planId = $request->input('plan_id');
        $ideaId = $request->input('idea_id');
        if ($ideaId>0 || $planId >0 ) {
            $rows =  ConsumptionDaily:: where($ideaId >0 ? 'idea_id':'plan_id', $ideaId>0? $ideaId: $planId)
                ->where('user_id', Auth::id())
                ->where('consumable_type', $request->input('consumable_type'))
                ->where('date', $request->input('date'))
                ->get();
            return ['total'=>$rows->count(), 'rows'=> $rows];    

        }
        return ['rows'=>[], 'total'=>0];
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
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
