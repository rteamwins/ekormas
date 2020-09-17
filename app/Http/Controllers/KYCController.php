<?php

namespace App\Http\Controllers;

use App\KYC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KYCController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $all_kycs = KYC::where('user_id', Auth()->id())->paginate(10);
    return view('kyc.list', ['kycs' => $all_kycs]);
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

    $this->validate($request, [
      'amount' => 'digits_between:250,49999',
      'source' => 'in:profit,bonus',
    ]);
    if (Auth()->user()->{$request->source . '_amount'} < $request->amount) {
      return back()->withErrors('amount', "Your {$request->source} is not enough for the amount, reduce the amount or select another souce for the KYC");
    } else {
      $new_kyc = new KYC();
      $new_kyc->amount = $request->amount;
      $new_kyc->source = $request->source;
      $new_kyc->created_by = $request->user()->id;
      $new_kyc->status = 'created';
      $new_kyc->save();
      return redirect()->route('list_all_kycs')->with('success', "Your {$request->amount} KYC has been created")->setStatusCode(201);
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\KYC  $kYC
   * @return \Illuminate\Http\Response
   */
  public function show(KYC $kYC)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\KYC  $kYC
   * @return \Illuminate\Http\Response
   */
  public function edit(KYC $kYC)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\KYC  $kYC
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, KYC $kYC)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\KYC  $kYC
   * @return \Illuminate\Http\Response
   */
  public function destroy(KYC $kYC)
  {
    //
  }
}
