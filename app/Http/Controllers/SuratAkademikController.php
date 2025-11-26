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
        // Cek apakah user adalah admin atau mahasiswa
        if (Auth::user()->is_admin) {
            $suratAkademik = SuratAkademik::with('user', 'programStudi')->get();
        } elseif (Auth::user()->is_mahasiswa) {
            $suratAkademik = SuratAkademik::with('user', 'programStudi')
                ->where('users_id', Auth::user()->id)
                ->get();
        }

        return DataTables::of($suratAkademik)
            ->addIndexColumn()
            ->addColumn('users.name', function ($item) {
                return $item->user ? $item->user->name : '-'; // Pastikan relasi 'user' digunakan dengan benar
            })
            ->addColumn('programStudi.program_studi', function ($item) {
                return $item->programStudi ? $item->programStudi->program_studi : '-'; // Pastikan relasi 'programStudi' digunakan dengan benar
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
            ->rawColumns(['action', 'users.name', 'programStudi.nama_program_studi']) // Render kolom 'action', 'users.name', 'programStudi.nama_program_studi'
            ->make(true); // Mengembalikan data dalam format JSON
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

    // public function pengajuan()
    // {
    //     // Ambil data mahasiswa berdasarkan user yang sedang login
    //     $mahasiswa = Mahasiswa::where('users_id', Auth::id())->first();

    //     // Jika data mahasiswa tidak ditemukan, redirect ke halaman mahasiswa.index atau halaman error
    //     if (!$mahasiswa) {
    //         Alert::error('Error', 'Data mahasiswa tidak ditemukan. Silahkan isi data mahasiswa terlebih dahulu.')->autoclose(10000)->toToast();
    //         return redirect()->route('mahasiswa.create');
    //     }
    //     // Menyusun data untuk Surat Akademik
    //     $data = [
    //         'users_id' => Auth::id(),
    //         'program_studi_id' => $mahasiswa->program_studi_id,
    //         'fakultas' => $mahasiswa->fakultas,
    //         'npm' => $mahasiswa->npm,
    //         'angkatan_tahun' => $mahasiswa->angkatan_tahun,
    //         'semester' => $mahasiswa->semester,
    //         'belum_sudah_cuti' => $mahasiswa->belum_sudah_cuti,
    //         'alamat' => $mahasiswa->alamat,
    //         'no_wa' => $mahasiswa->no_wa,
    //         'permohonan' => $mahasiswa->permohonan,
    //         'alasan_cuti' => $mahasiswa->alasan_cuti,
    //         'tahun_akademik' => $mahasiswa->tahun_akademik,
    //     ];

    //     // Menyimpan data Surat Akademik ke database
    //     SuratAkademik::create($data);

    //     // Menampilkan pesan sukses
    //     Alert::success('Success', 'Surat Akademik berhasil dibuat secara otomatis')->autoclose(3000)->toToast();

    //     // Redirect ke halaman index Surat Aktif
    //     return redirect()->route('suratAkademik.index');
    // }

    /**
     * Store a newly created resource in storage.
     */
   public function store(Request $request)
{
    // Mencari data mahasiswa berdasarkan users_id
    $mahasiswa = Mahasiswa::where('users_id', $request->users_id)->first();

    // Memeriksa apakah data mahasiswa ditemukan
    if (!$mahasiswa) {
        return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan!');
    }

    // Memeriksa apakah program studi mahasiswa ditemukan
    if (!$mahasiswa->programStudi) {
        return redirect()->back()->with('error', 'Program studi mahasiswa tidak ditemukan!');
    }

    // Menyiapkan data untuk disimpan
    $data = [
        'users_id' => $request->users_id,
        'program_studi_id' => $mahasiswa->programStudi->id, // Mengambil program studi ID dari relasi
        'npm' => $mahasiswa->npm, // Mengambil NIM
        'status_cuti' => 'Belum Pernah Cuti', // Default jika tidak ada status
        'alamat' => $mahasiswa->alamat, // Mengambil alamat dari Mahasiswa
        'no_wa' => $mahasiswa->no_wa, // Mengambil no_wa dari Mahasiswa
        'semester' => $request->semester,
        'permohonan' => $request->permohonan,
        'alasan_cuti' => $request->alasan_cuti,
    ];

    // Menyimpan data SuratAkademik
    SuratAkademik::create($data);

    // Menampilkan alert sukses
    Alert::success('success', 'Data berhasil dibuat')->autoclose(3000)->toToast();

    // Mengarahkan kembali ke halaman daftar SuratAkademik
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
