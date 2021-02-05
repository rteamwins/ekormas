<?php

namespace App\Http\Controllers;

use App\RegistrationCredit;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class RegistrationCreditController extends Controller
{
  public function index()
  {
    return view('membership.list');
  }
  function index_json()
  {
    $rcs = RegistrationCredit::with(['consumer:id,name,username,phone,email'])->whereUserId(auth()->user()->id)->whereNull('used_by')->paginate(10);
    return response()->json($rcs, Response::HTTP_OK);
  }
}
