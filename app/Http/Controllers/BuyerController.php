<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;
use Session;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class BuyerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $allData = Buyer::latest()->paginate(30);
		return response()->json([
		   'allData' => $allData
		]); 
    }

    public function index_all()
    {
        $allData = Buyer::latest()->get();
		return response()->json([
		   'allData' => $allData
		]); 
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $checkEmailExist=Buyer::where('buyers_email',$request->buyers_email)->first();
        if($checkEmailExist != null)
        {
            $message="Email already exit,please try again with different email address.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]); 
        }

        $data = new Buyer();
        $data->buyers_name = $request->buyers_name;
        $data->buyers_email = $request->buyers_email;
        $save = $data->save();
        if($save)
        {
            $message="Data created successfully.";
            $status = 1;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]);  
        }
        else
        {
            $message="Unable to create data,please try again.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]); 
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $singledata = Buyer::find($id);
        return response()->json([
            'singledata' => $singledata
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $singledata = Buyer::find($id);
        return response()->json([
            'singledata' => $singledata
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Buyer  $buyer
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $data = Buyer::find($request->id);
        $data->buyers_name = $request->buyers_name;
        $data->buyers_email = $request->buyers_email;
        $save = $data->update();
        if($save)
        {
            $message="Data updated successfully.";
            $status = 1;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]);  
        }
        else
        {
            $message="Unable to update data,please try again.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]); 
        }
    }

    public function destroy($id)
    {
        $data = Buyer::find($id);
        $delete = $data->delete();
        
        if($delete)
        {   
            $allData = Buyer::latest()->paginate(30);
            $message="Data deleted sccuessfully.";
            $status = 1;
            return response()->json([
                'message' => $message,
                'status' => $status,
                'allData' => $allData
            ]); 
        }
        else
        {
            $message="Unable to delete data,please try again.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]); 
        }
        
    }

	public function search(Request $request)
	{
        $query = $request->get('query');
		if($request->get('query') == null)
		{
            $allData=Buyer::latest()->paginate(30);
		}
		else
		{
            $allData=Buyer::where('buyers_name', 'LIKE', "%{$query}%")->latest()->paginate(30);		
		}
		return response()->json([
            'allData' => $allData,
        ]); 	
	}

    public function search_all(Request $request)
	{
        $query = $request->get('query');
		if($request->get('query') == null)
		{
            $allData=Buyer::latest()->get();
		}
		else
		{
            $allData=Buyer::where('buyers_name', 'LIKE', "%{$query}%")->latest()->get();		
		}
		return response()->json([
            'allData' => $allData,
        ]); 	
	}
}
