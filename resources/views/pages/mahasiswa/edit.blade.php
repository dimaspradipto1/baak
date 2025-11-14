@extends('layouts.dashboard.template')

@section('content')
    <div class="row">
        <div class="col-sm-12">
            <!-- Basic Form Inputs card start -->
            <div class="card">
                <div class="card-header">
                    <h5>Form Edit Mahasiswa</h5>
                </div>
                <div class="card-block">
                    <h4 class="sub-title">Form Inputs</h4>
                    <form action="{{ route('mahasiswa.update', $mahasiswa->id) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Nama Mahasiswa</label>
                            <div class="col-sm-10">
                                <select name="users_id" id="users_id" class="form-control rounded" data-live-search="true">
                                    <option selected disabled>{{ $mahasiswa->user->name }}</option>
                                    @foreach ($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Tanggal Lahir</label>
                            <div class="col-sm-10">
                                <input type="date" name="tgl_lahir" value="{{ $mahasiswa->tgl_lahir }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NPM</label>
                            <div class="col-sm-10">
                                <input type="number" name="npm" value="{{ $mahasiswa->npm }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Program Studi</label>
                            <div class="col-sm-10">
                                <input type="text" name="program_studi" value="{{ $mahasiswa->program_studi }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Jenjang Pendidikan</label>
                            <div class="col-sm-10">
                                <input type="text" name="jenjang_pendidikan" value="{{ $mahasiswa->jenjang_pendidikan }}"
                                    class="form-control rounded">
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">Semester</label>
                            <div class="col-sm-10">
                               <select name="semester" id="semester" class="form-control rounded">
                                <option selected disabled>{{ $mahasiswa->semester }}</option>
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
                                <textarea name="alamat" class="form-control rounded">{{ $mahasiswa->alamat }}</textarea>
                            </div>
                        </div>

                        <div class="form-group row">
                            <label class="col-sm-2 col-form-label">NO WA</label>
                            <div class="col-sm-10">
                                <input type="number" name="no_wa" value="{{ $mahasiswa->no_wa }}" class="form-control rounded">
                            </div>
                        </div>

                        <!-- Submit buttons -->
                        <button type="submit" class="btn btn-primary rounded text-uppercase btn-sm">
                            <i class="fa-solid fa-save"></i> Update
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
