<?php

namespace App\Http\Controllers;

use App\CryptoTransaction;
use App\RegistrationCredit;
use App\Transaction;
use App\User;
use CoinbaseCommerce\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Shakurov\Coinbase\Facades\Coinbase;

//Make sure you don't store your API Key in your source code!
$apiClientObj = ApiClient::init(env('COINBASE_API_KEY'));
$apiClientObj->setTimeout(5);

class HomeController extends Controller
{
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
  }

  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function user_dashboard()
  {
    $plabels = [];
    $pdata = [];
    foreach (range(0, 72) as $val) {
      $plabels[] = now()->subMinutes(rand(30, $val))->format('h:i:s A');
      $pdata[] = round(rand(1, 20) / 10000, 8);
    }
    $pdata = [
      "labels" => $plabels,
      "datasets" => [[
        "label" => 'Earned $',
        "data" => $pdata,
        "backgroundColor" => 'rgb(255,152,1)',
        "pointRadius" => 0,
        "borderWidth" => 1,
        "barPercentage" => 0.9,
        "lineTension" => 0.4,
        "categoryPercentage" => 1.0,
      ]]
    ];
    $options = [
      "responsive" => true,
      "maintainAspectRation" => false,
      "legend" => ["display" => false],
      "scales" => [
        "xAxes" => [["gridLines" => ["display" => false], "ticks" => ["display" => false]]],
        "yAxes" => [["gridLines" => ["display" => true], "ticks" => ["display" => false]]],
      ],
      "layout" => [
        "padding" => [
          "left" => -10,
          "right" => -4,
          "top" => 0,
          "bottom" => -10
        ]
      ],

    ];
    return view('user.dashboard', ['pdata' => $pdata, 'options' => $options]);
  }

  public function index()
  {
    return view('welcome');
  }

  public function get_agent_stat()
  {
   
  }

  /**
   * pay registraion fee.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function choose_reg_plan()
  {
    return view('membership.register_plan');
  }

  /**
   * pay registraion fee.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function process_reg_plan(Request $request)
  {
    $this->validate($request, [
      'rc_code' => 'required_without:plan|alpha_num|size:15|exists:registration_credits,code',
      'plan' => 'required_without:rc_code|in:pearl,ruby,gold,sapphire,emerald,diamond',
    ]);
    $plan = ['pearl' => 130, 'ruby' => 310, 'gold' => 610, 'sapphire' => 1210, 'emerald' => 3610, 'diamond' => 6010];
    if (Auth::user()->registration_credit_id == null) {
      if ($request->has('rc_code')) {
        $rc_code = $request->rc_code;
        $new_rc_trx = RegistrationCredit::where('code', $rc_code)->first();
        $new_rc_trx->status = 'used';
        $new_rc_trx->used_by = Auth()->user()->id;
        $new_rc_trx->save();

        $new_trx = new Transaction();
        $new_trx->amount = $new_rc_trx->amount;
        $new_trx->status = 'created';
        $new_trx->type = 'registration';
        $new_trx->user_id = Auth()->id();
        $new_trx->save();
        $new_rc_trx->transaction()->save($new_trx);

        Auth()->user()->registration_credit_id = $new_rc_trx->id;
        Auth()->user()->update();
        $new_trx->status = 'completed';
        $new_trx->update();
        return redirect()->route('home');
      } else {

        $new_crypto_trx = new CryptoTransaction();
        $new_crypto_trx->currency = 'BTC';
        $new_crypto_trx->status = 'created';
        $new_crypto_trx->save();

        $new_trx = new Transaction();
        $new_trx->amount = $plan[$request->plan];
        $new_trx->status = 'created';
        $new_trx->type = 'registration';
        $new_trx->user_id = Auth()->id();
        $new_trx->save();
        // $new_crypto_trx = CryptoTransaction::find($new_crypto_trx->id)->first();
        $new_crypto_trx->transaction()->save($new_trx);

        $new_charge = Coinbase::createCharge([
          'name' => Auth()->user()->username . " " . "\${$plan[$request->plan]} {$request->plan} Plan",
          'description' => Auth()->user()->username . " " . "\${$plan[$request->plan]} Registration Fee for {$request->plan} Plan",
          'local_price' => [
            'amount' => $plan[$request->plan],
            'currency' => 'USD'
          ],
          'pricing_type' => 'fixed_price',
          'metadata' => [
            "user_id" => Auth()->user()->id,
            "type" => "rc_fee",
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
      }
    }
  }
}
