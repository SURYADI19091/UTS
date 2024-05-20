<?php

namespace App\Http\Controllers;

use App\Models\categories;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class categoriescontroller extends Controller
{
    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',

        ]);
        if($validator->fails()){
            return response()->json($validator->messages(),422);
        }
        $payload = $validator->validated();
        categories::create([
            'name' => $payload['name'],

        ]);

        return response()->json([
            'msg' => 'data berhasil di simpan'
        ]);
    }
    public function showall(){
        $categories = categories::all();
        return response()->json([
            'msg' => 'Tidak terdapat data produk apapun',
            'data' => $categories
        ],200);
    }
    public function showbyid($id){
        $categories = categories::where('id',$id)->first();

        if ($categories){
            return response()->json([
                'msg' => 'Data categories yang anda cari :'.$id,
                'data' => $categories
            ]);
        }
        return response()->json([
            'msg' => 'data berdasarkan'.$id.'yang anda cari tidak ada atau telah di hapus'

        ],484);
    }


// untuk update data
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',

        ]);
        if($validator->fails()){
            return response()->json($validator->messages(),422);
        }
        $payload = $validator->validated();
        categories::where('id',$id)->update([
            'name' => $payload['name'],

        ]);

        return response()->json([
            'msg' => 'data berhasil di simpan'
        ],201);
    }

// untuk delete data
public function delete($id)
{
    $categories = categories::find($id);

    if ($categories) {
        $categories->delete(); // softdelete categories
        return response()->json([
            'msg' => 'Data berhasil dihapus'
        ], 200);
    }

    return response()->json([
        'msg' => 'Data dengan ID ' . $id . ' tidak ditemukan'
    ], 404);
}
}
