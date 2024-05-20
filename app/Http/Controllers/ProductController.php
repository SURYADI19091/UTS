<?php

namespace App\Http\Controllers;

use App\Models\product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class productcontroller extends Controller
{
// untuk create data
    public function store(Request $request) {
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'price' => 'required|numeric:11',
            'image' => 'required|mimes:png,jpg,jepg',
            'category_id' => 'required|numeric',
            'expired_at' => 'required|date',
            'modified_by' => 'required|max:50',

        ]);
        if($validator->fails()){
            return response()->json($validator->messages(),422);
        }
        $payload = $validator->validated();
        product::create([
            'name' => $payload['name'],
            'description' => $payload['description'],
            'price' => $payload['price'],
            'image' => $payload['image'],
            'category_id' => $payload['category_id'],
            'expired_at' =>$payload['expired_at'],
            'modified_by' => $payload['modified_by'],
        ]);

        return response()->json([
            'msg' => 'data berhasil di simpan'
        ],201);

    }

// Ini untuk Show data
    public function showall(){
        $product = product::all();
        return response()->json([
            'msg' => 'Product yang ada sekarang',
            'data' => $product
        ],200);
    }
    public function showbyid($id){
        $product = product::where('id',$id)->first();

        if ($product){
            return response()->json([
                'msg' => 'Data product yang anda cari :'.$id,
                'data' => $product
            ]);
        }
        return response()->json([
            'msg' => 'data berdasarkan'.$id.'yang anda cari tidak ada atau telah di hapus'

        ],484);
    }
    public function showbycategories($category_id)
    {
        $product = Product::where('category_id', $category_id)->first();
    
        if ($product) {
            return response()->json([
                'msg' => 'Data yang Anda cari berdasarkan kategori: '.$category_id,
                'data' => $product
            ]);
        }
    
        return response()->json([
            'msg' => 'Data berdasarkan kategori '.$category_id.' yang Anda cari tidak ada atau telah dihapus'
        ], 484);
    }

// untuk update data
    public function update(Request $request,$id){
        $validator = Validator::make($request->all(),[
            'name' => 'required|max:50',
            'description' => 'required|max:255',
            'price' => 'required|numeric:11',
            'image' => 'required|mimes:png,jpg,jepg',
            'category_id' => 'required|numeric',
            'expired_at' => 'required|date',
            'modified_by' => 'required|max:50',
        ]);
        if($validator->fails()){
            return response()->json($validator->messages(),422);
        }
        $payload = $validator->validated();
        product::where('id',$id)->update([
            'name' => $payload['name'],
            'description' => $payload['description'],
            'price' => $payload['price'],
            'image' => $payload['image'],
            'category_id' => $payload['category_id'],
            'expired_at' =>$payload['expired_at'],
            'modified_by' => $payload['modified_by'],
        ]);

        return response()->json([
            'msg' => 'data berhasil di simpan'
        ],201);
    }

// untuk delete data
public function delete($id)
{
    $product = Product::find($id);

    if ($product) {
        $product->delete(); // Soft delete the product
        return response()->json([
            'msg' => 'Data berhasil dihapus'
        ], 200);
    }

    return response()->json([
        'msg' => 'Data dengan ID ' . $id . ' tidak ditemukan'
    ], 404);
}
}
