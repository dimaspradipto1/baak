<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kurikulum;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class KurikulumController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if(request()->ajax()){
            $kurikulum = Kurikulum::all();

            return datatables()->of($kurikulum)
            ->addIndexColumn()
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
                    <a href="' . route('kurikulum.edit', $item->id) . '" class="btn btn-warning btn-sm px-3 rounded" title="edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                    <form action="' . route('kurikulum.destroy', $item->id) . '" method="POST" class="d-inline">
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
        return view('pages.kurikulum.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('pages.kurikulum.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/kurikulum'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/kurikulum/' . $fileName;
        } else {
            $fileUrl = null;
        }

        Kurikulum::create([
            'tahun' => $request->tahun,
            'nama_kurikulum' => $request->nama_kurikulum,
            'prodi' => $request->prodi,
            'file'     => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data created successfully')
            ->autoclose(3000)
            ->toToast();

        return redirect()->route('kurikulum.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Kurikulum $kurikulum)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Kurikulum $kurikulum)
    {
        $users = User::all();
        return view('pages.kurikulum.edit', compact('kurikulum', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Kurikulum $kurikulum)
    {
        if ($request->hasFile('file')) {
            $fileName = time() . '.' . $request->file('file')->extension();
            $path = $request->file('file')->move(public_path('storage/kurikulum'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/kurikulum/' . $fileName;

            if ($kurikulum->file) {
                $oldFilePath = public_path($kurikulum->file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath);
                }
            }
        } else {
            $fileUrl = $kurikulum->file;
        }

        $kurikulum->update([
            'tahun' => $request->tahun,
            'nama_kurikulum' => $request->nama_kurikulum,
            'prodi' => $request->prodi,
            'file' => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data updated successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('kurikulum.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kurikulum = Kurikulum::findOrFail($id);

        if ($kurikulum->file) {
            $fullPath = public_path($kurikulum->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $kurikulum->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('kurikulum.index');
    }
}
