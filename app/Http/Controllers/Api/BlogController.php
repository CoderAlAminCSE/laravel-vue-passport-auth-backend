<?php

namespace App\Http\Controllers\Api;

use App\Models\Blog;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BlogResource;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $blog = Blog::orderBy('id', 'desc')->get();
        return send_response('Success', BlogResource::collection($blog));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "title" => "required",
                "description" => "required",
            ]
        );

        if ($validator->fails()) {
            return send_error('Validation error', $validator->errors(), 422);
        }

        try {
            $blog = Blog::create([
                'title' => $request->title,
                'description' => $request->description,
            ]);
            return send_response('Blog created successfully', new BlogResource($blog));
        } catch (\Throwable $th) {
            return send_error($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $blog = Blog::find($id);

        if ($blog) {
            return send_response('Success', new BlogResource($blog));
        } else {
            return send_error('Data not found!');
        }
    }



    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Blog $blog)
    {
        $validator = Validator::make(
            $request->all(),
            [
                "title" => "required",
                "description" => "required",
            ]
        );

        if ($validator->fails()) {
            return send_error('Validation error', $validator->errors(), 422);
        }

        try {

            $blog->title = $request->title;
            $blog->description = $request->description;
            $blog->save();

            return send_response('Blog updated successfully', new BlogResource($blog));
        } catch (\Throwable $th) {
            return send_error($th->getMessage(), $th->getCode());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $blog = Blog::find($id);
            if ($blog) {
                $blog->delete();
                return send_response('Blog delete success', []);
            } else {
                return send_error('Data not found!');
            }
        } catch (\Throwable $th) {
            return send_error('Something went wrong', $th->getCode());
        }
    }
}
