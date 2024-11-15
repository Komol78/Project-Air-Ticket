<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Auth;

class UsersController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function users()
    {
        $users=User::get();
        return view('admin.user.users',compact('users'));
    }


    public function user_verify($id)
    {
        $user=User::find($id);
        $user->status=1;
        $user->save();

        return back()->with('success','This user verified successfully!.');
    }

    public function user_details($id)
    {
       $user=User::find($id);
       return view('admin.user.user-details',compact('user'));
    }


    public function user_delete($id)
    {
       $user=User::find($id);
       unlink('./uploads/profile/'.$user->profile_photo);
       unlink('./uploads/nid/'.$user->nid_front);
       unlink('./uploads/nid/'.$user->nid_back);
       $user->delete();
       return back()->with('success','This user deleted successfully!.');
    }


}

