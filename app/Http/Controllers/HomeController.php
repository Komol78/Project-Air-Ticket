<?php

namespace App\Http\Controllers;

use App\Models\Donate;
use App\Models\Flight;
use App\Models\Flightbook;
use App\Models\Post;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class HomeController extends Controller
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

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $res)
    {
        $user = User::find(Auth::id());
        $user->first_name = $res->input('first_name', $user->first_name);
        $user->last_name = $res->input('last_name', $user->last_name);
        $user->phone = $res->input('phone', $user->phone);
        $user->email = $res->input('email', $user->email);
        $user->address = $res->input('address', $user->address);
        $user->gender = $res->input('gender', $user->gender);
        $user->birth_date = $res->input('birth_date', $user->birth_date);
        $user->passport = $res->input('passport', $user->passport);
        $user->save();

        return view('front.profile.profile', compact('user'));
    }

    public function posts()
    {

        $posts=Post::where('user_id',Auth::id())->get();
        return  view('front.profile.post',compact('posts'));
    }


    public function add_post()
    {
        return  view('front.profile.add-post');
    }


    public function store_post(Request $request)
    {
        request()->validate([
            'title'=>'required|string|max:191',
            'thumnail'=>'required',
            'attachment'=>'nullable',
            'description'=>'required',
        ]);


        $tname=$request->file('thumnail')->getClientOriginalName();
        $thum_name = time().$tname;
        $request->file('thumnail')->move('./uploads/post/', $thum_name);

        if ($request->hasFile('attachment')) {
            $aname=$request->file('attachment')->getClientOriginalName();
            $attach_name = time().$aname;
            $request->file('attachment')->move('./uploads/attachment/', $attach_name);
        }
       

        $post=new Post();
        $post->user_id=Auth::id();
        $post->title=$request->title;
        $post->thumnail=$thum_name;
        $post->attachment=$attach_name;
        $post->description=$request->description;
        $post->type='User';
        $post->status=0;
        $post->save();


        return back()->with('success','Your post saved successfully!.Your post status is pending.');
       


    }


    public function flight_book(Request $request)
    {
       

        $book=new Flightbook();
        $book->user_id=\Auth::id();
        $book->flight_id=$request->flight_id;
        $book->flight_type_id=$request->flight_type_id;
        $book->flight_type=$request->flight_type;
        $book->class=$request->class;
        $book->from_country=$request->from_country;
        $book->to_country=$request->to_country;
        $book->price=$request->price;
        $book->person=$request->person;
        $book->date=$request->date;

        $book->save();


        return redirect('/my-flight')->with('success','Your flight booking request send successfully!.');

    }



    public function my_flight()
    {
       $myflights=Flightbook::where('user_id',Auth::id())->get();
       return  view('front.profile.my-flight',compact('myflights'));
    }

    public function rateAndCommnet($flight_id)
    {
        $flight = Flightbook::find($flight_id);
        return view('front.flight-rate', compact('flight'));
    }
    
    public function postRateAndCommnet(Request $r)
    {
        $r->validate([
            'id' => 'required',
            'comment' => 'required',
            'rating' => 'required'
        ]);
        $flight = Flightbook::find($r->id);
        $flight->rate =(int) $r->rating;
        $flight->comment = $r->comment;
        $flight->save();
        return redirect()->route('my-flight')->with('success','Thank You For Your Feedback.');
    }

    public function userVerify()
    {
        return view('front.otp_varify');
    }
    
    public function postUserVerify(Request $req)
    {
        $req->validate([
            'otp' => 'required'
        ]);

        $user = User::find(auth()->user()->id);
        if($user->otp == $req->otp)
        {
            $user->email_verified_at = now();
            $user->save();
        }
        return redirect()->route('home');
    }
}
