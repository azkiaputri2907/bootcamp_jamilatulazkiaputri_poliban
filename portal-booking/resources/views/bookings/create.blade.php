<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Booking Baru</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container mt-5">
        <h1>Buat Booking Baru</h1>
        <hr>

        @if(session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form action="{{ route('bookings.store') }}" method="POST">
            @csrf

            {{-- Kolom nama_pengguna tidak diperlukan karena user_id diambil dari Auth::id() di controller --}}
            <div class="mb-3">
                <label for="nama_pengguna" class="form-label">Nama Pengguna</label>
                <input type="text" class="form-control @error('nama_pengguna') is-invalid @enderror" id="nama_pengguna" name="nama_pengguna" value="{{ old('nama_pengguna') }}">
                @error('nama_pengguna')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div> 

            <div class="mb-3">
                <label for="room_id" class="form-label">Pilih Ruangan</label>
                <select class="form-select @error('room_id') is-invalid @enderror" id="room_id" name="room_id">
                    <option value="">-- Pilih Ruangan --</option>
                    {{-- Pastikan data rooms tersedia dan benar --}}
                    @foreach($rooms as $room)
                        <option value="{{ $room['id'] }}" {{ old('room_id') == $room['id'] ? 'selected' : '' }}>
                            {{ $room['name'] }} (Kapasitas: {{ $room['capacity'] }})
                        </option>
                    @endforeach
                </select>
                @error('room_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="start_date" class="form-label">Tanggal dan Waktu Mulai</label>
                <input type="datetime-local" class="form-control @error('start_date') is-invalid @enderror" id="start_date" name="start_date" value="{{ old('start_date') }}">
                @error('start_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="end_date" class="form-label">Tanggal dan Waktu Selesai</label>
                <input type="datetime-local" class="form-control @error('end_date') is-invalid @enderror" id="end_date" name="end_date" value="{{ old('end_date') }}">
                @error('end_date')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Buat Booking</button>
            <a href="{{ route('bookings.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>

</body>
</html>
