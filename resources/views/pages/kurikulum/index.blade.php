@extends('layouts.dashboard.template')

@push('script')
    <script>
        var dataTable = $('#crudTable').DataTable({
            ajax: {
                url: '{!! url()->current() !!}', // Menampilkan data dari database
            },
            scrollX: true, 
            columns: [
                {
                    data: 'DT_RowIndex',
                    name: 'DT_RowIndex',
                    width: '5%',
                    class: 'text-center'
                },
                {
                    data: 'tahun',
                    name: 'tahun',
                    width: '20%',
                    class: 'text-center'
                },
                {
                    data: 'nama_kurikulum',
                    name: 'nama_kurikulum',
                    width: '20%',
                    class: 'text-wrap'
                },
                {
                    data: 'prodi',
                    name: 'prodi',
                    width: '20%'
                },
                {
                    data: 'file',
                    name: 'file',
                    width: '20%'
                },
                {
                    data: 'users_id',
                    name: 'users_id',
                    width: '20%'
                },
                {
                    data: 'action',
                    name: 'action',
                    orderable: false,
                    searchable: false,
                    width: '15%'
                }
            ],
        })
    </script>
@endpush

@section('content')
<div class="card">
    <div class="card-header">
        <a href="{{ route('kurikulum.create') }}" class="btn btn-primary rounded btn-sm"><i class="fa-solid fa-plus"></i> Tambah</a>
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
            <table class="table display nowrap rounded table-centered table-striped" id="crudTable" style="width: 100%">
                <thead>
                    <tr>
                        <th>NO</th>
                        <th class="text-center">TAHUN</th>
                        <th>NAMA KURIKULUM</th>
                        <th>PRODI</th>
                        <th>DOKUMEN</th>
                        <th>SUBMITTED BY</th>
                        <th>AKSI</th>
                    </tr>
                </thead>
                <tbody> </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
