@extends('layouts.dashboard.template')

@push('script')
    <script>
        var dataTable = $('#crudTable').DataTable({
            ajax: {
                url: '{!! url()->current() !!}',
            },
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '5%',
                    class: 'text-center'
                },
                {
                    data: 'users.name',  // Nama kolom yang mengakses relasi 'users.name'
                    name: 'users.name',
                    width: '40%'
                },
                {
                    data: 'programStudi.nama_program_studi', 
                    name: 'programStudi.nama_program_studi',
                    width: '30%'
                },
                {
                    data: 'status', 
                    name: 'status',
                    width: '20%'
                },
                {
                    data: 'action', 
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                }
            ]
        })
    </script>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        @if(Auth::user()->is_admin)
            <a href="{{ route('suratAktif.create') }}" class="btn btn-primary rounded btn-sm"><i class="fa-solid fa-plus"></i> Tambah</a>
        @endif

        @if(auth()->user()->is_mahasiswa)
            <!-- Tombol hanya untuk mahasiswa: Pengajuan -->
            <form action="{{ route('suratAktif.pengajuan') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-success rounded btn-sm">
                    <i class="fa-solid fa-plus"></i> Pengajuan
                </button>
            </form>
        @endif

        <div class="card-header-right">
            <ul class="list-unstyled card-option">
                <li><i class="fa fa fa-wrench open-card-option"></i></li>
                <li><i class="fa fa-window-maximize full-card"></i></li>
                <li><i class="fa fa-minus minimize-card"></i></li>
                <li><i class="fa fa-refresh reload-card"></i></li>
                <li><i class="fa fa-trash close-card"></i></li>
            </ul>
        </div>
    </div>
      

    <div class="card-block table-border-style">
        <div class="table-responsive">
            <table class="table display nowrap rounded table-centered table-striped" id="crudTable">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th>NAMA MAHASISWA</th>
                        <th>PROGRAM STUDI</th>
                        <th>STATUS</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody> </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
