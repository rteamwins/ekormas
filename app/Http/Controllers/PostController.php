<?php

namespace App\Http\Controllers;

use App\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class PostController extends Controller
{
  public function __construct()
  {
    $this->middleware('auth');
  }
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $admin_post =
      Post::with('user:id,username,name')
      ->select('id', 'user_id', 'title', 'image', 'message', 'updated_at', 'created_at')
      ->orderBy('created_at', 'desc')->paginate(12);
    return view('post.index', ['posts' => $admin_post]);
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    return view('post.create');
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
      'title' => 'required|string|min:10',
      'content' => 'required|string|min:15',
      'image' => 'image|mimes:jpg,png,jpeg|max:2048'
    ]);
    try {
      $new_post = new Post();
      $new_post->title = $request->input('title');
      $new_post->message = $request->input('content');
      $new_post->user_id = Auth::User()->id;
      $new_post->save();
      if ($request->hasFile('image')) {
        $post_image = $request->file('image');
        $destination_path = public_path("images/post");
        $image_name = $new_post->id . "." . $post_image->getClientOriginalExtension();
        $post_image->move($destination_path, $image_name);
        $new_post->image = $image_name;
        $new_post->update();
      }
    } catch (\Exception $e) {
      return back()->with('error', sprintf('Could not create new post: %s', $e->getMessage()));
    }
    return redirect()->route('list_post')->with('success', 'New post created');
  }

  /**
   * Display the specified resource.
   *
   * @param  uuid  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
    try {
      $showable_post = Post::with('user:id,username,name')
        ->select('id', 'user_id', 'title', 'image', 'message', 'updated_at', 'created_at')
        ->where('id', $id)->firstOrFail();
      return view('post.show', ['post' => $showable_post]);
    } catch (\Exception $e) {
      return redirect()->route('admin_dashboard')->with('error', sprintf('could not show post: %s', $e->getMessage()));
    }
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Post $id
   * @return \Illuminate\Http\Response
   */
  public function edit($id)
  {
    try {
      $editable_post = Post::with('user:id,username,name')
        ->select('id', 'user_id', 'title', 'image', 'messsage', 'updated_at', 'created_at')
        ->where('id', $id)->firstOrFail();
      return view('post.edit', ['post' => $editable_post]);
    } catch (\Exception $e) {
      return back()->with('error', sprintf('could not edit post: %s', $e->getMessage()));
    }
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\post  $post
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id)
  {
    request()->validate([
      'title' => 'required|string|min:10',
      'content' => 'required|string|min:15',
      'image' => 'image|mimes:jpg,png,jpeg|max:2048'
    ]);

    try {
      $editable_post = Post::where('id', $id)->firstOrFail();
      $editable_post->title = $request->input('title');
      $editable_post->message = $request->input('content');
      if ($request->hasFile('image')) {
        $post_image = $request->file('image');
        Log::info(public_path('images/post/'));
        $destination_path = public_path("images/post");
        $image_name = $editable_post->id . "." . $post_image->getClientOriginalExtension();
        $post_image->move($destination_path, $image_name);
        $editable_post->image = $image_name;
      }
      $editable_post->update();
    } catch (\Exception $e) {
      return back()->with('error', sprintf('Could not update post: %s', $e->getMessage()));
    }
    return redirect()->route('list_post')->with('success', 'post updated');
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  uuid  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id)
  {
    try {
      $disposable_post = Post::where('id', $id)->firstOrFail();
      // if ($disposable_post->image != null) {
      //   if (file_exists(public_path('images/post/' . $disposable_post->image))) {
      //     unlink(public_path('images/post/' . $disposable_post->image));
      //   }
      //   $disposable_post->image = null;
      // }
      $disposable_post->delete();
    } catch (\Exception $e) {
      return back()->with('error', sprintf('Could not delete post: %s', $e->getMessage()));
    }
    return redirect()->route('list_post')->with('success', 'Post deleted');
  }
  /**
   * Remove the specified resource from storage.
   *
   * @param  uuid  $id
   * @return \Illuminate\Http\Response
   */
  public function delete_post_image($id)
  {
    try {
      $disposable_post_image = Post::where('id', $id)->firstOrFail();
      Log::info(public_path('images/post/'));
      if ($disposable_post_image->image != null) {
        if (file_exists(public_path('image/post/' . $disposable_post_image->image))) {
          unlink(public_path('image/post/' . $disposable_post_image->image));
        } else {
          return back()->with('error', 'Could not delete post image: file does not exist!',);
        }
        $disposable_post_image->image = null;
      }
      $disposable_post_image->update();
    } catch (\Exception $e) {
      return back()->with('error', sprintf('Could not delete post Image: %s', $e->getMessage()));
    }
    return redirect()->route('list_post')->with('success', 'Post IMage deleted');
  }
}
