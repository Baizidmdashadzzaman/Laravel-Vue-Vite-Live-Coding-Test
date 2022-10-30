<?php

namespace App\Http\Controllers;

use App\Models\Style;
use App\Models\StyleItem;
use Illuminate\Http\Request;

class StyleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $allData = Style::with('buyer')->latest()->paginate(4);
		return response()->json([
		   'allData' => $allData
		]); 
    }

    public function index_all()
    {
        $allData = Style::with('buyer')->latest()->get();
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
        
        $metarialname = explode(',' ,$request->metarialname);
        $width = explode(',' ,$request->width);
        $unit = explode(',' ,$request->unit);
        $size = explode(',' ,$request->size);
        // dd($metarialname[0]);
        
        if($request->buyer_id == 0)
        {
            $message="Please select buyer.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]); 
        }
        $checkExist=Style::where('style_number',$request->style_number)->first();
        if($checkExist != null)
        {
            $message="Style number already exit,please try again with different number.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status
            ]); 
        }

        $data = new Style();
        $data->buyers_id = $request->buyer_id;
        $data->style_number = $request->style_number;
        $data->color = $request->checkedColor;
        $save = $data->save();
        if($save)
        {
            for($i=0;$i<count($metarialname);$i++)
            {
                if($metarialname[$i] != "")
                {
                    $datanew = new StyleItem();
                    $datanew->style_id = $data->id;
                    $datanew->metarial_number = $metarialname[$i];
                    if(array_key_exists($i, $width))
                    {
                       $datanew->width_number = $width[$i];
                    }
                    if(array_key_exists($i, $unit))
                    {
                       $datanew->unit = $unit[$i];
                    }
                    if(array_key_exists($i, $size))
                    {
                       $datanew->size = $size[$i];
                    }
                    $save = $datanew->save();
                }
                 
            }
            $id = $data->id;
            $message="Style information created successfully , please add items now.";
            $status = 1;
            return response()->json([
                'message' => $message,
                'status' => $status,
                'id' => $id,
            ]);  
        }
        else
        {
            $message="Unable to create data,please try again.";
            $status = 0;
            return response()->json([
                'message' => $message,
                'status' => $status,
            ]); 
        }
    }


    public function store_more(Request $request)
    {
        
        $metarialname = explode(',' ,$request->metarialname);
        $width = explode(',' ,$request->width);
        $unit = explode(',' ,$request->unit);
        $size = explode(',' ,$request->size);
        
        for($i=0;$i<count($metarialname);$i++)
        {
             $datanew = new StyleItem();
             $datanew->style_id = $request->id;
             $datanew->metarial_number = $metarialname[$i];
             if(array_key_exists($i, $width))
             {
                $datanew->width_number = $width[$i];
             }
             if(array_key_exists($i, $unit))
             {
                $datanew->unit = $unit[$i];
             }
             if(array_key_exists($i, $size))
             {
                $datanew->size = $size[$i];
             }
             $save = $datanew->save();
        }

        $message="Style information added successfully , please add items now.";
        $status = 1;
        return response()->json([
            'message' => $message,
            'status' => $status,

        ]);  
    }
    

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Style  $style
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $singledata = Style::with('buyer')->find($id);
        return response()->json([
            'singledata' => $singledata
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Style  $style
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $singledata = Style::with('buyer')->with('items')->find($id);
        return response()->json([
            'singledata' => $singledata
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Style  $style
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        
        $data = Style::find($request->id);
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
        $data = Style::find($id);
        $delete = $data->delete();
        
        if($delete)
        {   
            $allData = Style::with('buyer')->latest()->paginate(4);
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
    
    public function destroy_item($id)
    {
        $data = StyleItem::find($id);
        $delete = $data->delete();
        
        if($delete)
        {   
            $message="Data deleted sccuessfully.";
            $status = 1;
            return response()->json([
                'message' => $message,
                'status' => $status,
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
            $allData=Style::with('buyer')->latest()->paginate(4);
		}
		else
		{
            $allData=Style::with('buyer')->where('style_number', 'LIKE', "%{$query}%")->latest()->paginate(4);		
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
            $allData=Style::with('buyer')->latest()->get();
		}
		else
		{
            $allData=Style::with('buyer')->where('style_number', 'LIKE', "%{$query}%")->latest()->get();		
		}
		return response()->json([
            'allData' => $allData,
        ]); 	
	}
}
