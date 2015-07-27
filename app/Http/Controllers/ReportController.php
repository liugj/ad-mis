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
        $rows =  ConsumptionDaily :: where('user_id', Auth::user()->id())
            ->where('date', $request->input('date'))
            ->where('consumable_type', 'App\Network')
            ->select('consumable_type', 'datetime', 
                    DB::raw('sum(open_total) as open_total'), 
                    DB::raw('sum(install_total) as install_total'), 
                    DB::raw('sum(download_total) as download_total'), 
                    DB::raw('sum(click_total) as click_total'), 
                    DB::raw('sum(exhibition_total) as exhibition_total'),
                    DB::raw('sum(consumption_total) as consumption_total')
                    )
            ->groupBy('datetime')
            ->orderBy('datetime', 'DESC')
            ->get();
        $total  =array('exhibition_total'=>0 , 'click_total'=>0, 'open_total'=>0, 'consumption_total'=>0, 'download_total'=>0, 'install_total'=>0);
        foreach ($rows as $row) {
            $result = $row->toArray();
            // $result['consumable'] = $row->consumable->name;
            $result['consumable'] = $row->consumable ?$row->consumable->name: '未知';
            $result['consumption_total'] /= 1000; 
            $result['consumption_total'] = sprintf('%.3f', $result['consumption_total']); 
            $result['click_rate'] =0;
            $result['convert_rate']  =0;
            if ($result['exhibition_total'] >0) {
                $result['click_rate'] = sprintf('%.2f', $result['click_total'] *1.0 / $result['exhibition_total'] *100). '%';
                $result['convert_rate'] = sprintf('%.2f', $result['open_total'] *1.0/ $result['exhibition_total'] *100) .'%';
            }
            $datetime = substr($result['datetime'], 10);
            $result['datetime'] = sprintf('%d:00-%d:00', $datetime, $datetime+1);
            foreach ($total as $key=>$value){
                $total[$key] += $row[$key];
            }

            $results[] = $result;
        }
        if ($total['exhibition_total']) {
            $total['datetime']= '总计';
            $total['consumption_total'] /= 1000;  
            $total['click_rate'] = sprintf('%.2f', $total['click_total'] *1.0 / $total['exhibition_total'] *100). '%';
            $total['convert_rate'] = sprintf('%.4f', $total['open_total'] *1.0/ $total['exhibition_total'] *100) .'%';
            $total['consumption_total'] = sprintf('%.3f', $total['consumption_total']); 


            $results[] = $total; 
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
				->where('user_id', Auth::user()->id())
				->where('consumable_type', $request->input('consumable_type'))
				->where('date', $request->input('date'))
				->groupBy('consumable_id')
				->groupBy('date')
				->select('consumable_type', 'date', 'consumable_id',
						DB::raw('sum(open_total) as open_total'), 
						DB::raw('sum(install_total) as install_total'), 
						DB::raw('sum(download_total) as download_total'), 
						DB::raw('sum(click_total) as click_total'), 
						DB::raw('sum(exhibition_total) as exhibition_total'),
						DB::raw('sum(consumption_total) as consumption_total')
					)
				->orderBy('exhibition_total', 'DESC')
				->get();
			$total  =array('exhibition_total'=>0, 'click_total'=>0, 'open_total'=>0, 'consumption_total'=>0, 'download_total'=>0, 'install_total'=>0);
			foreach ($rows as $row) {
				$result = $row->toArray();
				$result['consumable'] = $row->consumable ?$row->consumable->name: '未知';
				$result['consumption_total'] /= 1000; 
				$result['click_rate'] =0;
				$result['convert_rate']  =0;
				if ($result['exhibition_total'] >0) {
					$result['click_rate'] = sprintf('%.2f', $result['click_total'] *1.0 / $result['exhibition_total'] *100). '%';
					$result['convert_rate'] = sprintf('%.2f', $result['open_total'] *1.0/ $result['exhibition_total'] *100) .'%';
				}
				$result['consumption_total'] = sprintf('%.3f', $result['consumption_total']); 
				foreach ($total as $key=>$value){
					$total[$key] += $row[$key];
				}
				$results[] = $result;
			}
            if ($total['exhibition_total'] >0) {
                $total['consumable']= '总计';
                $total['consumption_total'] /= 1000;  
                $total['click_rate'] = sprintf('%.2f', $total['click_total'] *1.0 / $total['exhibition_total'] *100). '%';
                $total['convert_rate'] = sprintf('%.4f', $total['open_total'] *1.0/ $total['exhibition_total'] *100) .'%';
                $total['consumption_total'] = sprintf('%.2f', $total['consumption_total']); 
                $results[] = $total; 
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
