<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Collection;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PostCollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data=Collection::all();
        return Inertia::render('Collections/Index',['postcates'=>$data]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function getAll()
    {
        return Collection::all();
    }
    /**
     * Show the form for creating a new resource.
     */
    public function api_home()
    {
        return response()->json($this->getAll());
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:collections,title',
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $data = $request->all();
        $data['created_at']= now();
        $data['slug']= Str::slug($request->title);
        Collection::create($data);
        return response()->json(['check'=>true,'data'=>$this->getAll()]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Collection $collection)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Collection $collection)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|unique:collections,title',
        ]);
        if ($validator->fails()) {
            return response()->json(['check' => false, 'msg' => $validator->errors()->first()]);
        }
        $collection = Collection::find($id);
        if(!$collection){
            return response()->json(['check'=>true,'msg'=>'Không tìm thấy mã']);
        }
        $data=$request->all();
        if($request->has('title')){
            $data['slug']= Str::slug($request->title);
        }
        $data['updated_at']= now();
        Collection::where('id',$id)->update($data);
        return response()->json(['check'=>true,'data'=>$this->getAll()]);

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $collection = Collection::find($id);
        if(!$collection){
            return response()->json(['check'=>true,'msg'=>'Không tìm thấy mã']);
        }
        Collection::where('id',$id)->delete();
        return response()->json(['check'=>true,'data'=>$this->getAll()]);
    }
}
