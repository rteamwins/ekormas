<?php

namespace App\Http\Controllers;

use App\Investment;
use App\InvestmentTransaction;
use App\KYC;
use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use CoinbaseCommerce\ApiClient;
use CoinbaseCommerce\Resources\Charge;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\URL;

class InvestmentController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $all_investment = Investment::paginate(10);
    return view('investment.list_all', ['investments' => $all_investment]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $products = Product::all();
    return view('investment.create', ['products' => $products]);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $starter_message = ['amount.between' => 'The amount must be between $250 and $999 for Stater Plan'];
    $medium_message = ['amount.between' => 'The amount must be between $1000 and $4999 for Medium Plan'];
    $expert_message = ['amount.between' => 'The amount must be between $5000 and $9999 for Expert Plan'];
    $expert_message = ['amount.between' => 'The amount must be between $10000 and $19999 for Entreprenuer Plan'];
    $expert_message = ['amount.between' => 'The amount must be between $20000 and $49999 for Investors Plan'];
    request()->validate([
      'payment_method' => 'required|in:kyc,bitcoin',
      'plan' => 'required_if:payment_method,bitcoin|in:starter,medium,expert,entreprenuer,investors',
      'kyc_code' => 'required_if:payment_method,kyc|exists:k_y_c_s,code',
      'product_inclusive' => 'required|boolean',
      'selected_products' => 'required_if:product_inclusive,true|array|between:1,3'
    ]);
    if ($request->input('plan') == 'starter') {
      request()->validate([
        'amount' => 'required|numeric|between:50,499',
        'user_wallet_address' => 'required|min:30',
      ], $starter_message);
    } elseif ($request->input('plan') == 'medium') {
      request()->validate([
        'amount' => 'required|numeric|between:500,1499',
        'user_wallet_address' => 'required|min:30',
      ], $medium_message);
    } elseif ($request->input('plan') == 'expert') {
      request()->validate([
        'amount' => 'required|numeric|between:1500,5000',
        'user_wallet_address' => 'required|min:30',
      ], $expert_message);
    }
    if ($request->payment_method == 'bitcoin') {
      try {
        $new_investment = new Investment();
        $new_investment->plan = $request->input('plan');
        $new_investment->amount = $request->input('amount');
        $new_investment->status = 'invalid';
        $new_investment->user_id = Auth::user()->id;
        $new_investment->save();
      } catch (\Exception $e) {
        return back()->with('error', sprintf('Could not create Investment: %s', $e->getMessage()));
      }
      try {
        $new_charge = new Charge();
        $new_charge->name = Auth::user()->username . " " . $request->input('amount');
        $new_charge->description = Auth::user()->username . " new {$new_investment->plan} Investment plan.";
        $new_charge->local_amount = [
          'amount' => $request->input('amount'),
          'currency' => 'USD'
        ];
        $new_charge->pricing_type = 'fixed_amount';
        $new_charge->metadata = [
          "user_id" => Auth::User()->id,
          "investment_id" => $new_investment->id,
          "type" => "credit",
        ];
        $new_charge->redirect_url = URL::to(sprintf('user/investment/charge_callback/charge_successfull/CA/%s', $request->input('amount')));
        $new_charge->cancel_url = URL::to('user/investment/charge_callback/charge_cancelled');
        $new_charge->save();
        $new_investment_transaction =  InvestmentTransaction::create(
          [
            'status' => 'created',
            'charge_id' => $new_charge->id,
            'charge_code' => $new_charge->code,
            'system_wallet_address' => $new_charge->addresses['bitcoin'],
          ]
        );
        $new_investment_transaction->save();
        $new_investment_transaction->investment()->save($new_investment);
        return Redirect::away($new_charge->hosted_url);
      } catch (\Exception $e) {
        return back()->with('error', sprintf('Investment Created But Could not Connect to the CryptoNetwork : %s', $e->getMessage()));
      }
    } else {
      if ($new_kyc_investment = KYC::where('code', $request->kyc_code)->where('status', 'created')->where('used_by', null)->first()) {
        try {
          if ($new_kyc_investment->amount >= 250 && $new_kyc_investment->amount >= 999) {
            $kyc_plan = 'starter';
          } elseif ($new_kyc_investment->amount >= 1000 && $new_kyc_investment->amount >= 4999) {
            $kyc_plan = 'medium';
          } elseif ($new_kyc_investment->amount >= 5000 && $new_kyc_investment->amount >= 9999) {
            $kyc_plan = 'expert';
          } elseif ($new_kyc_investment->amount >= 10000 && $new_kyc_investment->amount >= 19999) {
            $kyc_plan = 'entreprenuer';
          } elseif ($new_kyc_investment->amount >= 20000 && $new_kyc_investment->amount >= 49999) {
            $kyc_plan = 'investor';
          }

          $new_investment = new Investment();
          $new_investment->plan = $kyc_plan;
          $new_investment->amount = $new_kyc_investment->amount;
          $new_investment->status = 'invalid';
          $new_investment->user_id = Auth()->id;
          $new_investment->save();
          $new_kyc_investment->investment()->save($new_investment);
          $new_kyc_investment->used_by = Auth()->id;
          $new_kyc_investment->update();
          $new_investment->status = 'created';
          $new_investment->update();

          return redirect()->route('investment_list')->with('success', "Your {$kyc_plan} investment of {$new_kyc_investment->amount} has been created");
        } catch (\Exception $e) {
          return back()->with('error', sprintf('Could not create Investment: %s', $e->getMessage()));
        }
      }
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Investment  $investment
   * @return \Illuminate\Http\Response
   */
  public function show(Investment $investment)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Investment  $investment
   * @return \Illuminate\Http\Response
   */
  public function edit(Investment $investment)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Investment  $investment
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Investment $investment)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Investment  $investment
   * @return \Illuminate\Http\Response
   */
  public function destroy(Investment $investment)
  {
    //
  }
}
