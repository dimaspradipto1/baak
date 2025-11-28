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

        // LOAD RELASI user SEKALIGUS
        $sopAkademik = SOPAkademik::with('user');

        // CEK ROLE USER
        if (!Auth::user()->is_admin) {
            // kalau bukan admin → filter by user login
            $sopAkademik = $sopAkademik->where('users_id', Auth::id());
        }

        return DataTables::of($sopAkademik)
            ->addIndexColumn()

            // ubah kolom users_id jadi nama user
            ->editColumn('users_id', function ($item) {
                return $item->user ? $item->user->name : '-';
            })

            ->addColumn('file', function ($item) {
                return '<a href="'.asset($item->file).'" target="_blank"
                            class="btn btn-sm btn-success text-white px-3 rounded">
                            <i class="fa-solid fa-eye"></i> Lihat File
                        </a>';
            })

            ->addColumn('action', function ($item) {
                return '
                    <form action="'.route('sopAkademik.destroy', $item->id).'" method="POST" class="d-inline">
                        '.csrf_field().'
                        '.method_field('delete').'
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                ';
            })

            // hanya file & action yang HTML
            ->rawColumns(['file', 'action'])

            ->make(true);
    }

    return view('pages.sopAkademik.index');
}

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.sopAkademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
        'nama_sop' => 'required|string',
        'file'     => 'required|file',
    ]);

    if ($request->hasFile('file')) {

        $imageName = time() . '.' . $request->file('file')->extension();

        $path = $request->file('file')->move(public_path('storage/sopAkademik'), $imageName);

        if (!$path) {
            return back()->withErrors(['file' => 'Failed to store the file']);
        }

        $imageUrl = 'storage/sopAkademik/' . $imageName;

    } else {
        $imageUrl = null;
    }

    SOPAkademik::create([
        'nama_sop' => $request->nama_sop,
        'file'     => $imageUrl,
        'users_id' => Auth::id(),
    ]);

    Alert::success('Success', 'Data created successfully')
        ->autoclose(3000)
        ->toToast();

    return redirect()->route('sopAkademik.index');
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
    public function edit(SOPAkademik $sOPAkademik)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SOPAkademik $sOPAkademik)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sopAkademik = SOPAkademik::findOrFail($id);

        // Hapus file fisik kalau ada
        if ($sopAkademik->file) {
            $fullPath = public_path($sopAkademik->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $sopAkademik->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('sopAkademik.index');
    }
}
