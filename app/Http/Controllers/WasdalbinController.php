<?php

namespace App\Http\Controllers;

use App\Models\Wasdalbin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class WasdalbinController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $wasdalbin = Wasdalbin::all();

            return datatables()->of($wasdalbin)
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
                    <form action="' . route('wasdalbin.destroy', $item->id) . '" method="POST" class="d-inline">
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
        return view('pages.wasdalbin.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.wasdalbin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/wasdalbin'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/wasdalbin/' . $fileName;
        } else {
            $fileUrl = null;
        }

        Wasdalbin::create([
            'tahun' => $request->tahun,
            'nama_wasdalbin' => $request->nama_wasdalbin,
            'prodi' => $request->prodi,
            'file'     => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data created successfully')
            ->autoclose(3000)
            ->toToast();

        return redirect()->route('wasdalbin.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Wasdalbin $wasdalbin)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Wasdalbin $wasdalbin)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Wasdalbin $wasdalbin)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $wasdalbin = Wasdalbin::findOrFail($id);

        // Hapus file fisik kalau ada
        if ($wasdalbin->file) {
            $fullPath = public_path($wasdalbin->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $wasdalbin->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('wasdalbin.index');
    }
}
