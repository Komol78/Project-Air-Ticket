<?php

namespace App\Http\Controllers;

use App\Models\Flighttype;
use Illuminate\Http\Request;

class FlighttypeController extends Controller
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



    public function flight_type()
    {

    	$types=Flighttype::get();
    	return view('admin.flighttype.flight-type',compact('types'));
    }


     public function add_flight_type()
    {
    	return view('admin.flighttype.add-flight-type');
    }



    public function store_flight_type(Request $request)
    {
    	request()->validate([
    		'flight_type'=>'required|string|max:191'
    	]);


    	$type=new Flighttype();
    	$type->flight_type=$request->flight_type;
    	$type->save();


    	return back()->with('success','This flight type saved successfully.');	


    }

    public function delete($id)
    {
    	
    	$delete=Flighttype::find($id);
    	$delete->delete();

    	return back()->with('success','This flight type deleted successfully.');	


    }






}
