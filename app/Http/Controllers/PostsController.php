<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PostsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {
        $this->middleware('auth')->only(['create','edit','update','destroy']);

    }


     public function index()
    {
        return view('blog.index',[
            'posts'=> Post::orderBy('updated_at','desc')->paginate(10)
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'title'=>['required','unique:posts','max:255'],
            'excerpt'=>['required'],
            'body'=>['required'],
            'image_path'=>['required','mimes:jpg,png,jpeg','max:5048'],
            'min_to_read'=>'min:0|max:60'

        ]);
       $post= Post::create([
            'user_id'=>Auth::id(),
            'title'=>$request->title,
            'excerpt'=>$request->excerpt,
            'body'=>$request->body,
            'image_path'=>$this->storeImage($request),
            'is_published'=>$request->is_published==='on',
            'min_to_read'=>$request->min_to_read
        ]);
        $post->meta()->create([
            'post_id'=>$post->id,
            'mta_desc'=>$request->mta_desc,
            'meta_keywords'=>$request->meta_keywords,
            'meta_robots'=>$request->meta_robots

        ]);
    return redirect(route('blog.index'));

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        return view('blog.show',[
            'post'=>Post::findOrFail($id)
        ]);
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       return view('blog.edit',[
        'post'=>Post::where('id',$id)->first()
       ]);
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
        $request->validate([
            'title'=>'required|max:255|unique:posts,title,' .$id,
            'excerpt'=>['required'],
            'body'=>['required'],
            'image_path'=>['mimes:jpg,png,jpeg','max:5048'],
            'min_to_read'=>'min:0|max:60'

        ]);
       Post::where('id',$id)->update($request->except(
        ['_token','_method']
    ));
       return redirect(route('blog.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Post::destroy($id);
       return redirect(route('blog.index'))->with('message','post has been Deleted');

    }

    private function storeImage($request)
    {
        $newImageName=uniqid().'_'.$request->title.'.'.$request->image_path->extension();
        return $request->image_path->move(public_path('images'),$newImageName);

    }
}
