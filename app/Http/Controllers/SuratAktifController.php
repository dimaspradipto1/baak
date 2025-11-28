<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\User;
use App\Models\Pegawai;
use App\Models\Mahasiswa;
use App\Models\SuratAktif;
use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class SuratAktifController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            if (Auth::user()->is_admin) {
                $suratAktif = SuratAktif::with('users', 'programStudi')->get();
            }

            if (Auth::user()->is_mahasiswa) {
                $suratAktif = SuratAktif::with('users', 'programStudi')
                    ->where('users_id', Auth::user()->id)
                    ->get();
            }

            return DataTables::of($suratAktif)
                ->addIndexColumn()
                ->addColumn('users.name', function ($item) {
                    return $item->users ? $item->users->name : '-';
                })
                ->addColumn('programStudi.program_studi', function ($item) {
                    return $item->programStudi ? $item->programStudi->program_studi : '-';
                })
                ->addColumn('status', function ($item) {
                    return $item->status == 'pending' ? '<span class="badge badge-warning text-white px-3 py-2">Pending</span>' : ($item->status == 'diterima' ? '<span class="badge badge-success text-white px-3 py-2">Diterima</span>' : ($item->status == 'ditolak' ? '<span class="badge badge-danger text-white px-3 py-2">Ditolak</span>' : '-'));
                })
                ->addColumn('action', function ($item) {
                    $actions = '';
                    if (Auth::user()->is_admin) {
                        $actions .= '
                            <a href="' . route('suratAktif.show', $item->id) . '" class="btn btn-sm btn-primary text-white px-3 rounded" title="detail"><i class="fa-solid fa-eye"></i></a> 
                            <a href="' . route('suratAktif.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                            <form action="' . route('suratAktif.destroy', $item->id) . '" method="POST" class="d-inline">
                                ' . csrf_field() . '
                                ' . method_field('delete') . '
                                <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can"></i></button>
                            </form>
                        ';
                    }

                    if ($item->status == 'diterima' && Auth::user()->is_mahasiswa) {
                        $actions .= '
                            <a href="' . route('suratAktif.show', $item->id) . '" class="btn btn-sm btn-primary text-white px-3 rounded" title="print"><i class="fa-solid fa-print"></i></a>
                        ';
                    }

                    return $actions;
                })

                ->rawColumns(['action', 'users.name', 'programStudi.nama_program_studi', 'status'])
                ->make();
        }
        return view('pages.suratAktif.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::where('is_mahasiswa', true)->get();
        $programStudi = ProgramStudi::all();
        return view('pages.suratAktif.create', compact('users', 'programStudi'));
    }

    public function pengajuan()
    {
        $mahasiswa = Mahasiswa::where('users_id', Auth::id())->first();

        if (!$mahasiswa) {
            Alert::error('Error', 'Data mahasiswa tidak ditemukan. Silahkan isi data mahasiswa terlebih dahulu.')->autoclose(10000)->toToast()->timerProgressBar();
            return redirect()->route('mahasiswa.create');
        }

        $lastSurat = SuratAktif::latest('no_surat')->first();
        $noSurat = $lastSurat ? $lastSurat->no_surat + 1 : 1;
        $noSuratFormatted = sprintf("%03d", $noSurat);

        $data = [
            'no_surat' => $noSuratFormatted,
            'users_id' => Auth::id(),
            'program_studi_id' => $mahasiswa->program_studi_id,
            'tempat_lahir' => $mahasiswa->tempat_lahir,
            'tgl_lahir' => $mahasiswa->tgl_lahir,
            'npm' => $mahasiswa->npm,
            'jenjang_pendidikan' => $mahasiswa->jenjang_pendidikan,
            'fakultas' => $mahasiswa->fakultas,
            'status' => 'pending',
            'semester' => $mahasiswa->semester ?? null,
            'status_semester' => $mahasiswa->status_semester ?? null,
            'tahun_akademik' => $mahasiswa->tahun_akademik ?? null,
        ];

        SuratAktif::create($data);

        // Menampilkan pesan sukses
        Alert::success('Success', 'Surat Aktif berhasil dibuat secara otomatis')->autoclose(3000)->toToast()->timerProgressBar();
        // Redirect ke halaman index Surat Aktif
        return redirect()->route('suratAktif.index');
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $lastSurat = SuratAktif::latest('no_surat')->first();  // Ambil data dengan nomor surat terakhir
        $noSurat = $lastSurat ? (int) $lastSurat->no_surat + 1 : 1;
        $noSuratFormatted = sprintf("%03d", $noSurat);
        $users_id = Auth::user()->id;

        $data = [
            'no_surat' => $noSuratFormatted,
            'users_id' => $users_id,
            'program_studi_id' => $request->program_studi_id,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'npm' => $request->npm,
            'jenjang_pendidikan' => $request->jenjang_pendidikan,
            'fakultas' => $request->fakultas,
            'status' => 'pending',
            'semester' => $request->semester ?? null,
            'status_semester' => $request->status_semester ?? null,
            'tahun_akademik' => $request->tahun_akademik ?? null,
        ];

        SuratAktif::create($data);
        Alert::success('Success', 'Data created successfully')->autoclose(3000)->toToast();
        return redirect()->route('suratAktif.index');
    }


    /**
     * Display the specified resource.
     */
    public function getBulanRomawi()
    {
        $bulan = Carbon::now()->format('F Y');

        $petaRomawi = [
            'January' => 'I',
            'February' => 'II',
            'March' => 'III',
            'April' => 'IV',
            'May' => 'V',
            'June' => 'VI',
            'July' => 'VII',
            'August' => 'VIII',
            'September' => 'IX',
            'October' => 'X',
            'November' => 'XI',
            'December' => 'XII',
        ];

        return $petaRomawi[date('F', strtotime($bulan))];
    }

    // Method to display Surat Aktif
    public function show(SuratAktif $suratAktif)
    {
       
        
        $no_surat = $suratAktif->no_surat;
        $program_studi = ProgramStudi::find($suratAktif->program_studi_id)->nama_program_studi;
        $user = User::where('is_approval', 1)->first();
        
        $bulanRomawi = $this->getBulanRomawi();
        
        // Mengirim data ke view
        return view('pages.suratAktif.show', compact('suratAktif', 'no_surat', 'program_studi', 'bulanRomawi', 'user'));
    }

    


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SuratAktif $suratAktif)
    {
        $tahunAkademik = TahunAkademik::all();
        return view('pages.suratAktif.edit', compact('suratAktif', 'tahunAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SuratAktif $suratAktif)
    {
        $suratAktif->update($request->all());
        Alert::success('success', 'data updated successfully')->autoclose(3000)->toToast()->timerProgressBar();
        return redirect()->route('suratAktif.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(SuratAktif $suratAktif)
    {
        $suratAktif->delete();
        Alert::success('success', 'data deleted successfully')->autoclose(3000)->toToast()->timerProgressBar();
        return redirect()->route('suratAktif.index');
    }
}
