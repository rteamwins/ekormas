<?php

namespace App\Http\Controllers;

use App\LocalPay;
use Illuminate\Http\Request;

class LocalPayController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $local_pays = LocalPay::whereUserId(Auth()->user()->id)->paginate(20);
    return view('withdraw.list_local_pay', ['local_pays' => $local_pays]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
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
   * @param  \App\LocalPay  $localPay
   * @return \Illuminate\Http\Response
   */
  public function show(LocalPay $localPay)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\LocalPay  $localPay
   * @return \Illuminate\Http\Response
   */
  public function edit(LocalPay $localPay)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\LocalPay  $localPay
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, LocalPay $localPay)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\LocalPay  $localPay
   * @return \Illuminate\Http\Response
   */
  public function destroy(LocalPay $localPay)
  {
    //
  }
}
