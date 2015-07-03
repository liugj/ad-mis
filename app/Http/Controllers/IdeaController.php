<?php

namespace App\Http\Controllers;
use Log;
use Illuminate\Http\Request;

use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Idea;
use App\Industry;
use App\Group;
use App\ClickAction;
use App\Category;
use App\Os;
use App\Device;
use App\Network;
use App\Operator;
use App\Classification;
use App\Age;
use App\Region;
use App\Plan;
use Auth;
use App\Http\Requests\IdeaRequest;
use App\Http\Requests\PlanRequest;

class IdeaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $ideas = Idea::with([
                'click_action', 
                'type_name',
                'plan' => function($query) {
                $query->select(['id', 'name']);
                }]
                )
            ->where('user_id', Auth::id())
            ->orderby('updated_at', 'desc')
            ->paginate(10);
        return view('idea.index', ['ideas'=>$ideas]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create(PlanRequest $request)
    {
        //
        $idea = new Idea();
        $groups = Group :: all();
        $industries = Industry :: all();
        $clickActions = ClickAction :: all();
        $categories = Category :: all();
        $devices = Device :: all();
        $networks = Network :: all();
        $oss = Os :: all();
        $ages = Age :: all();
        $operators = Operator :: all();
        $regions = Region :: all();
        $classification = Classification:: all();
    //    $plans          = Plan :: where('user_id', Auth ::id())->get();

        return view('idea.create', [
                'idea'          => $idea, 
                'groups'        => $groups, 
                'clickActions'  => $clickActions,
                'industries'  => $industries,
                'categories'  => $categories,
                'regions'  => $regions,
                'oss'  => $oss,
                'devices'  => $devices,
                'networks'  => $networks,
                'ages'  => $ages,
                'operators'  => $operators,
                'classification'  => $classification,
                'plan_id'          => $request->input('plan_id'),
                ]
                ); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PlanRequest $request)
    {
        $id = $request->input('id');
        $this->validate($request, [
                'name'    => 'required|max:128|unique:ideas'. ($id>0? sprintf(',name,%d', $id): ''),
                'plan_id' => 'required|numeric|min:1',
                'bid' => 'required|numeric|min:1',
                'budget' => 'required|numeric|min:1',
                'type' => 'required|max:64',
                //'status' => 'required|numeric',
                'pay_type' => 'required|numeric',
                'display_type' => 'required|numeric',
                'size_id' => 'required|numeric',
                'frequency' => 'required|numeric',
                'click_action_id' => 'required|numeric',
                'link' => 'required|max:65535',
                ]);
        //
        if ($id) {
            return  response()->json(['success' => Idea ::find($id)->update($request->all()),
                      'message'=> '']
                    );
        }else{
            $idea = Idea :: create(array('user_id'=>$request->user()->id) + $request->all());
            return response()->json(['success' => TRUE,
                    'message'=>'']
            );
        }
    }
    public function test()
    {
        return view('idea.test');
    }
    /**
     * upload 
     * 
     * @param Request $request 
     * @access public
     * @return mixed
     */
    public function upload(Request $request) {
        $imagePath = sprintf('/images/%d/', Auth:: id()/1000);
        file_exists(public_path().$imagePath)?'': mkdir(public_path(). $imagePath, 0755, TRUE);
        $fileName = sprintf('%s%s.%s',str_random(10), microtime(TRUE), $request->file('img')->getClientOriginalExtension() );
        $request->file('img')->move(public_path(). $imagePath, $fileName);
        $thumb = new \Imagick();
        $thumb->readImage(public_path(). $imagePath. $fileName);

        return ['url'=>$imagePath. $fileName, 'status'=>'success', 'width'=>$thumb->getImageWidth(), 'height'=>$thumb->getImageHeight()];
    }
    public function crop(Request $request) {
        foreach(["imgUrl","imgInitW","imgInitH","imgW","imgH","imgY1","imgX1","cropH","cropW"] as $v) {
            $$v = $request->input($v);
        }
        $imagick = new \Imagick(public_path(). $imgUrl);
        $imagick->cropImage($cropH, $cropH, $imgX1, $imgY1);
        $cropFileName  = str_replace('/images/', '/resizeImages/', public_path() . $imgUrl);
        $cropPath = substr($cropFileName, 0, strrpos($cropFileName, '/'));
        file_exists($cropPath) ?: mkdir($cropPath, 0755, TRUE);
        file_put_contents($cropFileName, $imagick->getImageBlob());
        return ['status'=>'success', 'url'=> str_replace('/images/', '/resizeImages/', $imgUrl)];
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show(IdeaRequest $request, $id)
    {
        //
        return view('idea.show', ['idea'=>Idea :: find($id)] );
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit(IdeaRequest $request, $id)
    {
        //
        $idea = Idea ::find($id);
        $groups = Group :: all();
        $industries = Industry :: all();
        $clickActions = ClickAction :: all();
        $categories = Category :: all();
        $devices = Device :: all();
        $networks = Network :: all();
        $oss = Os :: all();
        $ages = Age :: all();
        $operators = Operator :: all();
        $regions = Region :: all();
        $classification = Classification:: all();
        $plans          = Plan :: where('user_id', Auth ::id())->get();

        return view('idea.create', [
                'idea'          => $idea, 
                'groups'        => $groups, 
                'clickActions'  => $clickActions,
                'industries'  => $industries,
                'categories'  => $categories,
                'regions'  => $regions,
                'oss'  => $oss,
                'devices'  => $devices,
                'networks'  => $networks,
                'ages'  => $ages,
                'operators'  => $operators,
                'classification'  => $classification,
                'plans'          => $plans,
                ]
                ); 
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function update(IdeaRequest $request, $id)
    {
        Idea ::find($id)->update($request->all());
        return $id;
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
