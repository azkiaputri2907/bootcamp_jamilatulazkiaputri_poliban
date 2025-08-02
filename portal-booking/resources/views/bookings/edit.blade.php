<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 800px; }
    </style>
</head>
<body>
    <div class="container mt-5">
        <h1>Edit Booking</h1>

        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <form action="{{ route('bookings.update', ['booking' => $booking->id]) }}" method="POST" class="mt-4">
            @csrf
            @method('PATCH')

            <div class="mb-3">
                <label for="user_name" class="form-label">Nama Pengguna</label>
                {{-- Menggunakan input text yang bisa diedit --}}
                <input type="text" class="form-control" id="user_name" name="user_name" value="{{ old('user_name', $booking->user->name ?? '') }}" required>
                @error('user_name')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="room_id" class="form-label">Pilih Ruangan</label>
                <select class="form-control" id="room_id" name="room_id" required>
                    <option value="">-- Pilih Ruangan --</option>
                    {{-- Iterasi data ruangan dan tampilkan di dropdown --}}
                    @if(count($rooms) > 0)
                        @foreach($rooms as $room)
                            <option value="{{ $room['id'] }}" {{ old('room_id', $booking->room_id) == $room['id'] ? 'selected' : '' }}>
                                {{ $room['name'] }} (Kapasitas: {{ $room['capacity'] }})
                            </option>
                        @endforeach
                    @else
                        <option value="">Tidak ada ruangan tersedia</option>
                    @endif
                </select>
                @error('room_id')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>
            
            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal Mulai</label>
                <input type="datetime-local" class="form-control" id="start_date" name="start_date" value="{{ old('start_date', $booking->start_date ? \Carbon\Carbon::parse($booking->start_date)->format('Y-m-d\TH:i') : '') }}" required>
                @error('start_date')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal Selesai</label>
                <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{ old('end_date', $booking->end_date ? \Carbon\Carbon::parse($booking->end_date)->format('Y-m-d\TH:i') : '') }}" required>
                @error('end_date')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="status" class="form-label">Status</label>
                <select class="form-control" id="status" name="status" required>
                    <option value="pending" {{ old('status', $booking->status) == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="confirmed" {{ old('status', $booking->status) == 'confirmed' ? 'selected' : '' }}>Confirmed</option>
                    <option value="cancelled" {{ old('status', $booking->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                </select>
                @error('status')
                    <div class="text-danger mt-2">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>
</html>
