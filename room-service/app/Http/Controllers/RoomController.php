<?php

namespace App\Http\Controllers;

use App\Models\Room; // Import model Room
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator; // Untuk validasi input

/**
 * @OA\Schema(
 * schema="Room",
 * title="Room",
 * required={"name", "capacity"},
 * @OA\Property(property="id", type="integer", readOnly=true, example=1),
 * @OA\Property(property="name", type="string", example="Ruang Rapat 1"),
 * @OA\Property(property="capacity", type="integer", example=10),
 * @OA\Property(property="facilities", type="string", example="Proyektor, Whiteboard"),
 * @OA\Property(property="created_at", type="string", format="date-time", readOnly=true),
 * @OA\Property(property="updated_at", type="string", format="date-time", readOnly=true),
 * )
 */
class RoomController extends Controller
{
    /**
     * @OA\Get(
     * path="/api/rooms",
     * operationId="getRoomsList",
     * tags={"Rooms"},
     * summary="Mendapatkan daftar semua ruangan",
     * @OA\Response(
     * response=200,
     * description="Daftar semua ruangan berhasil diambil.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Daftar semua ruangan berhasil diambil."),
     * @OA\Property(
     * property="data",
     * type="array",
     * @OA\Items(ref="#/components/schemas/Room")
     * )
     * )
     * )
     * )
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
     * @OA\Post(
     * path="/api/rooms",
     * operationId="storeRoom",
     * tags={"Rooms"},
     * summary="Menyimpan ruangan baru",
     * @OA\RequestBody(
     * required=true,
     * description="Data ruangan baru",
     * @OA\JsonContent(
     * required={"name", "capacity"},
     * @OA\Property(property="name", type="string", example="Ruang Rapat A"),
     * @OA\Property(property="capacity", type="integer", example=20),
     * @OA\Property(property="facilities", type="string", example="AC, Proyektor")
     * )
     * ),
     * @OA\Response(
     * response=201,
     * description="Ruangan berhasil dibuat.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Ruangan berhasil dibuat."),
     * @OA\Property(property="data", ref="#/components/schemas/Room")
     * )
     * ),
     * @OA\Response(
     * response=422,
     * description="Validasi gagal."
     * )
     * )
     */
    public function store(Request $request)
    {
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
     * @OA\Get(
     * path="/api/rooms/{id}",
     * operationId="getRoomById",
     * tags={"Rooms"},
     * summary="Mendapatkan detail ruangan berdasarkan ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID ruangan",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Detail ruangan berhasil diambil.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Detail ruangan berhasil diambil."),
     * @OA\Property(property="data", ref="#/components/schemas/Room")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Ruangan tidak ditemukan."
     * )
     * )
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
     * @OA\Put(
     * path="/api/rooms/{id}",
     * operationId="updateRoom",
     * tags={"Rooms"},
     * summary="Memperbarui data ruangan berdasarkan ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID ruangan",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\RequestBody(
     * required=true,
     * description="Data ruangan yang akan diperbarui",
     * @OA\JsonContent(
     * @OA\Property(property="name", type="string", example="Ruang Rapat B"),
     * @OA\Property(property="capacity", type="integer", example=25),
     * @OA\Property(property="facilities", type="string", example="Wi-Fi, Whiteboard")
     * )
     * ),
     * @OA\Response(
     * response=200,
     * description="Ruangan berhasil diperbarui.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Ruangan berhasil diperbarui."),
     * @OA\Property(property="data", ref="#/components/schemas/Room")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Ruangan tidak ditemukan."
     * ),
     * @OA\Response(
     * response=422,
     * description="Validasi gagal."
     * )
     * )
     */
    public function update(Request $request, string $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return response()->json([
                'message' => 'Ruangan tidak ditemukan.'
            ], 404);
        }
        
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
     * @OA\Delete(
     * path="/api/rooms/{id}",
     * operationId="deleteRoom",
     * tags={"Rooms"},
     * summary="Menghapus ruangan berdasarkan ID",
     * @OA\Parameter(
     * name="id",
     * in="path",
     * description="ID ruangan",
     * required=true,
     * @OA\Schema(type="integer")
     * ),
     * @OA\Response(
     * response=200,
     * description="Ruangan berhasil dihapus.",
     * @OA\JsonContent(
     * @OA\Property(property="message", type="string", example="Ruangan berhasil dihapus.")
     * )
     * ),
     * @OA\Response(
     * response=404,
     * description="Ruangan tidak ditemukan."
     * )
     * )
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
