<?php

namespace App\Http\Controllers;

use App\Models\Kepanitiaan;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class KepanitiaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $kepanitiaan = Kepanitiaan::with('tahunAkademik')->get();
            return datatables()->of($kepanitiaan)
            ->addIndexColumn()
            ->editColumn('tahun_akademik_id', function ($item) {
                return $item->tahunAkademik->tahun_akademik;
            })
            ->editColumn('users_id', function ($item) {
                    return $item->user ? $item->user->name : '-';
            })
           ->addColumn('file', function ($item) {
                    return '<a href="' . asset($item->file) . '" target="_blank"
                            class="btn btn-sm btn-success text-white px-3 rounded">
                            <i class="fa-solid fa-eye"></i> Lihat Dokumen
                        </a>';
                })
            ->addColumn('action', function ($item) {
                return '
                    <form action="' . route('kepanitiaan.destroy', $item->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['action', 'file', 'users_id'])
            ->make(true);
        }
        return view('pages.kepanitiaan.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAkademik = TahunAkademik::all();
        return view('pages.kepanitiaan.create', compact('tahunAkademik'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'tahun_akademik_id' => 'required',
            'ketua' => 'required',
            'sekretaris' => 'required',
            'prodi' => 'required',
            'file'     => 'required|file',
        ]);

        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/SKKepanitiaan'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/SKKepanitiaan/' . $fileName;
        } else {
            $fileUrl = null;
        }

        Kepanitiaan::create([
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'ketua' => $request->ketua,
            'sekretaris' => $request->sekretaris,
            'prodi' => $request->prodi,
            'file'     => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data created successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('kepanitiaan.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kepanitiaan $kepanitiaan)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kepanitiaan $kepanitiaan)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kepanitiaan $kepanitiaan)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kepanitiaan = Kepanitiaan::findOrFail($id);

        // Hapus file fisik kalau ada
        if ($kepanitiaan->file) {
            $fullPath = public_path($kepanitiaan->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $kepanitiaan->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('kepanitiaan.index');
    }
}
