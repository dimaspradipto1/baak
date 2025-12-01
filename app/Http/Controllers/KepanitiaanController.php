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
        if (request()->ajax()) {
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
                    <a href="' . route('kepanitiaan.edit', $item->id) . '" class="btn btn-warning btn-sm px-3 rounded" title="edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
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

        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/LPJKepanitiaan'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/LPJKepanitiaan/' . $fileName;
        } else {
            $fileUrl = null;
        }

        Kepanitiaan::create([
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'nama_dokumen' => $request->nama_dokumen,
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
        $tahunAkademik = TahunAkademik::all();
        return view('pages.kepanitiaan.edit', compact('kepanitiaan', 'tahunAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */

    public function update(Request $request, Kepanitiaan $kepanitiaan)
    {
        // Check if a new file is uploaded
        if ($request->hasFile('file')) {
            // Generate a new file name and move the uploaded file to storage
            $fileName = time() . '.' . $request->file('file')->extension();
            $path = $request->file('file')->move(public_path('storage/LPJKepanitiaan'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            // Set the file URL to the new file's path
            $fileUrl = 'storage/LPJKepanitiaan/' . $fileName;

            // If there is an old file, delete it from storage
            if ($kepanitiaan->file) {
                $oldFilePath = public_path($kepanitiaan->file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Delete the old file
                }
            }
        } else {
            // If no new file is uploaded, retain the old file's URL
            $fileUrl = $kepanitiaan->file;
        }

        // Update the Kepanitiaan data
        $kepanitiaan->update([
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'nama_dokumen' => $request->nama_dokumen,
            'ketua' => $request->ketua,
            'sekretaris' => $request->sekretaris,
            'prodi' => $request->prodi,
            'file' => $fileUrl,  // Store the new or old file URL
            'users_id' => Auth::id(),
        ]);

        // Success message
        Alert::success('Success', 'Data updated successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('kepanitiaan.index');
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
