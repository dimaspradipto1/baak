<?php

namespace App\Http\Controllers;

use App\Models\SK;
use App\Models\User;
use App\Models\Jenissk;
use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class SKController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if(request()->ajax()){
            $sk = SK::with('tahunAkademik')->get();

            return datatables()->of($sk)
            ->addIndexColumn()
            ->editColumn('tahun_akademik_id', function ($item) {
                return $item->tahunAkademik->tahun_akademik;
            })
            ->editColumn('users_id', function ($item) {
                    return $item->user ? $item->user->name : '-';
            })
            ->editColumn('jenissk_id', function ($item) {
                return $item->jenissk ? $item->jenissk->nama_jenis_sk : '-';
            })
           ->addColumn('file', function ($item) {
                    return '<a href="' . asset($item->file) . '" target="_blank"
                            class="btn btn-sm btn-success text-white px-3 rounded">
                            <i class="fa-solid fa-eye"></i> Lihat Dokumen
                        </a>';
                })
            ->addColumn('action', function ($item) {
                return '
                    <form action="' . route('sk.destroy', $item->id) . '" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus">
                            <i class="fa-solid fa-trash-can"></i>
                        </button>
                    </form>
                ';
            })
            ->rawColumns(['action', 'file', 'users_id', 'tahun_akademik_id', 'jenissk_id'])
            ->make(true);
        }
        return view('pages.sk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $tahunAkademik = TahunAkademik::all();
        $users = User::all();
        $jenissks = Jenissk::all();
        return view('pages.sk.create', compact('tahunAkademik', 'users', 'jenissks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/sk'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/sk/' . $fileName;
        } else {
            $fileUrl = null;
        }

        SK::create([
            'tahun_akademik_id' => $request->tahun_akademik_id,
            'jenissk_id' => $request->jenissk_id,
            'nama_sk' => $request->nama_sk,
            'nomor_sk' => $request->nomor_sk,
            'prodi' => $request->prodi,
            'file'     => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data created successfully')
            ->autoclose(3000)
            ->toToast();

        return redirect()->route('sk.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(SK $sK)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(SK $sK)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, SK $sK)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $sk = SK::findOrFail($id);

        if ($sk->file) {
            $fullPath = public_path($sk->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $sk->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('sk.index');
    }
}
