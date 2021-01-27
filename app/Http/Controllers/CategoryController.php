<?php

namespace App\Http\Controllers;

use App\Alert;
use App\Category;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    return view('category.list');
  }

  /* Display a listing of the resource.
  *
  * @return \Illuminate\Http\Response
  */
  public function index_json()
  {
    $categories = Category::paginate(10);
    return response()->json($categories, Response::HTTP_OK);
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
      'name' => 'required|string|min:3|max:250'
    ]);

    Category::updateOrCreate([
      'name' => $request->name,
    ]);

    $response = 'New Category Created';
    return response()->json($response, Response::HTTP_OK);
  }


  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    $this->validate($request, [
      'name' => 'required|string|min:3|max:250'
    ]);

    Category::updateOrCreate(
      ['id' => $id],
      [
        'name' => $request->name,
      ]
    );

    $response = 'Category Name Updated';
    return response()->json($response, Response::HTTP_OK);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  Int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    $cat = Category::whereId($id)->firstOrFail();
    $cat->delete();
    $response = 'Category Deleted';
    return response()->json($response, Response::HTTP_OK);
  }
}
