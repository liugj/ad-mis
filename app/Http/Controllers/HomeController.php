<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\User;
use App\Basic;
use Auth;
class HomeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        //
        $basic = Basic ::find(Auth::user()->id());
        return view('home.index',['basic'=> $basic, 'user'=> Auth::user()->get()]);
    }
    public function chart(Request $request) {
        echo   bcrypt('admin%shoozen#com'); exit;
        return [
         [
            "day"   => "2014-09-05",
            "flow"  => 758,
            "click" => 349,
            "change"=> 235
         ],
         [
            "day"   => "2014-09-06",
            "flow"  => 758,
            "click" => 3490,
            "change"=> 235
         ]
       ];
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
