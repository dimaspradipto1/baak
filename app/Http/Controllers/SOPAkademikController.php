<?php

namespace App\Http\Controllers;

use Log;
use App\Models\SOPAkademik;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class SOPAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {

            // // LOAD RELASI user SEKALIGUS
            // $sopAkademik = SOPAkademik::with('user');

            // // CEK ROLE USER
            // if (!Auth::user()->is_admin || !Auth::user()->is_staffbaak) {
            //     // kalau bukan admin → filter by user login
            //     $sopAkademik = $sopAkademik->where('users_id', Auth::id());
            // }

            $sopAkademik = SOPAkademik::all();
            return DataTables::of($sopAkademik)
                ->addIndexColumn()
                ->editColumn('users_id', function ($item) {
                    return $item->user ? $item->user->name : '-';
                })
                ->addColumn('file', function ($item) {
                    return '<a href="' . asset($item->file) . '" target="_blank"
                            class="btn btn-sm btn-success text-white px-3 rounded">
                            <i class="fa-solid fa-eye"></i> Lihat File
                        </a>';
                })
                ->addColumn('action', function ($item) {
                    return '
                    <a href="' . route('SOPAkademik.edit', $item->id) . '" class="btn btn-warning btn-sm px-3 rounded" title="edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="' . route('SOPAkademik.destroy', $item->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                ';
                })
                ->rawColumns(['file', 'action', 'users_id'])
                ->make(true);
        }

        return view('pages.SOPAkademik.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.SOPAkademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/SOPAkademik'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/SOPAkademik/' . $fileName;
        } else {
            $fileUrl = null;
        }

        SOPAkademik::create([
            'nama_sop' => $request->nama_sop,
            'file'     => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data created successfully')
            ->autoclose(3000)
            ->toToast();

        return redirect()->route('SOPAkademik.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(SOPAkademik $sOPAkademik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SOPAkademik $SOPAkademik)
    {
        return view('pages.SOPAkademik.edit', compact('SOPAkademik'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SOPAkademik $SOPAkademik)
    {
        // Check if a new file is uploaded
        if ($request->hasFile('file')) {
            // Generate a new file name and move the uploaded file to storage
            $fileName = time() . '.' . $request->file('file')->extension();
            $path = $request->file('file')->move(public_path('storage/SOPAkademik'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            // Set the file URL to the new file's path
            $fileUrl = 'storage/SOPAkademik/' . $fileName;

            // If there is an old file, delete it from storage
            if ($SOPAkademik->file) {
                $oldFilePath = public_path($SOPAkademik->file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Delete the old file
                }
            }
        } else {
            // If no new file is uploaded, retain the old file's URL
            $fileUrl = $SOPAkademik->file;
        }

        // Update the SOP Akademik data
        $SOPAkademik->update([
            'nama_sop' => $request->nama_sop,
            'file'     => $fileUrl,  // Store the new or old file URL
            'users_id' => Auth::id(),
        ]);

        // Success message
        Alert::success('Success', 'Data updated successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('SOPAkademik.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $SOPAkademik = SOPAkademik::findOrFail($id);

        // Hapus file fisik kalau ada
        if ($SOPAkademik->file) {
            $fullPath = public_path($SOPAkademik->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $SOPAkademik->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('SOPAkademik.index');
    }
}
