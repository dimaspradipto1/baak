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
                    data: 'user.name',
                    name: 'user.name',
                    width: '40%'
                },

                {
                    data: 'user.email',
                    name: 'user.email',
                    width: '30%'
                },
                {
                    data: 'programStudi.program_studi',
                    name: 'programStudi.program_studi',
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

@php
    use App\Models\Mahasiswa;
@endphp


<div class="card">
    <div class="card-header">
        {{-- <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary rounded btn-sm"><i class="fa-solid fa-plus"></i> Tambah</a> --}}

        @if (Auth::user()->is_admin || !Mahasiswa::where('users_id', Auth::id())->exists())
            <a href="{{ route('mahasiswa.create') }}" class="btn btn-primary rounded btn-sm">
                <i class="fa-solid fa-plus"></i> Tambah
            </a>
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
                        <th>EMAIL</th>
                        <th>PROGRAM STUDI</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody> </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
