<?php

namespace App\Http\Controllers;

use App\Alert;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class AlertController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('alert.list');
  }

  /* Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index_json()
  {
    $alerts = Alert::paginate(10);
    return response()->json($alerts, Response::HTTP_OK);
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
      'message' => 'required|string|min:3|max:250'
    ]);

    $new_alert = Alert::create([
      'message' => $request->message,
      'status' => 'active',
    ]);

    $response = 'Alert Created';
    return response()->json($response, Response::HTTP_OK);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'message' => 'required|string|min:3|max:250'
    ]);
    $alert = Alert::whereId($id)->firstOrFail();
    $alert->message = $request->message;
    $alert->update();
    $response = 'Alert Content Updated';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * enable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function enable($id)
  {
    $alert = Alert::whereId($id)->firstOrFail();
    $alert->status = 'active';
    $alert->update();
    $response = 'Alert Enabled';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * disable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function disable($id)
  {
    $alert = Alert::whereId($id)->firstOrFail();
    $alert->status = 'pending';
    $alert->update();
    $response = 'Alert Disabled';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $alert = Alert::whereId($id)->firstOrFail();
    $alert->delete();
    $response = 'Alert Deleted';
    return response()->json($response, Response::HTTP_OK);
  }
}
