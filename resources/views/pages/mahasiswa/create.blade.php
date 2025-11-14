@extends('layouts.dashboard.template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <!-- Basic Form Inputs card start -->
            <div class="card">
                <div class="card-header">
                    <h5>Form Mahasiswa</h5>
                </div>
                <div class="card-block">
                    <h4 class="sub-title">Form Inputs</h4>
                    <form action="{{ route('mahasiswa.store') }}" method="POST">
                        @csrf
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Mahasiswa</label>
                            <div class="col-sm-10">
                                <select name="users_id" id="users_id" class="form-control rounded" data-live-search="true">
                                    <option value="">Pilih Mahasiswa</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tempat Lahir</label>
                            <div class="col-sm-10">
                                <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" name="tgl_lahir" value="{{ old('tgl_lahir') }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NPM</label>
                            <div class="col-sm-10">
                                <input type="number" name="npm" value="{{ old('npm') }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Program Studi</label>
                            <div class="col-sm-10">
                                <select name="program_studi_id" id="program_studi_id" class="form-control rounded" data-live-search="true">
                                    <option value="">Pilih Program Studi</option>
                                    @foreach ($programStudi as $programStudi)
                                        <option value="{{ $programStudi->id }}">{{ $programStudi->nama_program_studi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenjang Pendidikan</label>
                            <div class="col-sm-10">
                                <input type="text" name="jenjang_pendidikan" value="{{ old('jenjang_pendidikan') }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Semester</label>
                            <div class="col-sm-10">
                               <select name="semester" id="semester" class="form-control rounded">
                                <option selected disabled>Pilih Semester</option>
                                <option disabled>=========================================</option>
                                <option value="I (Satu)">I (Satu)</option>
                                <option value="II (Dua)">II (Dua)</option>
                                <option value="III (Tiga)">III (Tiga)</option>
                                <option value="IV (Empat)">IV (Empat)</option>
                                <option value="V (Lima)">V (Lima)</option>
                                <option value="VI (Enam)">VI (Enam)</option>
                                <option value="VII (Tujuh)">VII (Tujuh)</option>
                                <option value="VIII (Delapan)">VIII (Delapan)</option>
                                <option value="IX (Sembilan)">IX (Sembilan)</option>
                                <option value="X (Sepuluh)">X (Sepuluh)</option>
                                <option value="XI (Sebelas)">XI (Sebelas)</option>
                                <option value="XII (Dua Belas)">XII (Dua Belas)</option>
                               </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Alamat</label>
                            <div class="col-sm-10">
                                <textarea name="alamat" class="form-control rounded">{{ old('alamat') }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NO WA</label>
                            <div class="col-sm-10">
                                <input type="number" name="no_wa" value="{{ old('no_wa') }}"
                                    class="form-control rounded" placeholder="Masukkan no WA">
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