<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\SuratAkademik;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class SuratAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            // $suratAkademik = SuratAkademik::with('users', 'programStudi')->get();
            if (Auth::user()->is_admin) {
                $suratAkademik = SuratAkademik::with('users', 'programStudi')->get();
            }

            if (Auth::user()->is_mahasiswa) {
                $suratAkademik = SuratAkademik::with('users', 'programStudi')
                    ->where('users_id', Auth::user()->id)
                    ->get();
            }
            return DataTables::of($suratAkademik)
                ->addIndexColumn()
                ->addColumn('users.name', function ($item) {
                    return $item->users ? $item->users->name : '-';
                })
                ->addColumn('programStudi.nama_program_studi', function ($item) {
                    return $item->programStudi ? $item->programStudi->nama_program_studi : '-';
                })
                ->addColumn('status', function ($item) {
                    return $item->status == 'pending' ? '<span class="badge badge-warning text-white px-3 py-2">Pending</span>' : ($item->status == 'diterima' ? '<span class="badge badge-success text-white px-3 py-2">Diterima</span>' : ($item->status == 'ditolak' ? '<span class="badge badge-danger text-white px-3 py-2">Ditolak</span>' : '-'));
                })
                ->addColumn('action', function ($item) {
                    return '
                        <a href="'.route('suratAkademik.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                        <form action="'.route('suratAkademik.destroy', $item->id).'" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                        </form>
                    ';
                })
                ->rawColumns(['action', 'users.name', 'programStudi.nama_program_studi', 'status'])
                ->make(true);
        }
        return view('pages.suratAkademik.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_mahasiswa', true)->get();
        $programStudi = ProgramStudi::all();
        return view('pages.suratAkademik.create', compact('users', 'programStudi'));
    }

    public function pengajuan()
    {
        // Ambil data mahasiswa berdasarkan user yang sedang login
        $mahasiswa = Mahasiswa::where('users_id', Auth::id())->first();

        // Jika data mahasiswa tidak ditemukan, redirect ke halaman mahasiswa.index atau halaman error
        if (!$mahasiswa) {
            Alert::error('Error', 'Data mahasiswa tidak ditemukan. Silahkan isi data mahasiswa terlebih dahulu.')->autoclose(10000)->toToast();
            return redirect()->route('mahasiswa.create');
        }

       

        // Menyusun data untuk Surat Akademik
        $data = [
            'users_id' => Auth::id(),
            'program_studi_id' => $mahasiswa->program_studi_id,
            'fakultas' => $mahasiswa->fakultas,
            'npm' => $mahasiswa->npm,
            'angkatan_tahun' => $mahasiswa->angkatan_tahun,
            'semester' => $mahasiswa->semester,
            'belum_sudah_cuti' => $mahasiswa->belum_sudah_cuti,
            'alamat' => $mahasiswa->alamat,
            'no_wa' => $mahasiswa->no_wa,
            'permohonan' => $mahasiswa->permohonan,
            'alasan_cuti' => $mahasiswa->alasan_cuti,
            'tahun_akademik' => $mahasiswa->tahun_akademik,
        ];

        // Menyimpan data Surat Akademik ke database
        SuratAkademik::create($data);

        // Menampilkan pesan sukses
        Alert::success('Success', 'Surat Akademik berhasil dibuat secara otomatis')->autoclose(3000)->toToast();

        // Redirect ke halaman index Surat Aktif
        return redirect()->route('suratAkademik.index');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'users_id' => $request->users_id,
            'program_studi_id' => $request->program_studi_id,
            'fakultas' => $request->fakultas,
            'npm' => $request->npm,
            'angkatan_tahun' => $request->angkatan_tahun,
            'semester' => $request->semester,
            'belum_sudah_cuti' => $request->belum_sudah_cuti,
        ];
        SuratAkademik::create($data);
        Alert::success('success', 'data created successfully')->autoclose(3000)->toToast();
        return redirect()->route('suratAkademik.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SuratAkademik $suratAkademik)
    {
        return view('pages.suratAkademik.show', compact('suratAkademik'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratAkademik $suratAkademik)
    {
        return view('pages.suratAkademik.edit', compact('suratAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratAkademik $suratAkademik)
    {
        $suratAkademik->update($request->all());
        Alert::success('success', 'data updated successfully')->autoclose(3000)->toToast();
        return redirect()->route('suratAkademik.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratAkademik $suratAkademik)
    {
        $suratAkademik->delete();
        Alert::success('success', 'data deleted successfully')->autoclose(3000)->toToast();
        return redirect()->route('suratAkademik.index');
    }
}
