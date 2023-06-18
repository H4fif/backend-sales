<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $data = Barang::select(
            'barang.id',
            'barang.nama AS nama_barang',
            'jenis_barang.nama AS jenis_barang',
            'barang.stock',
        )
            ->where('barang.nama', 'like', '%' . strtolower(trim($request->nama)) . '%')
            ->where('barang.stock', 'like', '%' . $request->stock . '%')
            ->join('jenis_barang', 'barang.id_jenis_barang', 'jenis_barang.id')
            ->get();


        return response()->json([
            'data' => $data,
            'message' => 'success'
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'bail|string|required|unique:barang,nama',
            'id_jenis_barang' => 'integer|required|exists:jenis_barang,id',
            'stock' => 'integer|required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $result = Barang::create([
            'nama' => ucwords(strtolower($request->nama)),
            'id_jenis_barang' => $request->id_jenis_barang,
            'stock' => $request->stock
        ]);

        return response()->json([
            'message' => 'success',
            'result' => $result
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result = Barang::select(
            'barang.id',
            'barang.nama AS nama_barang',
            'jenis_barang.nama AS jenis_barang',
            'barang.stock',
        )
            ->where('barang.id', $id)
            ->join('jenis_barang', 'barang.id_jenis_barang', 'jenis_barang.id')
            ->first();

        if (empty($result)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'data' => $result,
            'message' => 'success'
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update($id, Request $request)
    {
        $find = Barang::find($id);

        if (empty($find)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'bail|string|required|unique:barang,nama,' . $id . ',id',
            'id_jenis_barang' => 'integer|required|exists:jenis_barang,id',
            'stock' => 'integer|required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $find->nama = ucwords(strtolower($request->nama));
        $find->id_jenis_barang = $request->id_jenis_barang;
        $find->stock = $request->stock;
        $find->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $Barang
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = Barang::find($id);

        if (empty($find)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        Barang::destroy($id);

        return response()->json([], 204);
    }
}
