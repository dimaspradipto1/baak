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
                    return $item->user ? $item->user->name : '-';
                })
                ->addColumn('programStudi.program_studi', function ($item) {
                    return $item->programStudi ? $item->programStudi->program_studi : '-';
                })
                ->addColumn('action', function ($item) {
                    $editButton = '';
                    $deleteButton = '';
                    $showButton = '<a href="' . route('suratAkademik.show', $item->id) . '" class="btn btn-sm btn-dark text-white px-3 mr-2 rounded" title="show"><i class="fa-solid fa-print"></i></a>';

                    if (Auth::user()->is_admin) {
                        $editButton = '<a href="' . route('suratAkademik.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 mr-2 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a>';
                        $deleteButton = '
                        <form action="' . route('suratAkademik.destroy', $item->id) . '" method="POST" class="d-inline">
                            ' . csrf_field() . '
                            ' . method_field('delete') . '
                            <button type="submit" class="btn btn-danger btn-sm px-3 mr-2 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                        </form>
                    ';
                    }

                    return $showButton . $editButton . $deleteButton;
                })

                ->rawColumns(['action', 'users.name', 'programStudi.nama_program_studi'])
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $mahasiswa = Mahasiswa::where('users_id', $request->users_id)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan!');
        }

        if (!$mahasiswa->programStudi) {
            return redirect()->back()->with('error', 'Program studi mahasiswa tidak ditemukan!');
        }

        $data = [
            'users_id' => $request->users_id,
            'program_studi_id' => $mahasiswa->programStudi->id,
            'npm' => $mahasiswa->npm,
            'status_cuti' => 'Belum Pernah Cuti',
            'alamat' => $mahasiswa->alamat,
            'no_wa' => $mahasiswa->no_wa,
            'semester' => $request->semester,
            'permohonan' => $request->permohonan,
            'alasan_cuti' => $request->alasan_cuti,
        ];

        SuratAkademik::create($data);
        Alert::success('success', 'Data berhasil dibuat')->autoclose(3000)->toToast();

        return redirect()->route('suratAkademik.index');
    }

    public function show(SuratAkademik $suratAkademik)
    {
        $mahasiswa = Mahasiswa::where('users_id', $suratAkademik->users_id)->first();

        if (!$mahasiswa) {
            return redirect()->back()->with('error', 'Data mahasiswa tidak ditemukan!');
        }

        $programStudi = ProgramStudi::find($mahasiswa->program_studi_id);
        $fakultas = $mahasiswa->fakultas;
        $user = User::find($suratAkademik->users_id);
        $no_surat = SuratAkademik::count();
        return view('pages.suratAkademik.show', compact('suratAkademik', 'mahasiswa', 'programStudi', 'user', 'no_surat', 'fakultas'));
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
