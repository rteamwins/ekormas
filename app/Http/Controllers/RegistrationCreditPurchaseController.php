<?php

namespace App\Http\Controllers;

use App\CryptoTransaction;
use App\KYC;
use App\RegistrationCreditPurchase;
use App\Transaction;
use Illuminate\Http\Request;
use Shakurov\Coinbase\Facades\Coinbase;

class RegistrationCreditPurchaseController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $rc_purchases = RegistrationCreditPurchase::whereUserId(Auth()->user()->id)->paginate(20);
    return view('membership.purchase_list', [
      'rc_purchases' => $rc_purchases
    ]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('membership.buy_register_credits');
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
      'plan' => 'required|in:pearl,ruby,gold,sapphire,emerald,diamond',
      'plan_quantity' => 'required|integer|digits_between:1,100',
    ]);

    try {
      $rc_purchase = new RegistrationCreditPurchase();
      $rc_purchase->package = $request->plan;
      $rc_purchase->quantity = $request->plan_quantity;
      $rc_purchase->user_id = Auth()->user()->id;
      $rc_purchase->status = 'created';
      $rc_purchase->saveOrFail();

      $new_trx = new Transaction();
      $new_trx->amount = $rc_purchase->amount;
      $new_trx->status = 'created';
      $new_trx->type = 'registration_credit_purchase';
      $new_trx->user_id = Auth()->user()->id;
      $new_trx->save();
      $new_crypto_trx = new CryptoTransaction();
      $new_crypto_trx->currency = 'BTC';
      $new_crypto_trx->status = 'created';
      $new_crypto_trx->save();

      $new_crypto_trx = CryptoTransaction::find($new_crypto_trx->id)->first();
      $new_crypto_trx->transaction()->save($new_trx);

      $new_charge = Coinbase::createCharge([
        'name' => Auth()->user()->username . " $" . $new_trx->amount . " RC Purchase",
        'description' => Auth()->user()->username . " $" . $new_trx->amount . " Registration Credit Purchase",
        'local_price' => [
          'amount' => $new_trx->amount,
          'currency' => 'USD'
        ],
        'pricing_type' => 'fixed_price',
        'metadata' => [
          "user_id" => Auth()->user()->id,
          "type" => "registration_credit_purchase",
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
    } catch (\Exception $e) {
      return back()->with('error', sprintf('Could not Fund wallet: %s', $e->getMessage()));
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
   * @return \Illuminate\Http\Response
   */
  public function show(RegistrationCreditPurchase $registrationCreditPurchase)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
   * @return \Illuminate\Http\Response
   */
  public function edit(RegistrationCreditPurchase $registrationCreditPurchase)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, RegistrationCreditPurchase $registrationCreditPurchase)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\RegistrationCreditPurchase  $registrationCreditPurchase
   * @return \Illuminate\Http\Response
   */
  public function destroy(RegistrationCreditPurchase $registrationCreditPurchase)
  {
    //
  }
}
