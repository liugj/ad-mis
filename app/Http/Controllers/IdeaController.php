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
use App\Type;
use Validator;
use Cache;
use App\PayType;
use App\Location;
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
            ->where('user_id', Auth::user()->id())
            ->orderby('updated_at', 'desc')
            ->paginate(10);
        return view('idea.index', ['ideas'=>$ideas]); 
    }
    public function bid(Request $request){
        
        return Idea :: MinBid($request->all());
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
        $groups       =  Cache:: rememberForever('groups', function(){  return Group :: all();});
        $industries   =  Cache:: rememberForever('industries', function(){ return Industry :: all();});
        $clickActions =  Cache:: rememberForever('ClickActions', function(){ return ClickAction :: all();});
        $categories   =  Cache:: rememberForever('categories', function(){ return Category :: all();});
        $devices      =  Cache:: rememberForever('devices', function(){ return  Device :: all();});
        $networks     =  Cache:: rememberForever('networks', function(){ return Network :: all();});
        $oss          =  Cache:: rememberForever('oss', function(){ return  Os :: all();});
        $ages         =  Cache:: rememberForever('ages', function(){ return  Age :: all();});
        $operators    =  Cache:: rememberForever('operators', function(){ return  Operator :: all();});
        $regions      =  Cache:: rememberForever('regions', function(){ return  Region :: all()->sortBy('parent');});
        $locations    =  Cache:: rememberForever('locations', function(){ return  Location :: all();});
        $types        =  Cache:: rememberForever('types', function(){ return  Type :: all();});
        $classification =  Cache:: rememberForever('classification', function(){ return  Classification:: all();});

        return view('idea.create', [
                'idea'          => $idea, 
                'groups'        => $groups, 
                'clickActions'  => $clickActions,
                'industries'  => $industries,
                'categories'  => $categories,
                'regions'  => $regions,
                'locations'  => $locations,
                'oss'  => $oss,
                'devices'  => $devices,
                'networks'  => $networks,
                'ages'  => $ages,
                'operators'  => $operators,
                'classification'  => $classification,
                'plan_id'          => $request->input('plan_id'),
                'types' => $types,
                'payTypes' => PayType::$names,
                ]
                ); 
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(IdeaRequest $request)
    {
        $id = $request->input('id');
        $user_id  =  Auth::user()->id();
        $v  =  Validator::make($request->all(), [
                'name'    => 'required|max:128|unique:ideas,name,' .($id>0?$id: 'NULL') . ',id,user_id,'. $user_id,
                'plan_id' => 'required|numeric|min:1',
                'bid' => 'required|numeric|min:0',
                'budget' => 'required|numeric|min:0',
                'device' => 'required|max:64',
                'group' => 'required|max:64',
                'pay_type' => 'required|numeric',
                'click_action_id' => 'required|numeric',
                'link' => 'required|max:65535',
                ]);
        $v->sometimes('alt', 'required|max:128|min:1', function($input){
                return   $input->type == 'banner_text';
                });
        $v->sometimes('size_id', 'required|min:1', function($input){
                return   $input->type != 'banner_text';
                });
        $v->sometimes('src', 'required|max:255', function($input){
                return   $input->type != 'banner_text';
                });
        //
        if (!$v->fails()) {
            $type  = sprintf('%s_%s', $request->input('group'), $request->input('device'));
            $device = [];
            $deviceMap = ['iphone'=>2, 'ipad'=>4, 'android'=>3, 'text'=>0];
            $device[] = $deviceMap[$request->input('device')];
            $media = $request->input('media');
            if ($request->input('platform')>0) {
                $media = $request->input('platform');
            }
            if ($id) {
                $idea   =  Idea ::find($id) ; 
                $status =  $idea->status;
                if ($idea->alt != $request->input('alt') ||$idea->src != $request->input('src') || $idea->link != $request->input('link')){
                    $status = 1;
                }
                return  response()->json(['success' => $idea->update(['status'=>$status, 'media'=>$media, 'device'=>$device,'type'=>$type]+$request->all()),
                        'message'=> '', 'id'=>$id]
                        );
            }else{
                $idea = Idea :: create(array('user_id'=>$user_id, 'device'=>$device,'media'=>$media, 'type'=>$type) + $request->all());
                return response()->json(['success' => TRUE, 'id'=>$idea->id,
                        'message'=>'']
                        );
            }
        }
        $this->throwValidationException($request, $v);
    }
    public function preview(IdeaRequest $request, $id)
    {
        $idea = Idea:: findOrFail($id);
        return  ['type'=>$idea->type, 'value'=> $idea->type =='banner_text'?  $idea->alt: $idea->src];
    }

    /**
     * upload 
     * 
     * @param Request $request 
     * @access public
     * @return mixed
     */
    public function upload(Request $request) {
        $imagePath = sprintf('/images/%d/', Auth::user()->id()/1000);
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
        $ext =  substr($imgUrl, strrpos($imgUrl, '.') + 1);
        if (!in_array(strtolower($ext), array('gif'))) {
            $imagick = new \Imagick(public_path(). $imgUrl);
            $imagick->cropImage($cropW, $cropH, $imgX1, $imgY1);
            $cropFileName  = str_replace('/images/', '/resizeImages/', public_path() . $imgUrl);
            $cropPath = substr($cropFileName, 0, strrpos($cropFileName, '/'));
            file_exists($cropPath) ?: mkdir($cropPath, 0755, TRUE);
            file_put_contents($cropFileName, $imagick->getImageBlob());
            return ['status'=>'success', 'url'=> str_replace('/images/', '/resizeImages/', $imgUrl)];
        }else{
            return ['status'=>'success', 'url'=>  $imgUrl];
        }
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
        $locations      =  Cache:: rememberForever('locations', function(){ return  Location :: all();});
        $groups       =  Cache:: rememberForever('groups', function(){  return Group :: all();});
        $industries   =  Cache:: rememberForever('industries', function(){ return Industry :: all();});
        $clickActions =  Cache:: rememberForever('ClickActions', function(){ return ClickAction :: all();});
        $categories   =  Cache:: rememberForever('categories', function(){ return Category :: all();});
        $devices      =  Cache:: rememberForever('devices', function(){ return  Device :: all();});
        $networks     =  Cache:: rememberForever('networks', function(){ return Network :: all();});
        $oss          =  Cache:: rememberForever('oss', function(){ return  Os :: all();});
        $ages         =  Cache:: rememberForever('ages', function(){ return  Age :: all();});
        $operators    =  Cache:: rememberForever('operators', function(){ return  Operator :: all();});
        $regions      =  Cache:: rememberForever('regions', function(){ return  Region :: all()->sortBy('parent');});
        $types        =  Cache:: rememberForever('types', function(){ return  Type :: all();});
        $classification =  Cache:: rememberForever('classification', function(){ return  Classification:: all();});

        return view('idea.create', [
                'idea'          => $idea, 
                'groups'        => $groups, 
                'locations'        => $locations, 
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
          //      'plans'          => $plans,
                'types'          => $types,
                'payTypes' => PayType::$names,
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
    public function destroy(IdeaRequest $request, $id)
    {
        //
        return [
            'success'=> Idea :: where('id', $id)->update($request->all()),
            'message' => $request->input('status') ==1 ?'广告创意停止成功': '广告创意投放成功'
        ];

    }
}
