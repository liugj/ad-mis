<?php

namespace App\Http\Controllers\Admin;
use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Idea;
use Auth;
use App\Category;
use App\Flow;
use App\ConsumptionDaily;
use DB;
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
    public function report(Request $request, $id) {
        $format = $request->input('format');
        if ($format == 'json') {
            $results = array();
            $rows =  ConsumptionDaily:: where('idea_id', $request->input('idea_id'))
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
                        DB::raw('sum(cost) as cost'),
                        DB::raw('sum(bid_total) as bid_total'),
                        DB::raw('sum(consumption_total) as consumption_total')
                        )
                ->orderBy('exhibition_total', 'DESC')
                ->get();
            $total  =array('exhibition_total'=>0, 'click_total'=>0, 'open_total'=>0, 'consumption_total'=>0, 'download_total'=>0, 'install_total'=>0, 'cost'=>0, 'bid_total'=>0);
            foreach ($rows as $row) {
                $result = $row->toArray();
                $result['consumable'] = $row->consumable ?$row->consumable->name: '未知';
                $result['consumption_total'] /= 1000; 
                $result['cost'] /= 1000; 
                $result['bid_total'] /= 10000; 
                $result['click_rate'] =0;
                $result['convert_rate']  =0;
                if ($result['exhibition_total'] >0) {
                    $result['click_rate'] = sprintf('%.2f', $result['click_total'] *1.0 / $result['exhibition_total'] *100). '%';
                    $result['convert_rate'] = sprintf('%.2f', $result['open_total'] *1.0/ $result['exhibition_total'] *100) .'%';
                    $result['bid_total'] = sprintf('%.4f', $result['bid_total']*100*1.0/ $result['exhibition_total']/1000); 
                }
                $result['consumption_total'] = sprintf('%.3f', $result['consumption_total']); 
                $result['cost'] = sprintf('%.2f', $result['cost']); 
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
                $total['cost'] = sprintf('%.2f', $total['cost']/1000); 
                $total['bid_total'] = sprintf('%.4f', $total['bid_total']/10000 *100/$total['exhibition_total'] /1000); 
                $results[] = $total; 
            }
            return ['total'=>$rows->count(), 'rows'=> $results];    

            unset($reports['data']);
            return  $reports;    
        }else{
            return view('admin.idea.report', [
             //'ideas'=> Idea:: where('user_id', Auth::admin()->get()->id)->get(), 
             'ideas'=> Idea:: all(), 
             'idea_id'=>$id
             ]);
        }
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
        $id = $request->input('id');
        $attributes = ['auditor_id'=> Auth::admin()->get()->id, 'audited_at'=>date('Y-m-d H:i:s')];
        $idea = Idea :: find($id);
        $idea->update2($attributes+$request->all());
        return ['success'=>'true', 'message'=>'操作成功'];
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
        return view('admin.idea.create',[ 
                'categories' => Category ::where ('parent', 0)->get(),
                'hasStatus' => $request->has('status'), 
                'status' => $request->input('status',0), 
                'idea'      => Idea ::find($id),
                'flows'     => Flow :: all()
                ]
                );
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
        $attributes = ['auditor_id'=> Auth::admin()->get()->id, 'audited_at'=>date('Y-m-d H:i:s')];
        return [
            'success'=> Idea :: where('id', $id)->update($attributes + $request->all()),
            // 'message' => $request->input('status') ==1 ?'广告创意停止成功': '广告创意投放成功'
            ];
    }
}
