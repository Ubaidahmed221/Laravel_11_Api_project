<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use App\Models\Post;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['posts'] = Post::all();
        return response()->json([
            'status' => true,
            'message' =>  'All POst Data',
            'data' =>  $data
        ],200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'required|mimes:png,jpg,jpeg,gif',
            ]
            );
            if($validatePost->fails()){
                return response()->json([
                    'status' => false,
                    'message' =>  'Validation Error',
                    'errors' => $validatePost->errors()->all()
                ],401);
            }

            $img = $request->image;
            $ext = $img->getClientOriginalExtension();
            $imageName = time() . '.' . $ext;
            $img->move(public_path() . '/uploads' ,  $imageName);

            $post = Post::create([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            return response()->json([
                'status' => true,
                'message' =>  'Post Created Successfully',
               'post' => $post
            ],200);

    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $data['post'] = Post::select('*')->where(['id' => $id])->get();
        return response()->json([
            'status' => true,
            'message' =>  'Your single Post',
           'data' => $data
        ],200);


    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $validatePost = Validator::make(
            $request->all(),
            [
                'title' => 'required',
                'description' => 'required',
                'image' => 'nullable|image|mimes:png,jpg,jpeg,gif',
            ]
            );
            if($validatePost->fails()){
                return response()->json([
                    'status' => false,
                    'message' =>  'Validation Error',
                    'errors' => $validatePost->errors()->all()
                ],401);
            }

            $postImg =  Post::select('id','image')->where(['id' => $id])->get();
            if($request->image != ''){
                $path = public_path() . '/uploads/';
                if($postImg[0]->image != '' && $postImg[0]->image != null){
                    $old_file = $path. $postImg[0]->image;
                    if(file_exists($old_file)){
                        unlink($old_file);
                    }

                }
                $img = $request->image;
                $ext = $img->getClientOriginalExtension();
                $imageName = time() . '.' . $ext;
                $img->move(public_path() . '/uploads' ,  $imageName);

            }else{
                $imageName = $postImg[0]->image;
            }



            $post = Post::where(['id' => $id])->update([
                'title' => $request->title,
                'description' => $request->description,
                'image' => $imageName,
            ]);

            return response()->json([
                'status' => true,
                'message' =>  'Post Updated Successfully',
               'post' => $post
            ],200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $imagePath = Post::select('image')->where('id',$id)->get();
        $filePath = public_path() . '/uploads/' . $imagePath[0]['image'];
        unlink($filePath);

        $post = Post::where('id',$id)->delete();

        return response()->json([
            'status' => true,
            'message' =>  'Post Delete Successfully',
           'post' => $post
        ],200);

    }
}
