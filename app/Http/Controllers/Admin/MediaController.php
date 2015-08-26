<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;

use App\Http\Requests;

use App\Http\Controllers\Controller;
use App\Media;
use Auth;
use Cache;
use App\Device;
use App\Group;
use Validator;
class MediaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        //
        return view('admin.media.index');
    }
    function lists(Request $request) {
        $medias = Media :: with(['devices','classify_1','classify', 'classify_3'])->orderBy ('updated_at', 'DESC')
            ->paginate($request->input('size', 20))
            ->toArray();
        $medias['rows'] = $medias['data'];
        unset($medias['data']);
        return $medias;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        //
        return view('admin.media.create', [
        'media'          => new Media(),
        'classification' => Cache:: rememberForever('classification', function(){ return  Classification:: all();}),
        'devices' => Cache:: rememberForever('device', function(){ return  Device:: all();}),
        'groups' => Cache:: rememberForever('group', function(){ return  Group:: all();}),
        ]);
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
        $v       = Validator::make($request->all(), [
                'name'         => 'required|min:2|max:64|unique:medias,name,'.($id>0?$id: 'NULL') . ',id' .
                sprintf(',classify_id,%d,classify_id_1,%d,classify_id_3,%d',
                    $request->input('classify_id', 0), 
                    $request->input('classify_id_1', 0),
                    $request->input('classify_id_3', 0)
                    ),
                'classify_id'  => 'required|numeric|min:1',
                'classify_id_1'=> 'required|numeric',
                'classify_id_3'=> 'required|numeric',
                'device_media' =>  'required|array|min:1',
                ]);
        if (!$v->fails()) {
            $user_id = Auth :: admin()->get()->id;
            $devices_medias = [];
            foreach ($request->input('device_media')  as $device_media) {
                if (isset($device_media['group'])) {
                    $device_media['group'] =  implode(',', $device_media['group']);
                }else{
                    $device_media['group'] = '';
                }
                $devices_medias[] = $device_media; 
            }
            if ($id>0) {
                return ['success'=>Media :: find($id)->update(['device_media'=>$devices_medias]+ $request->all()),'id'=>$id];
            }else{
                $attributes = $request->all() ; 
                Media :: create( ['user_id'=> $user_id, 'device_media'=>$devices_medias] + $attributes);
                return ['success'=>TRUE ];
            }
        }
        $this->throwValidationException($request, $v);
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
    public function edit(Request $qequest, $id)
    {
        //
        return view('admin.media.create', [
        'media'=>  Media ::with('devices')->find($id),
        'classification' => Cache:: rememberForever('classification', function(){ return  Classification:: all();}),
        'devices' => Cache:: rememberForever('device', function(){ return  Device:: all();}),
        'groups' => Cache:: rememberForever('group', function(){ return  Group:: all();}),
        ]);
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
