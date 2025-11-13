<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAkademik;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class TahunAkademikController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = TahunAkademik::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '
                        <a href="'.route('tahunAkademik.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                        <form action="'.route('tahunAkademik.destroy', $item->id).'" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.tahunAkademik.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.tahunAkademik.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'tahun_akademik' => $request->tahun_akademik,
        ];

        TahunAkademik::create($data);
        Alert::success('success', 'data created successfully')->autoclose(3000)->toToast();
        return redirect()->route('tahunAkademik.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(TahunAkademik $tahunAkademik)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(TahunAkademik $tahunAkademik)
    {
        return view('pages.tahunAkademik.edit', compact('tahunAkademik'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, TahunAkademik $tahunAkademik)
    {
        // Tidak perlu lagi mencari $tahunAkademik, karena Laravel sudah otomatis mengikatkan model tersebut
        $tahunAkademik->update([
            'tahun_akademik' => $request->tahun_akademik,
        ]);

        Alert::success('success', 'Data updated successfully')->autoclose(3000)->toToast();
        return redirect()->route('tahunAkademik.index');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(TahunAkademik $tahunAkademik)
    {
        $tahunAkademik->delete();
        Alert::success('success', 'Data deleted successfully')->autoclose(3000)->toToast();
        return redirect()->route('tahunAkademik.index');
    }
}
