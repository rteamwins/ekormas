<?php

namespace App\Http\Controllers;

use App\Referal;
use App\User;
use Illuminate\Http\Request;

class ReferalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Referal  $referal
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
      $parent_node = User::decendantsAndSelf(Auth()->user()->id);
        return view('referal.listing',['']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Referal  $referal
     * @return \Illuminate\Http\Response
     */
    public function destroy(Referal $referal)
    {
        //
    }
}
