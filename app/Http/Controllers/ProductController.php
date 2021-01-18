<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ProductController extends Controller
{

  function index()
  {
    return view('product.list');
  }

  function index_json()
  {
    $products = Product::paginate(10);
    return response()->json($products, Response::HTTP_OK);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('product.create');
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
      'title' => 'required|string',
      'amount' => 'digits_between:1,10000000',
      // 'reward_level' => 'required|integer|digits_between:1,20',
      'product_images' => 'required|array|min:1',
      'product_images.*' => 'required|image|mimes:png,jpg,jpeg',
      'description' => 'required|string',
    ]);
    $product_data = [
      'title' => $request->title,
      'amount' => $request->amount,
      'images' => '[]',
      'description' => $request->description,
      'status' => 'active'
    ];
    if ($request->hasFile('product_images') && count($product_images = $request->file('product_images'))) {
      $request->images = [];
      $new_product = Product::create($product_data);
      $images = [];
      foreach ($product_images as $image) {
        $image_ext = $image->getClientOriginalExtension();
        $image_name = sprintf("%s.%s", strtoupper(Str::random(10)), $image_ext);
        $image_path = public_path("images/product");
        $image->move($image_path, $image_name);
        $images[] = $image_name;
      }
      $new_product = Product::find($new_product->id);
      $new_product->images = $images;
      $new_product->update();
      return redirect()->route('list_product')->with('user-success', 'Product Created successfully!');
    }
  }

  /**
   * enable the specified resource in storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function enable($id)
  {
    $product = Product::whereId($id)->firstOrFail();
    $product->status = 'active';
    $product->update();
    $response = 'Product Enabled';
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
    $product = Product::whereId($id)->firstOrFail();
    $product->status = 'pending';
    $product->update();
    $response = 'Product Disabled';
  }
}
