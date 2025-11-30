<?php

namespace App\Http\Controllers;

use App\Models\Jenissk;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class JenisskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
         if (request()->ajax()) {
            $query = Jenissk::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '
                        <a href="'.route('jenissk.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                        <form action="'.route('jenissk.destroy', $item->id).'" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.jenissk.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.jenissk.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'nama_jenis_sk' => 'required',
        ]);

        Jenissk::create($request->all());
        Alert::success('success', 'data created successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('jenissk.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Jenissk $jenissk)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Jenissk $jenissk)
    {
        return view('pages.jenissk.edit', compact('jenissk'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Jenissk $jenissk)
    {
        $jenissk->update($request->all());
        Alert::success('success', 'data updated successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('jenissk.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Jenissk $jenissk)
    {
        $jenissk->delete();
        Alert::success('success', 'data deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('jenissk.index');
    }
}
