<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        if (request()->ajax()) {
            $query = User::query();

            return DataTables::of($query)
                ->addIndexColumn()
                ->addColumn('name', function ($item) {
                    return $item->name;
                })
                ->addColumn('email', function ($item) {
                    return $item->email;
                })
                ->addColumn('status', function ($item) {
                    $statuses = [];

                    // Cek setiap status dan tambahkan ke array jika statusnya aktif
                    if ($item->is_admin) {
                        $statuses[] = 'Admin';
                    }
                    if ($item->is_staffbaak) {
                        $statuses[] = 'Staff BAAK';
                    }
                    if ($item->is_mahasiswa) {
                        $statuses[] = 'Mahasiswa';
                    }
                    if ($item->is_tata_usaha) {
                        $statuses[] = 'Tata Usaha';
                    }
                    if ($item->is_approval) {
                        $statuses[] = 'Approval';
                    }

                    // Gabungkan semua status yang ada, dipisahkan dengan koma
                    return implode(', ', $statuses);
                })
                ->addColumn('action', function ($item) {
                    return '
                    <a href="' . route('users.updatePassword', $item->id) . '" class="btn btn-sm btn-primary text-white px-3" title="update password"><i class="fa-solid fa-key"></i></a>
                    <a href="' . route('users.edit', $item->id) . '" class="btn btn-sm btn-warning text-white px-3 rounded" title="edit"><i class="fa-solid fa-pen-to-square"></i></a> 
                    <form action="' . route('users.destroy', $item->id) . '" method="POST" class="d-inline">
                    ' . csrf_field() . '
                    ' . method_field('delete') . '
                    <button type="submit" class="btn btn-danger btn-sm px-3 rounded" title="hapus"><i class="fa-solid fa-trash-can" ></i></button>
                    </form>
                ';
                })
                ->rawColumns(['action', 'status'])
                ->make();
        }
        return view('pages.users.index');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('pages.users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required',
        ]);

        $data = [
            'name'           => $request->name,
            'email'          => $request->email,
            'password'       => Hash::make($request->password),
            'remember_token' => Str::random(60),

            // role flags (boleh lebih dari satu, sama seperti update)
            'is_admin'      => $request->has('is_admin') ? 1 : 0,
            'is_mahasiswa'  => $request->has('is_mahasiswa') ? 1 : 0,
            'is_tata_usaha' => $request->has('is_tata_usaha') ? 1 : 0,
            'is_approval'   => $request->has('is_approval') ? 1 : 0,
            'is_staffbaak'  => $request->has('is_staffbaak') ? 1 : 0,
        ];

        User::create($data);

        Alert::success('success', 'user created successfully')
            ->autoclose(2000)->toToast();

        return redirect()->route('users.index');
    }


    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /** 
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.edit', compact('user'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = User::findOrFail($id);

        $user->update([
            'is_admin' => $request->has('is_admin') ? 1 : 0,
            'is_mahasiswa' => $request->has('is_mahasiswa') ? 1 : 0,
            'is_tata_usaha' => $request->has('is_tata_usaha') ? 1 : 0,
            'is_approval' => $request->has('is_approval') ? 1 : 0,
        ]);

        $updateData = [
            'name' => $request->name ?? '',
            'email' => $request->email ?? '',
        ];

        if ($request->has('password')) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        Alert::success('success', 'data updated successfully')->autoclose(2000)->toToast();
        return redirect(route('users.index'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = User::findOrFail($id);
        $user->delete();
        Alert::success('success', 'delete successfully')->autoclose(3000)->toToast();
        return redirect()->route('users.index');
    }

    public function showUpdatePasswordForm(string $id)
    {
        $user = User::findOrFail($id);
        return view('pages.users.updatePassword', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $user->password = Hash::make($request->password);
        $user->save();
        Alert::success('success', 'password updated successfully')->autoclose(2000)->toToast();
        return redirect()->route('users.index');
    }
}
