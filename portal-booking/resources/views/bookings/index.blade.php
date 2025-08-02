<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Daftar Booking</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container { max-width: 1200px; }
        .table img { max-width: 100px; height: auto; }
        .actions-header { margin-bottom: 1rem; }
        .actions-cell { white-space: nowrap; }
    </style>
</head>
<body>

    <div class="container mt-5">
        <h1>Daftar Booking Ruangan</h1>

        @if(session('success'))
            <div class="alert alert-success mt-3">{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger mt-3">{{ session('error') }}</div>
        @endif

        <div class="actions-header mt-4">
            <a href="{{ route('bookings.create') }}" class="btn btn-primary">+ Tambah Booking</a>
        </div>

        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nama Pengguna</th>
                    <th>ID Ruangan</th>
                    <th>Tanggal Mulai</th>
                    <th>Tanggal Selesai</th>
                    <th>Status</th>
                    <th>Dibuat</th>
                    <th>Diperbarui</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @if($bookings->isEmpty())
                    <tr>
                        <td colspan="9" class="text-center">Belum ada data booking.</td>
                    </tr>
                @else
                    @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->user->name ?? 'N/A' }}</td>
                        <td>{{ $booking->room_id ?? 'N/A' }}</td>
                        <td>{{ $booking->start_date }}</td>
                        <td>{{ $booking->end_date }}</td>
                        <td>{{ $booking->status }}</td>
                        <td>{{ $booking->created_at }}</td>
                        <td>{{ $booking->updated_at }}</td>
                        <td class="actions-cell">
                            <a href="{{ route('bookings.edit', ['booking' => $booking->id]) }}" class="btn btn-warning btn-sm">Edit</a>
                            <form action="{{ route('bookings.destroy', ['booking' => $booking->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus booking ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                @endif
            </tbody>
        </table>
    </div>

</body>
</html>
