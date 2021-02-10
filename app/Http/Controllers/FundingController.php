<?php

namespace App\Http\Controllers;

use App\Bonus;
use App\CryptoTransaction;
use App\KYC;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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
    $fundings = Transaction::whereUserId(auth()->user()->id)->where('type', 'wallet_funding')->latest()->paginate(10);
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
      'funding_kyc_code' => 'required_without:funding_amount|alpha_num|size:22|exists:k_y_c_s,code,used_by,null',
    ],['funding_kyc_code.exists'=> 'Token Code is invalid or has been used']);

    if ($request->has('funding_amount')) {
      try {
        $new_crypto_trx = new CryptoTransaction();
        $new_crypto_trx->currency = 'BTC';
        $new_crypto_trx->status = 'created';
        $new_crypto_trx->save();

        $new_trx = new Transaction();
        $new_trx->amount = $request->funding_amount;
        $new_trx->status = 'created';
        $new_trx->type = 'wallet_funding';
        $new_trx->user_id = Auth()->user()->id;
        $new_trx->save();

        $new_crypto_trx = CryptoTransaction::where('id',$new_crypto_trx->id)->first();
        $new_crypto_trx->transaction()->save($new_trx);


        $new_charge = Coinbase::createCharge([
          'name' => Auth()->user()->username . " $" . $request->funding_amount . " Funding",
          'description' => Auth()->user()->username . " $" . $request->funding_amount . " Wallet funding",
          'local_price' => [
            'amount' => $request->funding_amount,
            'currency' => 'USD'
          ],
          'pricing_type' => 'fixed_price',
          'metadata' => [
            "user_id" => Auth()->user()->id,
            "type" => "user_wallet_funding",
            "trnx_id" => $new_trx->id,
          ],
          'redirect_url' => route('user_fund_payment_success', ['amount' => $request->funding_amount]),
          'cancel_url' => route('user_fund_payment_failed', ['amount' => $request->funding_amount]),
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
        $new_trx->type = 'wallet_funding';
        $new_trx->user_id = Auth()->user()->id;

        $valid_kyc->used_by = Auth()->user()->id;
        $valid_kyc->status = 'used';
        $valid_kyc->update();
        $valid_kyc->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $user = User::where('id', Auth()->user()->id)->first();
        $user->wallet += $valid_kyc->amount;
        $user->update();

        if (Auth()->user()->type == 'agent') {
          $benefactor = $user;
        } else {
          $benefactor = User::where('role', "admin")->firstOrFail();
        }

        $new_trx = new Transaction();
        $new_trx->amount = ($valid_kyc->fee / 2);
        $new_trx->status = 'created';
        $new_trx->type = 'service_charge_bonus';
        $new_trx->user_id = $benefactor->id;

        $new_bonus_trx = new Bonus();
        $new_bonus_trx->user_id = $benefactor->id;
        $new_bonus_trx->amount = ($valid_kyc->fee / 2);
        $new_bonus_trx->status = 'created';
        $new_bonus_trx->type = 'service_charge_completed';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $benefactor->bonus += $new_trx->amount;
        $benefactor->update();

        return redirect()->route('user_home')->with('success', "Your wallet was successfully funded with {$valid_kyc->amount}");
      } catch (\Exception $e) {
        return back()->with('error', sprintf('Could not fund your wallet: %s', $e->getMessage()));
      }
    }
  }
  public function payment_failed($amount)
  {
    return view('payment_status.wallet_fund_failed', ['amount' => $amount]);
  }

  public function payment_success($amount)
  {
    return view('payment_status.wallet_fund_success', ['amount' => $amount]);
  }
}
