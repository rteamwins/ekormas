<?php

namespace App\Http\Controllers;

use App\Category;
use App\Lga;
use App\Product;
use App\State;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Str;

class ProductController extends Controller
{

  function index()
  {
    return view('product.list');
  }

  function store_index()
  {
    $states = State::select('id', 'name')->whereCountryCode('cm')->get();
    $lgas = Lga::select('id', 'name', 'state_id')->whereCountryCode('cm')->get();
    return view('store', [
      'states' => $states,
      'lgas' => $lgas,
    ]);
  }

  function index_json()
  {
    $products = Product::paginate(10);
    return response()->json($products, Response::HTTP_OK);
  }

  function home_index_json()
  {
    $products = Product::latest()->limit(4)->get();
    return response()->json($products, Response::HTTP_OK);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    $categories = Category::select('id', 'name')->get();
    return view('product.create', ['categories' => $categories]);
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
      'category' => 'required|integer|exists:categories,id',
      'amount' => 'digits_between:1,10000000',
      'reward_level' => 'required|integer|digits_between:1,20',
      'delivery_duration' => 'required|integer|digits_between:1,99',
      'product_images' => 'required|array|min:1',
      'product_images.*' => 'required|image|mimes:png,jpg,jpeg',
      'description' => 'required|string',
    ]);
    $product_data = [
      'title' => $request->title,
      'amount' => $request->amount,
      'category_id' => $request->category,
      'images' => '[]',
      'reward_level' => $request->reward_level,
      'delivery_duration' => $request->delivery_duration,
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
      return redirect()->route('list_product')->with('success', 'Product Created successfully!');
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
    return response()->json($response, Response::HTTP_OK);
  }

}



