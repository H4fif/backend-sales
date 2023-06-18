<?php

namespace App\Http\Controllers;

use App\Models\JenisBarang;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JenisBarangController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function get(Request $request)
    {
        $data = JenisBarang::where('nama', 'like', '%' . strtolower(trim($request->keyword)) . '%')
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
            'nama' => 'bail|string|required|unique:jenis_barang,nama',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $result = JenisBarang::create([
            'nama' => ucwords(strtolower($request->nama))
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
        $result = JenisBarang::find($id);

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
        $find = JenisBarang::find($id);

        if (empty($find)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'nama' => 'bail|string|required|unique:jenis_barang,nama,' . $id . ',id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation error',
                'errors' => $validator->errors()->first()
            ], 400);
        }

        $find->nama = ucwords(strtolower($request->nama));
        $find->save();

        return response()->json([
            'message' => 'success'
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $jenisBarang
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $find = JenisBarang::find($id);

        if (empty($find)) {
            return response()->json([
                'message' => 'Data not found',
            ], 404);
        }

        JenisBarang::destroy($id);

        return response()->json([], 204);
    }
}
