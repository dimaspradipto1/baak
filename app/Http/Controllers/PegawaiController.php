<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class PegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    if (request()->ajax()) {
        $query = Pegawai::with('user')->get();  // Eager load the user relationship

        return DataTables::of($query)
            ->addIndexColumn()
            ->addColumn('users.name', function ($item) {
                return $item->user ? $item->user->name : '-';  // Pastikan mengakses 'name' dari relasi 'user'
            })
            ->addColumn('jabatan', function ($item) {
                return $item->jabatan;
            })
            ->addColumn('nidn', function ($item) {
                return $item->nidn;
            })
            ->addColumn('url', function ($item) {
                return '<img src="'.asset($item->url).'" alt="TTD" width="100">';
            })
            ->addColumn('action', function ($item) {
                return '
                    <a href="'.route('pegawai.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                    <form action="'.route('pegawai.destroy', $item->id).'" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('delete') . '
                    <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                    </form>
                ';
            })
            ->rawColumns(['action', 'users.name',  'jabatan', 'nidn', 'url'])
            ->make();
    }
    return view('pages.pegawai.index');
}


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $users = User::all();
        return view('pages.pegawai.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'users_id' => 'required|exists:users,id',
            'jabatan' => 'required|string',
            'nidn' => 'required|string',
            'url' => 'required|image|mimes:jpeg,png,jpg,gif,svg', 
        ]);

        if ($request->hasFile('url')) {
            $imageName = time() . '.' . $request->url->extension();
            $path = $request->file('url')->move(public_path('storage/images'), $imageName); 

            if (!$path) {
                return back()->withErrors(['url' => 'Failed to store the image']);
            }

            $imageUrl = 'storage/images/' . $imageName;
        } else {
            $imageUrl = null;
        }

        $data = [
            'users_id' => $request->users_id,
            'jabatan' => $request->jabatan,
            'nidn' => $request->nidn,
            'url' => $imageUrl,
        ];

        Pegawai::create($data);
        Alert::success('Success', 'Data created successfully')->autoclose(3000)->toToast();

        return redirect()->route('pegawai.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pegawai $pegawai)
    {
        return view('pages.pegawai.show', compact('pegawai'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pegawai $pegawai)
    {
        return view('pages.pegawai.edit', compact('pegawai'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $pegawai = Pegawai::findOrFail($id);
        $pegawai->update($request->all());
        Alert::success('success', 'data updated successfully')->autoclose(3000)->toToast();
        return redirect()->route('pegawai.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Pegawai $pegawai)
    {
        $pegawai->delete();
        Alert::success('success', 'data deleted successfully')->autoclose(3000)->toToast();
        return redirect()->route('pegawai.index');
    }
}
