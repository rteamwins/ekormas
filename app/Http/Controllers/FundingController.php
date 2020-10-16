<?php

namespace App\Http\Controllers;

use App\CryptoTransaction;
use App\KYC;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Shakurov\Coinbase\Facades\Coinbase;

class FundingController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $fundings = Transaction::where('type', 'funding')->paginate(10);
    return view('funding.list', ['fundings' => $fundings]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('funding.create');
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    request()->validate([
      'funding_amount' => 'required_without:funding_kyc_code|numeric|min:100',
      'funding_kyc_code' => 'required_without:funding_amount|alpha_num|size:15|exists:k_y_c_s,code',
    ]);
    if ($request->has('funding_amount')) {
      try {
        $new_crypto_trx = new CryptoTransaction();
        $new_crypto_trx->currency = 'BTC';
        $new_crypto_trx->status = 'created';
        $new_crypto_trx->save();

        $new_trx = new Transaction();
        $new_trx->amount = $request->funding_amount;
        $new_trx->status = 'created';
        $new_trx->type = 'funding';
        $new_trx->user_id = Auth()->id();
        $new_trx->save();

        $new_crypto_trx = CryptoTransaction::find($new_crypto_trx->id)->first();
        $new_crypto_trx->transaction()->save($new_trx);


        $new_charge = Coinbase::createCharge([
          'name' => Auth()->user()->username . " $" . $request->funding_amount ." Funding",
          'description' => Auth()->user()->username . " $" . $request->funding_amount . " Wallet funding",
          'local_price' => [
            'amount' => $request->funding_amount,
            'currency' => 'USD'
          ],
          'pricing_type' => 'fixed_price',
          'metadata' => [
            "user_id" => Auth()->user()->id,
            "type" => "funding",
            "trnx_id" => $new_trx->id,
          ],
          'redirect_url' => route('home'),
          'cancel_url' => route('home'),
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
    } elseif ($request->has('funding_kyc_code')) {
      $valid_kyc = KYC::where('code', $request->funding_kyc_code)->first();
      try {
        $new_trx = new Transaction();
        $new_trx->amount = $valid_kyc->amount;
        $new_trx->status = 'created';
        $new_trx->type = 'funding';
        $new_trx->user_id = Auth()->id();

        $valid_kyc->used_by = Auth()->id();
        $valid_kyc->status = 'used';
        $valid_kyc->update();
        $valid_kyc->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $user = User::find(Auth()->id)->first();
        $user->wallet += $new_trx->amount;
        $user->update();


        return redirect()->route('home')->with('success', "Your wallet was successfully funded with {$valid_kyc->amount}");
      } catch (\Exception $e) {
        return back()->with('error', sprintf('Could not fund your wallet: %s', $e->getMessage()));
      }
    }
  }
}
