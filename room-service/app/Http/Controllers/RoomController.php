<?php

namespace App\Http\Controllers;

use App\Models\Room; // Import model Room
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Untuk validasi input

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $rooms = Room::all();
        return response()->json([
            'message' => 'Daftar semua ruangan berhasil diambil.',
            'data' => $rooms
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Menambahkan validasi untuk room_number dan price
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'facilities' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422); // Unprocessable Entity
        }

        $room = Room::create($request->all()); // Menggunakan Mass Assignment
        return response()->json([
            'message' => 'Ruangan berhasil dibuat.',
            'data' => $room
        ], 201); // Created
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Ruangan tidak ditemukan.'
            ], 404); // Not Found
        }

        return response()->json([
            'message' => 'Detail ruangan berhasil diambil.',
            'data' => $room
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Ruangan tidak ditemukan.'
            ], 404);
        }
        
        // Menambahkan validasi untuk room_number dan price pada update
        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'capacity' => 'sometimes|required|integer|min:1',
            'facilities' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validasi gagal.',
                'errors' => $validator->errors()
            ], 422);
        }

        $room->update($request->all()); // Menggunakan Mass Assignment
        return response()->json([
            'message' => 'Ruangan berhasil diperbarui.',
            'data' => $room
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Ruangan tidak ditemukan.'
            ], 404);
        }

        $room->delete();
        return response()->json([
            'message' => 'Ruangan berhasil dihapus.'
        ], 200); // OK
    }
}
