<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Data_Crud;
use Validator;

class DatadiriController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('index');
    }
   

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
   
     //~~~~~Create Data~~~~~//
    public function store(Request $request){
        $storeData = $request->all();
        $validate = Validator::make($storeData,[
            'userName'  => 'required',       
            'userEmail' => 'required|email:rfc,dns',
        ]);

        if($validate->fails())   
                       
            return response(['message' => $validate->errors()],400);

        $result = Data_Crud::insert([
            'nama' => $storeData['userName'],
            'email' => $storeData['userEmail'],
            ]);
        return response([
            'message' => 'Add Data Success',
            'data' => $result,
            'status' => 200
        ],200);
    }

    //~~~~~View Data~~~~~~//
    public function view()
    {
    
        $data = Data_Crud::all();
        $dataCount = $data -> count();
        if(count($data)>0){
            return response ([
                'count' => $dataCount,
                'message' => 'Retrieve All Success',
                'data' => $data
            ],200);
        }

        return response([
            'data' => null
        ],404);
    }

    //~~~~~Find Data~~~~~~//
    public function find(Request $request)
    {
    
        $data = Data_Crud::find($request['userId']);
        if(!is_null($data)) {
            return response([
                'message' => 'Retrive Menu Success',
                'data' => $data,
                'status' => 200
            ], 200);
        }

        return response([
            'message' => 'Data Not Found',
            'data' => null
        ], 404);

    }
    
    //~~~~~~Update Data~~~~~~//
    public function update(Request $request)
    {
        $data = Data_Crud::find($request['userId']);
        if(is_null($data)) {
            return response([
                'message' => 'data Not Found',
                'data' => null
            ], 404);
        }

        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'userId' => 'required',
            'userEmail' => 'required|email:rfc,dns',
        ]);

        if($validate->fails())
            return response(['message' => $validate->errors()], 400);

        $data->email = $updateData['userEmail'];
        if($data->save()) { 
            return response([
                'message' => 'Update Data Success',
                'data' => $data,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Update Data Failed',
            'data' => null
        ], 400);
    }


    //Delete Data//
    public function destroy(Request $cari)
    {
        $data = Data_Crud::find($cari['userId']);;

        if(is_null($data)) {
            return response([
                'message' => $data,
                'data' => $data
            ], 404);
        }

        else {
            $data->delete();
            return response([
                'message' => 'Delete Data Success',
                'data' => $data,
                'status' => 200
            ], 200);
        }
        return response([
            'message' => 'Delete Data Failed',
            'data' => null
        ], 400);
    }
}
