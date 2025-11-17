@extends('layouts.dashboard.template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <!-- Basic Form Inputs card start -->
            <div class="card">
                <div class="card-header">
                    <h5>Form Approval</h5>
                </div>
                <div class="card-block">
                    <h4 class="sub-title">Form Inputs</h4>
                    <form action="{{ route('approval.store') }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Approval</label>
                            <div class="col-sm-10">
                                <select name="users_id" id="users_id" class="form-control rounded" data-live-search="true">
                                    <option value="">Pilih Approval</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jabatan</label>
                            <div class="col-sm-10">
                                <input type="text" name="jabatan" value="{{ old('jabatan') }}"
                                    class="form-control rounded">
                            </div>
                        </div>
                        

                        

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NIDN</label>
                            <div class="col-sm-10">
                                <input type="text" name="nidn" value="{{ old('nidn') }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">URL Surat</label>
                            <div class="col-sm-10">
                                <input type="file" name="url" value="{{ old('url') }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <button type="submit" class="btn btn-primary rounded text-uppercase btn-sm">
                            <i class="fa-solid fa-save"></i> Submit
                        </button>
                        <a href="{{ route('mahasiswa.index') }}" class="btn btn-danger rounded text-uppercase btn-sm">
                            <i class="fa-solid fa-arrow-left"></i> Back
                        </a>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Basic Form Inputs card end -->
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#users_id').select2();
        });
    </script>
@endpush