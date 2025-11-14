<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Mahasiswa;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            // Memuat data Mahasiswa beserta relasi User
            $query = Mahasiswa::with('user')->get(); 
    
            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('user.name', function ($item) {
                    // Cek apakah relasi user ada
                    return $item->user ? $item->user->name : 'Tidak Ada'; 
                })
                ->addColumn('user.email', function ($item) {
                    // Cek apakah relasi user ada
                    return $item->user ? $item->user->email : 'Tidak Ada'; 
                })
                ->addColumn('program_studi', function ($item) {
                    return $item->program_studi;
                })
                ->addColumn('action', function ($item) {
                    return '
                        <a href="'.route('mahasiswa.show', $item->id).'" class="btn btn-sm btn-primary text-white px-3 rounded" title="detail"><i class="fa-solid fa-eye"></i></a> 
                        <a href="'.route('mahasiswa.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                        <form action="'.route('mahasiswa.destroy', $item->id).'" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                        </form>
                    ';
                })
                ->rawColumns(['action', 'user.name', 'user.email', 'program_studi', 'detail'])
                ->make();
        }
        return view('pages.mahasiswa.index');
    }
    


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('pages.mahasiswa.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'users_id' => $request->users_id,
            'tempat_lahir' => $request->tempat_lahir,
            'tgl_lahir' => $request->tgl_lahir,
            'npm' => $request->npm,
            'program_studi' => $request->program_studi,
            'jenjang_pendidikan' => $request->jenjang_pendidikan,
            'semester' => $request->semester,
            'alamat' => $request->alamat,
            'no_wa' => $request->no_wa,
        ];
        Mahasiswa::create($data);
        Alert::success('success', 'data created successfully')->autoclose(3000)->toToast();
        return redirect()->route('mahasiswa.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Mahasiswa $mahasiswa)
    {
        return view('pages.mahasiswa.show', compact('mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Mahasiswa $mahasiswa)
    {
        $users = User::all();
        return view('pages.mahasiswa.edit', compact('mahasiswa', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Mahasiswa $mahasiswa)
    {
        $mahasiswa->update($request->all());
        Alert::success('success', 'data updated successfully')->autoclose(3000)->toToast();
        return redirect()->route('mahasiswa.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Mahasiswa $mahasiswa)
    {
        $mahasiswa->delete();
        Alert::success('success', 'data deleted successfully')->autoclose(3000)->toToast();
        return redirect()->route('mahasiswa.index');
    }
}
