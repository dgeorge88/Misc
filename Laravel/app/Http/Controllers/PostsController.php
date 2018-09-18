<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Post;

#call DB when using plain SQL to query
//use DB;

class PostsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        #blocks all pages for non-authenticated users 
        //$this->middleware('auth');

        #Creates same auth rescription, but adds exception to pages
        $this->middleware('auth', ['except' => ['index', 'show']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        #individual post where
        //return $post = Post::where('title', 'Post Two')->get();
        
        #use SQL to query
        //$posts = DB::select('SELECT * FROM posts');
        
        #get all
        //$posts = Post::all();

        #single query
        //return $posts = Post::orderBy('title', 'desc')->take(1)->get();

        #add paging to cap number of posts per page
        $posts = Post::orderBy('created_at', 'desc')->paginate(10);

        
        //$posts = Post::orderBy('title', 'desc')->get();
        return view('posts.index')->with('posts', $posts);
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
    public function store(Request $request)
    {
        //stores data from form
        $this->validate($request ,[
            'title' => 'required',
            'body' => 'required',
            'cover_image' => 'image|nullable|max:1999'

        ]);

        //handle file upload
        if($request->hasFile('cover_image')){
            //get filename with extension
            $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
            //Get just filename
            $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
            //get just extension
            $extension = $request->file('cover_image')->getClientOriginalExtension();
            //filename to store
            $fileNameToStore = $filename.'_'.time().'.'.$extension;
            //upload image
            $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
        }else {
            $fileNameToStore = 'noimage.jpg';
        }

        //Create Post
        $post = new Post;
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        $post->user_id = auth()->user()->id;
        $post->cover_image = $fileNameToStore;
        $post->save();

        return redirect('/posts')->with('success', 'Post Created');


        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //get a single post by id
        $post = Post::find($id);
        return view('posts.show')->with('post', $post);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        
        //load single id post to edit
        $post = Post::find($id);

        //check for correct user
        if(auth()->user()->id !==$post->user_id){

            return redirect('posts')->with('error', 'Unauthorised User');
        }

        return view('posts.edit')->with('post', $post);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $this->validate($request ,[
            'title' => 'required',
            'body' => 'required'

        ]);

                //handle file upload
                if($request->hasFile('cover_image')){
                    //get filename with extension
                    $filenameWithExt = $request->file('cover_image')->getClientOriginalName();
                    //Get just filename
                    $filename = pathinfo($filenameWithExt, PATHINFO_FILENAME);
                    //get just extension
                    $extension = $request->file('cover_image')->getClientOriginalExtension();
                    //filename to store
                    $fileNameToStore = $filename.'_'.time().'.'.$extension;
                    //upload image
                    $path = $request->file('cover_image')->storeAs('public/cover_images', $fileNameToStore);
                }

        //Create Post
        $post = Post::find($id);
        $post->title = $request->input('title');
        $post->body = $request->input('body');
        if($request->hasFile('cover_image')){
            $post->cover_image = $fileNameToStore;
        }
        $post->save();

        return redirect('/posts')->with('success', 'Post Updated');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //delete post
        $post = Post::find($id);

                //check for correct user
                if(auth()->user()->id !==$post->user_id){

                    return redirect('posts')->with('error', 'Unauthorised User');
                }

                if($post->cover_image != 'noimage.jpg'){

                    //delete image
                    Storage::delete('public/cover_images/'.$post->cover_image);

                }
                
        $post->delete();
        return redirect('/posts')->with('success', 'Post Removed');

    }
}
