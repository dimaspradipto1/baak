@extends('layouts.dashboard.template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <!-- Basic Form Inputs card start -->
            <div class="card">
                <div class="card-header">
                    <h5>Form SOP Akademik</h5>
                </div>
                <div class="card-block">
                    <h4 class="sub-title">Form Inputs</h4>
                    <form action="{{ route('sopAkademik.update', $sopAkademik->id) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Nama SOP -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama SOP</label>
                            <div class="col-sm-10">
                                <input type="text" name="nama_sop" value="{{ $sopAkademik->nama_sop }}"
                                    class="form-control rounded" required>
                            </div>
                        </div>

                        <!-- File SOP -->
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">File SOP</label>
                            <div class="col-sm-10">
                                <input type="file" name="file" value="{{ $sopAkademik->file }}" class="form-control rounded">
                            </div>
                        </div>

                        <!-- Submit button -->
                        <button type="submit" class="btn btn-primary rounded text-uppercase btn-sm">
                            <i class="fa-solid fa-save"></i> Submit
                        </button>

                        <!-- Back button -->
                        <a href="{{ route('sopAkademik.index') }}" class="btn btn-danger rounded text-uppercase btn-sm">
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
