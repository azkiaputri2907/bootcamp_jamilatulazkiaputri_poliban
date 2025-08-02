<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class BookingController extends Controller
{
    /**
     * Tampilkan daftar semua booking yang ada.
     */
    public function index()
    {
        // Ambil semua booking beserta data user terkait
        $bookings = Booking::with('user')->get();

        // Kirim data bookings ke view
        return view('bookings.index', compact('bookings'));
    }

    /**
     * Tampilkan formulir untuk membuat booking baru.
     */
    public function create()
    {
        $rooms = [];
        
        try {

            $token =config(key: 'services.room_service.token');
            //$response = Http::get('http://room-service-nginx/api/rooms');
            $response = Http::withToken(token: $token)->get(url: 'http://room-service-nginx/api/rooms');

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    $rooms = $responseData['data'];
                } else {
                    Log::error('Kunci "data" tidak ditemukan atau bukan array dalam respons API rooms.', ['body' => $response->body()]);
                    return redirect()->back()->with('error', 'Gagal mengambil data ruangan. Respons API tidak valid.');
                }
            } else {
                Log::error('Gagal mengambil data ruangan dari room-service.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->back()->with('error', 'Gagal mengambil data ruangan. Silakan coba lagi.');
            }
        } catch (Exception $e) {
            Log::error('Service room-service-nginx tidak dapat dijangkau.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Layanan ruangan tidak tersedia. Silakan coba lagi nanti.');
        }

        return view('bookings.create', compact('rooms'));
    }

    /**
     * Tampilkan formulir untuk mengedit booking.
     */
    public function edit(Booking $booking)
    {
        $rooms = [];

        try {
            $response = Http::get('http://room-service-nginx/api/rooms');

            if ($response->successful()) {
                $responseData = $response->json();
                
                if (isset($responseData['data']) && is_array($responseData['data'])) {
                    $rooms = $responseData['data'];
                } else {
                    Log::error('Kunci "data" tidak ditemukan atau bukan array dalam respons API rooms.', ['body' => $response->body()]);
                    return redirect()->back()->with('error', 'Gagal mengambil data ruangan. Respons API tidak valid.');
                }
            } else {
                Log::error('Gagal mengambil data ruangan dari room-service.', [
                    'status' => $response->status(),
                    'body' => $response->body()
                ]);
                return redirect()->back()->with('error', 'Gagal mengambil data ruangan. Silakan coba lagi.');
            }
        } catch (Exception $e) {
            Log::error('Service room-service-nginx tidak dapat dijangkau.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Layanan ruangan tidak tersedia. Silakan coba lagi nanti.');
        }

        return view('bookings.edit', compact('booking', 'rooms'));
    }

    /**
     * Perbarui data booking di database.
     */
    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'user_name' => 'required|string|max:255',
            'room_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'status' => 'required|string|in:pending,confirmed,cancelled',
        ]);

        try {
            // Cari pengguna berdasarkan nama, atau buat pengguna baru jika tidak ditemukan
            // Menggunakan Str::uuid() untuk email agar lebih unik
            $user = User::firstOrCreate(
                ['name' => $request->input('user_name')],
                [
                    'email' => str_replace(' ', '', strtolower($request->input('user_name'))) . '_' . Str::uuid() . '@example.com',
                    'password' => Hash::make('password')
                ]
            );

            $booking->update([
                'user_id' => $user->id,
                'room_id' => $request->input('room_id'),
                'start_date' => $request->input('start_date'),
                'end_date' => $request->input('end_date'),
                'status' => $request->input('status'),
            ]);

            return redirect()->route('bookings.index')->with('success', 'Booking berhasil diperbarui.');
        } catch (Exception $e) {
            Log::error('Gagal memperbarui booking.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui booking.');
        }
    }

    /**
     * Hapus data booking dari database.
     */
    public function destroy(Booking $booking)
    {
        try {
            $booking->delete();
            return redirect()->route('bookings.index')->with('success', 'Booking berhasil dihapus.');
        } catch (Exception $e) {
            Log::error('Gagal menghapus booking.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menghapus booking.');
        }
    }

    /**
     * Simpan booking baru yang telah dibuat.
     */
    public function store(Request $request)
    {
        // Pastikan nama input di form dan controller sama (nama_pengguna)
        $request->validate([
            'nama_pengguna' => 'required|string|max:255',
            'room_id' => 'required|integer',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        try {
            // Cari user berdasarkan nama, atau buat user baru jika tidak ditemukan
            // Menggunakan Str::uuid() untuk email agar lebih unik
            $user = User::firstOrCreate(
                ['name' => $request->input('nama_pengguna')],
                [
                    'email' => str_replace(' ', '', strtolower($request->input('nama_pengguna'))) . '_' . Str::uuid() . '@example.com',
                    'password' => Hash::make('password')
                ]
            );
            
            $booking = new Booking();
            $booking->user_id = $user->id;
            $booking->room_id = $request->input('room_id');
            $booking->start_date = $request->input('start_date');
            $booking->end_date = $request->input('end_date');
            $booking->status = 'pending';
            $booking->save();

            return redirect()->route('bookings.index')->with('success', 'Booking berhasil dibuat!');
        } catch (Exception $e) {
            Log::error('Gagal menyimpan booking.', ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat membuat booking.');
        }
    }
}
