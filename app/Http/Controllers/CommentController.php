<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Event;
use App\Models\User;
use Auth;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    


    public function comment(Request $request)
    {
       request()->validate([
            'comment'=>'required',
        ]);

        $comment=new Comment();
        $comment->post_id=$request->post_id;
        $comment->user_id=Auth::id();
        $comment->user_name=Auth::user()->first_name;
        $comment->comment=$request->comment;
        $comment->save();


        return back()->with('success','You commented successfully!.');
    }


    public function comment_delete($id)
    {
       $comment=Comment::find($id);
      
       $comment->delete();

       return back()->with('success','This comment deleted successfully!.');
    }


}

