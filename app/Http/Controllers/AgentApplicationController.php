<?php

namespace App\Http\Controllers;

use App\AgentApplication;
use App\CryptoTransaction;
use App\Lga;
use App\State;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;
use Shakurov\Coinbase\Facades\Coinbase;

class AgentApplicationController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('user.list_application');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index_json()
  {
    $applications = AgentApplication::with(['user:id,name,username,phone,email,wallet', 'state', 'country', 'lga'])->paginate(10);
    return response()->json($applications, Response::HTTP_OK);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    if (auth()->user()->downlines->count() >= 100 && !AgentApplication::whereUserId(auth()->user()->id)->whereIn('status', ['pending', 'confirmed'])->exists()){

      $states = State::select('id', 'name')->whereCountryCode('cm')->get();
      $lgas = Lga::select('id', 'name', 'state_id')->whereCountryCode('cm')->get();
      return view('user.new_agent_application', [
        'states' => $states,
        'lgas' => $lgas,
        ]);
      }else{
        return back()->with('info','Sorry you cannot apply at this time, you already have a pending or finalized application.');
      }
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
      'government_issued_id' => 'required|image|mimes:png,jpg,jpeg|max:20000',
      'intending_state' => 'required|integer|exists:states,id',
      'intending_lga' => 'required|integer|exists:lgas,id',
      'address' => 'required|string|min:5|max:240',
    ]);

    if ($request->hasFile('government_issued_id')) {
      $post_image = $request->file('government_issued_id');
      $destination_path = public_path("images/agent_application");
      $image_name = strtoupper(Str::random(10)) . "." . $post_image->getClientOriginalExtension();
      $post_image->move($destination_path, $image_name);
    }

    $application = new AgentApplication();
    $application->state_id = $request->intending_state;
    $application->lga_id = $request->intending_lga;
    $application->address = $request->address;
    $application->id_card = $image_name;
    $application->user_id = Auth()->user()->id;
    $application->status = 'created';
    $application->save();


    $new_trx = new Transaction();
    $new_trx->amount = 10000;
    $new_trx->status = 'created';
    $new_trx->type = 'agent_application_fee';
    $new_trx->user_id = Auth()->user()->id;
    $new_trx->save();

    $new_crypto_trx = new CryptoTransaction();
    $new_crypto_trx->currency = 'BTC';
    $new_crypto_trx->status = 'created';
    $new_crypto_trx->save();

    $new_crypto_trx = CryptoTransaction::where('id',$new_crypto_trx->id)->first();
    $new_crypto_trx->transaction()->save($new_trx);

    $new_charge = Coinbase::createCharge([
      'name' => Auth()->user()->username . " $" . $new_trx->amount . " Agent Application",
      'description' => Auth()->user()->username . " $" . $new_trx->amount . " Agent Application Fee",
      'local_price' => [
        'amount' => $new_trx->amount,
        'currency' => 'USD'
      ],
      'pricing_type' => 'fixed_price',
      'metadata' => [
        "user_id" => Auth()->user()->id,
        "type" => "agent_application_fee",
        "trnx_id" => $new_trx->id,
      ],
      'redirect_url' => route('user_list_purchase_registration_credits'),
      'cancel_url' => route('user_purchase_registration_credits'),
    ]);

    $new_crypto_trx->charge_id = $new_charge['data']['hosted_url'];
    $new_crypto_trx->charge_id = $new_charge['data']['id'];
    $new_crypto_trx->charge_code = $new_charge['data']['code'];
    $new_crypto_trx->hosted_url = $new_charge['data']['hosted_url'];
    $new_crypto_trx->system_wallet_address = $new_charge['data']['addresses']['bitcoin'];
    $new_crypto_trx->update();
    return redirect()->away($new_crypto_trx->hosted_url);
  }
}
