<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\ReportRequest;
use App\Http\Controllers\Controller;
use Auth;
use App\ConsumptionDaily;
use App\Industry;
use DB;
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
        return view('report.index');
    }
    public function summary(Request $request) 
    {
        $results = array();
        $rows =  ConsumptionDaily :: where('user_id', Auth::id())
           ->where('date', $request->input('date'))
           ->where('consumable_type', 'App\Industry')
           ->select('consumable_type', 'datetime', 'consumable_id', 
                       DB::raw('sum(click_total) as click_total'), 
                       DB::raw('sum(exhibition_total) as exhibition_total'),
                       DB::raw('sum(consumption_total) as consumption_total')
                       )
           ->groupBy('datetime')
          ->groupBy('consumable_id')
          ->orderBy('datetime', 'DESC')
          ->get()
          ;
        foreach ($rows as $row) {
            $result = $row->toArray();
            $result['consumable'] = $row->consumable->name;
            $results[] = $result;
        }
        return ['total'=>$rows->count(), 'rows'=> $results];    
    }
    public function lists(ReportRequest $request)
    {
        $planId = $request->input('plan_id');
        $ideaId = $request->input('idea_id');
        if ($ideaId>0 || $planId >0 ) {
            $results = array();
            $rows =  ConsumptionDaily:: where($ideaId >0 ? 'idea_id':'plan_id', $ideaId>0? $ideaId: $planId)
                ->where('user_id', Auth::id())
                ->where('consumable_type', $request->input('consumable_type'))
                ->where('date', $request->input('date'))
                ->get();
            foreach ($rows as $row) {
                $result = $row->toArray();
                $result['consumable'] = $row->consumable->name;
                $result['consumption_total'] /= 100; 
                $results[] = $result;
            }
            return ['total'=>$rows->count(), 'rows'=> $results];    

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
     4*
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
