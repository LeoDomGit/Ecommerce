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

    /**
     * Remove the specified resource from storage.
     */
    public function api_highlight(Post $post){
        $result=Post::with('cates')
        ->highlight()
        ->orderBy('id', 'desc')
        ->first();
        return response()->json($result);
    }

    public function api_get(Post $post){
        $result=Post::with('cates')->active()->orderBy('id','desc')->paginate(4);
        return response()->json($result);
    }
    public function api_single(Post $post,$id){
        $result=Post::with('cates')->active()->where('slug',$id)->first();
        $id_collection= $result->id_collection;
        $posts = Post::with('cates')->active()->where('id_collection',$id_collection)->get();
        $id_post=$result->id;
        $products = Products::join('links','links.id_link','=','products.id')
                ->join('gallery','products.id','=','gallery.id_parent')
                ->where('links.id_parent',$id_post)->where('products.status',1)
                ->where('gallery.status',1)
                ->select('products.*','gallery.image as image')->get();
        return response()->json(['post'=>$result,'products'=>$products ,'relative'=>$posts]);

    }
}
