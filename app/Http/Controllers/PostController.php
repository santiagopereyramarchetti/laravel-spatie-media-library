<?php

namespace App\Http\Controllers;

use App\Http\Requests\PostCreateRequest;
use App\Models\Post;
use Illuminate\Http\Request;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\MediaLibrary\Support\MediaStream;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::all();
        return view('posts.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostCreateRequest $request)
    {
        $post = Post::create($request->validated());

        if($request->hasFile('image')){
            
            $post->addMediaFromRequest('image')
                ->withResponsiveImages()
                ->usingName($post->title)
                ->toMediaCollection('images');
        }

        if($request->hasFile('download')){
            
            $post->addMediaFromRequest('download')
                ->withResponsiveImages()
                ->usingName($post->title)
                ->toMediaCollection('downloads');
        }

        return to_route('posts.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostCreateRequest $request, Post $post)
    {
        $post->update($request->validated());

        if($request->hasFile('image')){
            // $post->clearMediaCollection('images');
            $post->addMediaFromRequest('image')
                ->withResponsiveImages()
                ->usingName($post->title)
                ->toMediaCollection('images');
        }

        if($request->hasFile('download')){
            $post->clearMediaCollection('downloads');
            $post->addMediaFromRequest('download')
                ->withResponsiveImages()
                ->usingName($post->title)
                ->toMediaCollection('downloads');
        }

        return to_route('posts.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Post $post)
    {
        $post->delete();
        return to_route('posts.index');
    }

    public function download(Post $post){

        $media = $post->getFirstMedia('downloads');

        return $media;

    }

    public function downloads(){

        $media = Media::where('collection_name', 'downloads')->get();
        // $media = Media::all();

        return MediaStream::create('downloads.zip')->addMedia($media);

    }

    public function resImage(Post $post){

        return view('posts.show', compact('post'));

    }
}
