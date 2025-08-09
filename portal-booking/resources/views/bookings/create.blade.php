<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Booking Baru</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
        }
    </style>
</head>
<body class="bg-gray-100 p-8">

    <div class="container mx-auto max-w-2xl bg-white p-8 rounded-xl shadow-lg">
        <h1 class="text-3xl font-bold mb-4 text-center">Buat Booking Baru</h1>
        <hr class="mb-6">

        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                {{ session('error') }}
            </div>
        @endif

        <!-- Menambahkan blok untuk menampilkan error booking_conflict -->
        @error('booking_conflict')
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded-lg relative mb-4" role="alert">
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="nama_pengguna" class="block text-gray-700 font-semibold mb-2">Nama Pengguna</label>
                <input type="text" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('nama_pengguna') border-red-500 @enderror" id="nama_pengguna" name="nama_pengguna" value="{{ old('nama_pengguna') }}">
                @error('nama_pengguna')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div> 

            <div class="mb-4">
                <label for="room_id" class="block text-gray-700 font-semibold mb-2">Pilih Ruangan</label>
                <select class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('room_id') border-red-500 @enderror" id="room_id" name="room_id">
                    <option value="">-- Pilih Ruangan --</option>
                    {{-- Menyimpan data rooms sebagai atribut data untuk digunakan oleh JavaScript --}}
                    @php
                        $roomsJson = json_encode($rooms);
                    @endphp
                    @foreach($rooms as $room)
                        <option value="{{ $room['id'] }}" data-room-name="{{ $room['name'] }}" data-room-capacity="{{ $room['capacity'] }}" {{ old('room_id') == $room['id'] ? 'selected' : '' }}>
                            {{ $room['name'] }} (Kapasitas: {{ $room['capacity'] }})
                        </option>
                    @endforeach
                </select>
                @error('room_id')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <!-- Elemen baru untuk menampilkan detail ruangan yang dipilih -->
            <div id="room-details-card" class="bg-gray-50 p-4 rounded-lg border border-gray-200 mt-4 hidden">
                <p class="font-bold text-gray-800">Detail Ruangan:</p>
                <div class="mt-2 text-gray-600">
                    <p><strong>Nama:</strong> <span id="detail-name"></span></p>
                    <p><strong>Kapasitas:</strong> <span id="detail-capacity"></span> orang</p>
                </div>
            </div>

            <div class="mb-4 mt-6">
                <label for="start_date" class="block text-gray-700 font-semibold mb-2">Tanggal dan Waktu Mulai</label>
                <input type="datetime-local" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('start_date') border-red-500 @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-4">
                <label for="end_date" class="block text-gray-700 font-semibold mb-2">Tanggal dan Waktu Selesai</label>
                <input type="datetime-local" class="w-full px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 @error('end_date') border-red-500 @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                @error('end_date')
                    <div class="text-red-500 text-sm mt-1">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-lg hover:bg-blue-700 transition-colors">Buat Booking</button>
            <a href="{{ route('bookings.index') }}" class="block text-center mt-3 text-blue-600 hover:text-blue-800">Batal</a>
        </form>
    </div>

    <script>
        const roomsDropdown = document.getElementById('room_id');
        const roomDetailsCard = document.getElementById('room-details-card');
        const detailName = document.getElementById('detail-name');
        const detailCapacity = document.getElementById('detail-capacity');

        roomsDropdown.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            
            if (selectedOption.value) {
                // Ambil data dari atribut data pada elemen <option>
                const roomName = selectedOption.getAttribute('data-room-name');
                const roomCapacity = selectedOption.getAttribute('data-room-capacity');

                // Update konten card detail ruangan
                detailName.textContent = roomName;
                detailCapacity.textContent = roomCapacity;
                roomDetailsCard.classList.remove('hidden');
            } else {
                // Sembunyikan card jika tidak ada ruangan yang dipilih
                roomDetailsCard.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
