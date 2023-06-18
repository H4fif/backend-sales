<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Transaksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TransaksiController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $data = Transaksi::select(
            'barang.nama AS nama_barang',
            'jenis_barang.nama AS jenis_barang',
            'transaksi.id',
            'transaksi.jumlah',
            'transaksi.created_at AS tanggal_transaksi'
        )
            ->where('barang.nama', 'like', '%' . $request->nama_barang . '%');

        if (!empty($request->tanggal_transaksi)) {
            $data = $data->whereDate('transaksi.created_at', $request->tanggal_transaksi);
        }

        $data = $data->join('barang', 'transaksi.id_barang', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis_barang', 'jenis_barang.id')
            ->orderBy('barang.nama', 'asc')
            ->orderBy('transaksi.created_at', 'asc')
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
            'id_barang' => 'integer|required|exists:barang,id',
            'jumlah' => 'integer|required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $findBarang = Barang::find($request->id_barang);
        $findBarang->stock = $findBarang->stock - $request->jumlah;
        $findBarang->save();

        $result = Transaksi::create([
            'id_barang' => $request->id_barang,
            'jumlah' => $request->jumlah
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
        $data = Transaksi::select(
            'barang.nama AS nama_barang',
            'jenis_barang.nama AS jenis_barang',
            'transaksi.id',
            'transaksi.jumlah',
            'transaksi.created_at AS tanggal_transaksi'
        )
            ->where('transaksi.id', $id)
            ->join('barang', 'transaksi.id_barang', 'barang.id')
            ->join('jenis_barang', 'barang.id_jenis_barang', 'jenis_barang.id')
            ->first();

        if (empty($data)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        return response()->json([
            'data' => $data,
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
        $find = Transaksi::find($id);

        if (empty($find)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'id_barang' => 'integer|required|exists:jenis_barang,id',
            'jumlah' => 'integer|required|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $jumlahLama = $find->jumlah;

        $find->id_barang = $request->id_barang;
        $find->jumlah = $request->jumlah;
        $find->save();

        $findBarang = Barang::find($find->id_barang);
        $findBarang->stock = $findBarang->stock + $jumlahLama - $request->jumlah;
        $findBarang->save();

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
        $find = Transaksi::find($id);

        if (empty($find)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $findBarang = Barang::find($find->id_barang);
        $findBarang->stock = $findBarang->stock + $find->jumlah;
        $findBarang->save();

        Transaksi::destroy($id);

        return response()->json([], 204);
    }
}
