<?php

namespace App\Http\Controllers;

use App\Alert;
use App\Bonus;
use App\CryptoTransaction;
use App\Lga;
use App\MembershipPlan;
use App\Order;
use App\Post;
use App\Product;
use App\Profit;
use App\RegistrationCredit;
use App\State;
use App\Transaction;
use App\User;
use CoinbaseCommerce\ApiClient;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Shakurov\Coinbase\Facades\Coinbase;

//Make sure you don't store your API Key in your source code!
$apiClientObj = ApiClient::init(env('COINBASE_API_KEY'));
$apiClientObj->setTimeout(5);
class HomeController extends Controller
{
  public  $today_earning = 0;
  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
  }

  public function view_profile()
  {
    $states = State::select('id', 'name')->whereCountryCode('cm')->get();
    $lgas = Lga::select('id', 'name', 'state_id')->whereCountryCode('cm')->get();
    return view('user.profile', [
      'states' => $states,
      'lgas' => $lgas,
    ]);
  }
  public function update_profile(Request $request)
  {
    $this->validate($request, [
      'state_id' => 'sometimes|nullable|exists:states,id',
      'lga_id' => 'sometimes|nullable|exists:lgas,id',
      'name' => 'sometimes|nullable|string|',
      'phone' => 'sometimes|nullable|unique:users,phone,' . auth()->user()->id,
      'email' => 'sometimes|nullable|unique:users,email,' . auth()->user()->id,
    ]);

    $user = User::where('id', auth()->user()->id)->firstOrFail();
    $attribs = [
      'state_id',
      'lga_id',
      'name',
      'phone',
      'email',
    ];

    foreach ($attribs as $attrib) {
      if ($request->has($attrib) && $request->{$attrib} != (null || '')) {
        $user->{$attrib} = $request->{$attrib};
      }
    }


    $user->update();

    $userx['id'] = $user->id;
    $userx['name'] = $user->name;
    $userx['state_id'] = $user->state_id;
    $userx['lga_id'] = $user->lga_id;
    $userx['phone'] = $user->phone;
    $userx['username'] = $user->username;
    $userx['email'] = $user->email;
    $response['status'] = 'success';
    $response['message'] = 'Profile has been updated';
    $response['details'] = $userx;
    return response()->json($response, Response::HTTP_OK);
  }

  public function get_profile()
  {
    $user['id'] = auth()->user()->id;
    $user['name'] = auth()->user()->name;
    $user['state_id'] = auth()->user()->state_id;
    $user['lga_id'] = auth()->user()->lga_id;
    $user['phone'] = auth()->user()->phone;
    $user['username'] = auth()->user()->username;
    $user['email'] = auth()->user()->email;
    $response['status'] = 'success';
    $response['message'] = 'Profile Details';
    $response['details'] = $user;
    return response()->json($response, Response::HTTP_OK);
  }

  public function get_ref_level($id = null)
  {
    if ($id === null) {
      $id = Auth()->user()->id;
    }
    $node = User::select('id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'dormant_points', 'phone')->where('id', $id)->firstOrFail();
    $nodes = User::descendantsOf($id, ['id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'dormant_points', 'phone']);
    $node->children = $nodes->toTree();
    return response()->json($node, Response::HTTP_OK);
  }

  public function recur($data)
  {
    $this->today_earning;
  }
  public function show_referal()
  {
    return view('referal.listing');
  }

  public function active_users()
  {
    $users = User::select('referer', 'name', 'id', 'username', 'email', 'phone', 'created_at', 'membership_plan_id')
      ->with(['membership_plan:id,name'])
      ->withCount('downlines')
      ->where('role', 'user')
      ->whereNotNull('membership_plan_id')
      ->paginate(20);
    return view('user.list_active_user', [
      'users' => $users,
    ]);
  }

  public function non_active_users()
  {
    $users = User::select('referer', 'name', 'id', 'username', 'email', 'phone', 'created_at')
      ->where('role', 'user')
      ->whereNull('membership_plan_id')
      ->without('membership_plan')
      ->paginate(20);
    return view('user.list_non_active_user', [
      'users' => $users,
    ]);
  }


  public function active_user_count(): int
  {
    return User::select('referer', 'name', 'id', 'username', 'email', 'phone',)
      ->where('role', 'user')
      ->whereNotNull('membership_plan_id')
      ->without('membership_plan')
      ->count();
  }

  public function non_active_user_count(): int
  {
    return User::select('referer', 'name', 'id', 'username', 'email', 'phone',)
      ->where('role', 'user')
      ->whereNull('membership_plan_id')
      ->without('membership_plan')
      ->count();
  }


  public function potential_agents()
  {
    $users = User::select('referer', 'name', 'id', 'username', 'email', 'phone', 'created_at')
      ->where('role', 'user')
      ->withCount('downlines')
      ->without('membership_plan')
      ->orderBy('downlines_count', 'desc')
      // ->having('downlines_count', '>', 49)
      ->paginate(20);
    return view('user.list_potential_agent', [
      'agents' => $users,
    ]);
  }

  public function avail_agents()
  {
    $users = User::select('referer', 'name', 'id', 'username', 'email', 'phone', 'created_at')
      ->where('role', 'agent')
      ->withCount('downlines')
      ->without('membership_plan')
      ->orderBy('downlines_count', 'desc')
      // ->having('downlines_count', '>', 49)
      ->paginate(20);

    return view('user.list_avail_agent', [
      'agents' => $users,
    ]);
  }

  public function potential_agents_count(): int
  {
    return User::select('referer', 'name', 'id', 'username', 'email', 'phone')
      ->where('role', 'user')
      ->withCount('downlines')
      ->without('membership_plan')
      ->orderBy('downlines_count', 'desc')
      // ->having('downlines_count', '>', 49)
      ->count();
  }

  public function avail_agents_count(): int
  {
    return User::select('referer', 'name', 'id', 'username', 'email', 'phone')
      ->where('role', 'agent')
      ->withCount('downlines')
      ->without('membership_plan')
      ->orderBy('downlines_count', 'desc')
      // ->having('downlines_count', '>', 49)
      ->count();
  }
  /**
   * Show the application dashboard.
   *
   * @return \Illuminate\Contracts\Support\Renderable
   */
  public function user_dashboard()
  {
    $profits = Profit::whereUserId(Auth()->user()->id)->whereDate('created_at', now())->latest()->get();
    // $candle_sticks = $this->market_candlestick_bar();
    $plabels = [];
    $pdata = [];
    $series = [];
    foreach ($profits as $profit) {
      $plabels[] = $profit->created_at->format('h:i:s A');
      $pdata[] = number_format($profit->amount, 3);
    }

    // foreach ($candle_sticks as $candle_stick) {
    //   $series[] = [
    //     "x" => $candle_stick['date'] * 1000,
    //     "y" => [
    //       $candle_stick['open'],
    //       $candle_stick['high'],
    //       $candle_stick['low'],
    //       $candle_stick['close']
    //     ]
    //   ];
    // }
    // $mdata = [

    //   "series" => [[
    //     "data" => $series,
    //     // "backgroundColor" => '#00C853',
    //   ]]
    // ];
    $pdata = [
      "series" => [[
        "name" => 'Trade Profits',
        "data" => $pdata,
        "backgroundColor" => '#00C853',
      ]],
      "labels" => $plabels,
    ];
    return view('user.dashboard', [
      'pdata' => $pdata,
      // 'mdata' => $mdata,
      'role' => auth()->user()->role,
      'active_post' => Post::count(),
      'deleted_post' => Post::withTrashed()->whereNotNull('deleted_at')->count(),
      'active_alert' => Alert::whereStatus('active')->count(),
      'disabled_alert' => Alert::whereStatus('pending')->count(),
      'active_product' => Product::whereStatus('active')->count(),
      'disabled_product' => Product::whereStatus('pending')->count(),
      'avail_agent_count' => $this->avail_agents_count(),
      'potential_agent_count' => $this->potential_agents_count(),
      'active_user_count' => $this->active_user_count(),
      'non_active_user_count' => $this->non_active_user_count(),
      'open_order' => Order::whereIn('status', ['confirmed', 'shipped'])->count(),
      'closed_order' => Order::where('status', 'completed')->count(),
    ]);
  }

  public function market_candlestick_bar()
  {

    $date_ranges = [];
    $first_date = Profit::whereDate('created_at', now()->subDays())->oldest()->first()->created_at;
    $last_date = Profit::whereDate('created_at', now())->latest()->first()->created_at;
    while ($last_date->greaterThan($first_date)) {
      $date_ranges[] = $first_date->addHours(3)->getTimestamp();
    }

    $OHLCs = [];
    $last_close = null;
    array_pop($date_ranges);
    foreach ($date_ranges as $date) {
      $open_time = now()->setTimestamp($date);
      $close_time = now()->setTimestamp($date)->addHours(3);
      $OHLC['date'] = $date ?? null;
      $profits = Profit::whereBetween('created_at', [$open_time, $close_time])->get();
      $OHLC['open'] = $last_close ?: number_format($profits->avg('amount'), 4);
      $OHLC['high'] = number_format($profits->max('amount'), 4) ?? null;
      $OHLC['low'] = number_format($profits->min('amount'), 4) ?? null;
      $OHLC['close'] = number_format($profits->last()->amount ?? 00, 4) ?? null;
      // $OHLC['volume'] = $profits->avg('volume') ?? null;
      $OHLCs[] = $OHLC;
      $last_close = $OHLC['close'];
    }
    return $OHLCs;
  }

  public function index()
  {
    $states = State::select('id', 'name')->whereCountryCode('cm')->get();
    $lgas = Lga::select('id', 'name', 'state_id')->whereCountryCode('cm')->get();
    return view('welcome', [
      'states' => $states,
      'lgas' => $lgas,
    ]);
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
  public function update_reg_plan()
  {
    return view('membership.upgrade_plan');
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
      'plan' => 'required_without:rc_code|in:oynx,pearl,ruby,gold,sapphire,emerald,diamond',
    ]);

    $plan = ['oynx' => 70, 'pearl' => 130, 'ruby' => 310, 'gold' => 610, 'sapphire' => 1210, 'emerald' => 3610, 'diamond' => 6010];
    if (Auth()->user()->membership_plan_id == null) {
      if ($request->has('rc_code')) {
        $rc_code = $request->rc_code;
        $new_rc_trx = RegistrationCredit::where('code', $rc_code)->first();
        $new_rc_trx->status = 'used';
        $new_rc_trx->used_by = Auth()->user()->id;
        $new_rc_trx->save();

        $membership_plan = MembershipPlan::whereName($new_rc_trx->plan);
        $referer = User::find($new_rc_trx->user_id);

        $new_trx = new Transaction();
        $new_trx->amount =  3;
        $new_trx->status = 'created';
        $new_trx->type = 'bonus';
        $new_trx->user_id = $referer->id;

        Auth()->user()->membership_plan_id = $membership_plan->id;
        Auth()->user()->referer = $new_rc_trx->user_id;
        Auth()->user()->activated_at = now();
        Auth()->user()->update();
        $new_trx->status = 'completed';
        $new_trx->update();

        $new_bonus_trx = new Bonus();
        $new_bonus_trx->user_id = $referer->id;
        $new_bonus_trx->amount = 3;
        $new_bonus_trx->status = 'created';
        $new_bonus_trx->type = 'registration_fee_split';
        $new_bonus_trx->save();
        $new_bonus_trx->transaction()->save($new_trx);
        $new_trx->status = 'completed';
        $new_trx->update();
        $referer->bonus += $new_trx->amount;
        $referer->update();

        $admin = User::where('role', 'admin')->first();
        $new_admin_trx = new Transaction();
        $new_admin_trx->amount =  7;
        $new_admin_trx->status = 'created';
        $new_admin_trx->type = 'bonus';
        $new_admin_trx->user_id = $admin->id;

        $new_bonus_admin_trx = new Bonus();
        $new_bonus_admin_trx->user_id = $admin->id;
        $new_bonus_admin_trx->amount = 7;
        $new_bonus_admin_trx->status = 'created';
        $new_bonus_admin_trx->type = 'registration_fee_split';
        $new_bonus_admin_trx->save();
        $new_bonus_admin_trx->transaction()->save($new_admin_trx);
        $new_admin_trx->status = 'completed';
        $new_admin_trx->update();
        $admin->bonus += $new_admin_trx->amount;
        $admin->update();

        $user = User::where('id', Auth()->user()->id)->first();
        $user->give_referal_bonus();
        if ($user->parent->children->count() == 2) {
          $user->check_for_bonus_eligible_ancestors($user);
        }
        return redirect()->route('home');
      } else {

        $new_crypto_trx = new CryptoTransaction();
        $new_crypto_trx->currency = 'BTC';
        $new_crypto_trx->status = 'created';
        $new_crypto_trx->save();

        $new_trx = new Transaction();
        $new_trx->amount = $plan[$request->plan];
        $new_trx->status = 'created';
        $new_trx->type = 'user_registration_fee';
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
            "type" => "user_registration_fee",
            "trnx_id" => $new_trx->id,
            "membership_plan" => $request->plan
          ],
          'redirect_url' => route('reg_plan_payment_success', ['plan' => $request->plan]),
          'cancel_url' => route('reg_plan_payment_failed', ['plan' => $request->plan]),
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


  public function check_for_bonus_eligible_ancestors(User $user)
  {
    $ancestors = User::defaultOrder()->with(['membership_plan:id,fee,name'])
      ->ancestorsOf($user->id, ['id', '_rgt', '_lft', 'parent_id', 'placement_id', 'username', 'name', 'total_points', 'phone', 'membership_plan_id', 'created_at', 'activated_at']);
    foreach ($ancestors as $ancestor) {
      $ancestor_directline_count = $ancestor->children->count();
      $leg_count[$ancestor->username]['name'] = $ancestor->name;
      if ($ancestor_directline_count > 0) {
        if ($ancestor_directline_count == 2) {
          $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = $ancestor->children->last()->membership_plan->fee ?? 0;
        } else {
          $leg_count[$ancestor->username]['left'] = 1;
          $leg_count[$ancestor->username]['right'] = 0;
          $leg_count[$ancestor->username]['left_amount'] = $ancestor->children->first()->membership_plan->fee ?? 0;
          $leg_count[$ancestor->username]['right_amount'] = 0;
        }
      } else {
        $leg_count[$ancestor->username]['left'] = $leg_count[$ancestor->username]['right'] = 1;
        $leg_count[$ancestor->username]['left_amount'] = $leg_count[$ancestor->username]['right_amount'] = 0;
      }
      $left_desc = User::descendantsOf($ancestor->children->first());
      $right_desc = User::descendantsOf($ancestor->children->last());

      $leg_count[$ancestor->username]['left'] += $left_desc->count();
      $leg_count[$ancestor->username]['right'] += $right_desc->count();

      $leg_count[$ancestor->username]['left_amount'] += $left_desc->sum('membership_plan.fee');
      $leg_count[$ancestor->username]['right_amount'] += $right_desc->sum('membership_plan.fee');
      if ($leg_count[$ancestor->username]['left'] == $leg_count[$ancestor->username]['right']) {
        $ancestor->calculate_matching_bonus($leg_count[$ancestor->username]['left'] + $leg_count[$ancestor->username]['right']);
      }
    }
  }

  public function about_us()
  {
    return view('about');
  }

  public function faq()
  {
    return view('faq');
  }

  public function tac()
  {
    return view('tac');
  }

  public function disclaimer()
  {
    return view('disclaimer');
  }

  public function payment_failed($plan)
  {
    return view('payment_status.registration_plan_failed', ['plan' => $plan]);
  }

  public function payment_success($plan)
  {
    return view('payment_status.registration_plan_success', ['plan' => $plan]);
  }
}
