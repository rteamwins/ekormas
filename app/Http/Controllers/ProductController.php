<?php

namespace App\Http\Controllers;

use App\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    //
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //
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
      'reward_level' => 'required|in:0,1,2,3,4,5,6,7,8,9,10',
      'product_images.*' => 'required|image|mimes:png,jpg,jpeg',
      'avail_qty' => 'required|digits_between:0,100000',
      'sold_qty' => 'required|digits_between:0,100000',
      'description' => 'required|string',
    ]);
    $product_data = [
      'title' => $request->title,
      'amount' => $request->amount,
      'reward_level' => $request->reward_level,
      'images' => [],
      'avail_qty' => $request->avail_qty,
      'sold_qty' => $request->sold_qty,
      'description' => $request->description,
    ];
    if ($request->hasFile('product_images') && count($product_images = $request->file('product_images'))) {
      $request->images = [];
      $new_product = Product::create($product_data);
      $images = [];
      foreach ($product_images as $image) {
        $image_ext = $image->getClientOriginalExtension();
        $image_name = sprintf("%s.%s", Str::slug($request->input('title')), $image_ext);
        $image_path = public_path(sprintf("products/%s", $image_name));
        $image->move($image_path);
        $images[] = $image_name;
      }
      $new_product = Product::find($new_product->id);
      $new_product->images = $images;
      $new_product->update();
      return redirect()->route('product.view', ['product_slug' => $new_product->slug])->with('success', 'Product Created succefully!');
    }
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function show(Product $product)
  {
    //
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function edit(Product $product)
  {
    //
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Product $product)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Product  $product
   * @return \Illuminate\Http\Response
   */
  public function destroy(Product $product)
  {
    //
  }
}
