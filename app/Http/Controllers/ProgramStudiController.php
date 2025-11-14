<?php

namespace App\Http\Controllers;

use App\Models\ProgramStudi;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class ProgramStudiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = ProgramStudi::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('action', function ($item) {
                    return '
                        <a href="'.route('programStudi.edit', $item->id).'" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                        <form action="'.route('programStudi.destroy', $item->id).'" method="POST" class="d-inline">
                        ' . csrf_field() . '
                        ' . method_field('delete') . '
                        <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                        </form>
                    ';
                })
                ->rawColumns(['action'])
                ->make();
        }
        return view('pages.programStudi.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.programStudi.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = [
            'nama_program_studi' => $request->nama_program_studi,
        ];
        ProgramStudi::create($data);
        Alert::success('success', 'data created successfully')->autoclose(3000)->toToast();
        return redirect()->route('programStudi.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(ProgramStudi $programStudi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(ProgramStudi $programStudi)
    {
        return view('pages.programStudi.edit', compact('programStudi'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ProgramStudi $programStudi)
    {
        $programStudi->update($request->all());
        Alert::success('success', 'data updated successfully')->autoclose(3000)->toToast();
        return redirect()->route('programStudi.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ProgramStudi $programStudi)
    {
        $programStudi->delete();
        Alert::success('success', 'data deleted successfully')->autoclose(3000)->toToast();
        return redirect()->route('programStudi.index');
    }
}
