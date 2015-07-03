<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Plan;
use App\Http\Requests\PlanRequest;
use Auth;
use Idea;
class PlanController extends Controller
{
	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
    {
        //
       // $plans = Plan::where('user_id', Auth::id())
       //     ->orderby('status', 'asc')
       //     ->orderby('updated_at', 'desc')
       //     ->paginate(10);
        return view('plan.index');
    }
    public function lists() {
        return Plan :: with(['ideas' => function($query){
                $query->select(['id','name','status as state', 'plan_id'])
                         ->orderBy('updated_at', 'desc');
                }
                ]
                )
            ->where('user_id', Auth::id())
            ->orderBy('status',  'ASC')
            ->orderBy('updated_at', 'ASC')
            ->select(['id', 'name', 'status as state'])
            ->get();
    }

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create(Request $request)
	{
        $plan = new Plan();
        return  view('plan.create', ['plan'=>$plan]);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store(PlanRequest $request)
    {
        $id = $request->input('id', 0) ;
        $this->validate($request, [
                'name'    => 'required|max:128|unique:plans' . ($id > 0 ? sprintf(',name,%d', $id): ''),
                //'status' => 'required|numeric',
                'budget' => 'required|numeric|min:1',
                ]);
        //
        if ($id <=0 ) {
            return Plan :: create($request->all()+array('user_id'=>$request->user()->id));
        } else{
           return response()->json(['success'=>Plan :: find($id)->update($request->all()), 'message'=>'修改计划成功！']);
        }

    }

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show(PlanRequest $request, $id)
	{
		//
        return view('plan.show', ['plan'=>Plan :: find($id)]);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit(PlanRequest $request, $id)
	{
		//
        $plan = Plan ::find($id);
        return  view('plan.create', ['plan'=>$plan]);

	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update(PlanRequest $request, $id)
    {
        //
        return (string) Plan :: find($id)->update($request->all());
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
