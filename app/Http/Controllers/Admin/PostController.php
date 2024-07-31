<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\PostCate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PostController extends Controller
{
    public function store1(Request $request, $slug)
    {
        $validator = Validator::make($request->all(), [
                'title' => 'required|unique:posts,title',
                'summary'=>'required',
                'content'=>'required',
                'image'=>'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $id_collection= PostCate::where('slug',$slug)->value('id');
        $data = $request->all();
        $data['slug']=Str::slug($request->title);
        $data['id_collection']= $id_collection;
        $data['created_at']= now();
        Post::create($data);
        return response()->json(['check'=>true]);
    }
  /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $posts = Post::with('cates')->get();
        $cates = PostCate::active()->select('id','title')->get();
        return Inertia::render('Posts/Index', ['posts' => $posts,'cates'=>$cates]);
    }

    public function create()
    {
        $postCates = PostCate::active()->get();
        return Inertia::render('Posts/Create', ['postCates' => $postCates]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function getProductsList($id){
        $result =Links::where('id_parent',$id)->pluck('id_link');
        return response()->json($result);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:posts,title',
            'summary' => 'required',
            'content' => 'required',
            'id_collection' => 'required|exists:collections,id',
            'file' => 'required',
            'file.*' => 'mimes:jpeg,jpg,png,gif',
        ]);

        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }

        $data['title']=$request->title;
        $data['summary']=$request->summary;
        $data['content']=$request->content;
        $data['id_collection']=$request->id_collection;
        $data['slug'] = Str::slug($request->title);
        $file=$request->file('file');
        $imageName = $file->getClientOriginalName();
        $extractTo = storage_path('app/public/posts/');
        $data['image'] = '/storage/posts/' . $imageName;
        $file->move($extractTo, $imageName);
        $data['created_at']=now();
        $id=Post::insertGetId($data);
        $posts = Post::with('cates')->get();
        return response()->json(['check' => true, 'data' => $posts]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        return response()->json($post);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        $postCates = PostCate::all();
        return Inertia::render('Posts/Edit', ['post' => $post, 'postCates' => $postCates]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $data = $request->all();
        if ($request->has('title')) {
            $data['slug'] = Str::slug($request->title);
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $imageName = $file->getClientOriginalName();
            $extractTo = storage_path('app/public/posts/');
            $data['image'] = '/storage/posts/' . $imageName;
            $file->move($extractTo, $imageName);
            $oldImage = Post::where('id', $id)->value('image');
            $oldFilePath = "public/posts/{$oldImage}";
            Storage::delete($oldFilePath);
        }

        $data['updated_at'] = now();
        unset($data['products']);
        unset($data['file']);
        Post::where('id', $id)->update($data);
        $posts = Post::with('cates')->get();
        return response()->json(['check' => true, 'data' => $posts]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $oldImage = Post::where('id', $id)->value('image');
            Storage::delete($oldImage);
        Post::where('id', $id)->delete();
        $posts = Post::with('cates')->get();
        return response()->json(['check' => true, 'data' => $posts]);
    }

      // =========================================
      public function api_post()
      {
          $posts = Post::where('status', 1)->orderBy('created_at', 'desc')->get();
          $new_posts = Post::where('status', 1)->orderBy('id', 'desc')->take(4)->get();
          $postcates = PostCate::where('status', 1)->select('id', 'slug', 'title')->get();
          return response()->json(['posts' => $posts, 'new_posts' => $new_posts, 'postcates' => $postcates]);
      }
      // =========================================
      public function api_highlight()
      {
          $posts = Post::with('cates')->where('status', 1)->where('highlight', 1)->orderBy('created_at', 'asc')->get();
          return response()->json($posts);
      }

      // =========================================

      public function single_post($id)
      {
          $posts = Post::where('status', 1)->where('slug', $id)->first();
          $postcates = PostCate::where('status', 1)->select('id', 'slug', 'title')->get();
          $new_posts = Post::where('status', 1)->orderBy('id', 'desc')->take(4)->get();
          return response()->json(['post' => $posts, 'newposts' => $new_posts, 'postcates' => $postcates]);
      }
}
