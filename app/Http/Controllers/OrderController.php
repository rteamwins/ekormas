<?php

namespace App\Http\Controllers;

use App\CryptoTransaction;
use App\Order;
use App\Transaction;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Shakurov\Coinbase\Facades\Coinbase;

class OrderController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('order.list');
  }

  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function admin_index()
  {
    return view('order.admin_list');
  }

  /* Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function admin_index_json()
  {
    $orders = Order::with(['user:id,name,username,email,phone'])->paginate(10);
    return response()->json($orders, Response::HTTP_OK);
  }

  /* Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index_json()
  {
    $orders = Order::whereUserId(Auth()->user()->id)->paginate(10);
    return response()->json($orders, Response::HTTP_OK);
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
      'state' => 'required|integer|exists:states,id',
      'lga' => 'required|integer|exists:lgas,id',
      'address' => 'required|string|',
      'cart_items' => 'required|array|min:1',
      'cart_items.*.id' => 'required|integer|exists:products,id',
      'cart_items.*.quantity' => 'required|integer|digits_between:1,10'
    ]);

    $new_order = Order::create([
      'type' => 'purchase',
      'status' => 'created',
      'traded' => false,
      'country_code' => 'CM',
      'state_id' => $request->state,
      'lga_id' => $request->lga,
      'address' => $request->address,
      'user_id' => Auth()->user()->id,

    ]);

    foreach ($request->cart_items as $item) {
      $new_order->ordered_products()->create(['product_id' => $item['id'], 'quantity' => $item['quantity']]);
    }

    $new_trx = new Transaction();
    $new_trx->amount = $new_order->total_amount;
    $new_trx->status = 'created';
    $new_trx->type = 'product_order';
    $new_trx->user_id = Auth()->user()->id;
    $new_trx->save();
    $new_crypto_trx = new CryptoTransaction();
    $new_crypto_trx->currency = 'BTC';
    $new_crypto_trx->status = 'created';
    $new_crypto_trx->save();

    $new_crypto_trx = CryptoTransaction::find($new_crypto_trx->id)->first();
    $new_crypto_trx->transaction()->save($new_trx);

    $new_charge = Coinbase::createCharge([
      'name' => Auth()->user()->username . " $" . $new_trx->amount . " New Order",
      'description' => Auth()->user()->username . " $" . $new_trx->amount . " New Product Order",
      'local_price' => [
        'amount' => $new_trx->amount,
        'currency' => 'USD'
      ],
      'pricing_type' => 'fixed_price',
      'metadata' => [
        "user_id" => Auth()->user()->id,
        "type" => "product_order",
        "trnx_id" => $new_trx->id,
        "order_code" => $new_order->code,
      ],
      'redirect_url' => route('order_payment_success', ['order_code' => $new_order->code]),
      'cancel_url' => route('order_payment_failed', ['order_code' => $new_order->code]),
    ]);

    $new_crypto_trx->charge_id = $new_charge['data']['hosted_url'];
    $new_crypto_trx->charge_id = $new_charge['data']['id'];
    $new_crypto_trx->charge_code = $new_charge['data']['code'];
    $new_crypto_trx->hosted_url = $new_charge['data']['hosted_url'];
    $new_crypto_trx->system_wallet_address = $new_charge['data']['addresses']['bitcoin'];
    $new_crypto_trx->update();
    $response['message'] = 'Order Received Redirecting to make payment';
    $response['order'] = $new_order;
    $response['payment_url'] = $new_crypto_trx->hosted_url;
    return response()->json($response, Response::HTTP_OK);
  }


  /**
   * ship the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function mark_as_shipped($id)
  {
    $order = Order::whereId($id)->firstOrFail();
    $order->status = 'shipped';
    $order->update();
    $response = 'Order Been Marked As Shipped';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * disable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function mark_as_completed($id)
  {
    $order = Order::whereId($id)->firstOrFail();
    $order->status = 'completed';
    $order->update();
    $response = 'Order Been Marked As Completed';
    return response()->json($response, Response::HTTP_OK);
  }

  public function payment_failed($order_code)
  {
    return view('payment_status.product_order_failed', ['order_code' => $order_code]);
  }

  public function payment_success($order_code)
  {
    return view('payment_status.product_order_success', ['order_code' => $order_code]);
  }
}
