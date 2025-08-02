<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('bookings')->insert([
            'nama_ruangan' => 'Ruang Rapat A',
            'nama_pengguna' => 'Ahmad',
            'keperluan' => 'Meeting Proyek',
            'mulai' => '2025-07-15 09:00:00',
            'selesai' => '2025-07-15 11:00:00',
            'status' => 'approved',
            'created_at' => Carbon::parse('2025-07-10 14:30:00'),
            'updated_at' => Carbon::parse('2025-07-11 10:00:00'),
        ]);
    }
}
