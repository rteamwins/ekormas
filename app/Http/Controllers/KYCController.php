<?php

namespace App\Http\Controllers;

use App\KYC;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
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
    $all_kycs = KYC::where('user_id', Auth()->user()->id)->paginate(10);
    return view('kyc.list', ['kycs' => $all_kycs]);
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index_json()
  {
    $kycs = KYC::where('user_id', auth()->user()->id)->paginate(10);
    return response()->json($kycs, Response::HTTP_OK);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $max = (Auth()->user()->wallet * ((100 - 5) / 100));

    $this->validate($request, [
      'amount' => 'required|numeric|min:100|max:' . $max,
    ]);

    $new_kyc = new KYC();
    $new_kyc->amount = $request->amount;
    $new_kyc->source = $request->source;
    $new_kyc->created_by = $request->user()->id;
    $new_kyc->status = 'created';
    $new_kyc->save();
    return redirect()->route('list_all_kycs')->with('user-success', "Your {$request->amount} KYC has been created")->setStatusCode(201);
  }


}
