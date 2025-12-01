<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pedoman;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use RealRashid\SweetAlert\Facades\Alert;

class PedomanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $pedoman = Pedoman::all();
            return datatables()->of($pedoman)
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
                ->addColumn('action', function ($row) {
                    return '
                    <a href="' . route('pedoman.edit', $row->id) . '" class="btn btn-warning btn-sm px-3 rounded" title="edit">
                        <i class="fa-solid fa-pen-to-square"></i>
                    </a>
                <form action="' . route("pedoman.destroy", $row->id) . '" method="POST" class="d-inline">
                ' . csrf_field() . '
                ' . method_field("DELETE") . '
                <button type="submit" class="btn btn-danger btn-sm px-3 rounded">
                <i class="fa-solid fa-trash-can"></i>
                </button>
                </form>';
                })
                ->rawColumns(['file', 'action', 'users_id'])
                ->make(true);
        }
        return view('pages.pedoman.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.pedoman.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        if ($request->hasFile('file')) {

            $fileName = time() . '.' . $request->file('file')->extension();

            $path = $request->file('file')->move(public_path('storage/pedoman'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            $fileUrl = 'storage/pedoman/' . $fileName;
        } else {
            $fileUrl = null;
        }

        Pedoman::create([
            'nama_pedoman' => $request->nama_pedoman,
            'tahun' => $request->tahun,
            'file'     => $fileUrl,
            'users_id' => Auth::id(),
        ]);

        Alert::success('Success', 'Data created successfully')
            ->autoclose(3000)
            ->toToast();

        return redirect()->route('pedoman.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Pedoman $pedoman)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Pedoman $pedoman)
    {
        $users = User::all();
        return view('pages.pedoman.edit', compact('pedoman', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Pedoman $pedoman)
    {
        // Check if a new file is uploaded
        if ($request->hasFile('file')) {
            // Generate a new file name and move the uploaded file to storage
            $fileName = time() . '.' . $request->file('file')->extension();
            $path = $request->file('file')->move(public_path('storage/pedoman'), $fileName);

            if (!$path) {
                return back()->withErrors(['file' => 'Failed to store the file']);
            }

            // Set the file URL to the new file's path
            $fileUrl = 'storage/pedoman/' . $fileName;

            // If there is an old file, delete it from storage
            if ($pedoman->file) {
                $oldFilePath = public_path($pedoman->file);
                if (file_exists($oldFilePath)) {
                    unlink($oldFilePath); // Delete the old file
                }
            }
        } else {
            // If no new file is uploaded, retain the old file's URL
            $fileUrl = $pedoman->file;
        }

        // Update the Kepanitiaan data
        $pedoman->update([
            'tahun' => $request->tahun,
            'nama_pedoman' => $request->nama_pedoman,
            'file' => $fileUrl,  // Store the new or old file URL
            'users_id' => Auth::id(),
        ]);

        // Success message
        Alert::success('Success', 'Data updated successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('pedoman.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pedoman = Pedoman::findOrFail($id);

        // Hapus file fisik kalau ada
        if ($pedoman->file) {
            $fullPath = public_path($pedoman->file);

            if (File::exists($fullPath)) {
                File::delete($fullPath);
            }
        }

        $pedoman->delete();

        Alert::success('Success', 'Data and file deleted successfully')
            ->autoclose(3000)
            ->toToast()
            ->timerProgressBar();

        return redirect()->route('pedoman.index');
    }
}
