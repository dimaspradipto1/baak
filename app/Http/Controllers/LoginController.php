<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use RealRashid\SweetAlert\Facades\Alert;

class LoginController extends Controller
{
    public function login()
    {
        return view('layouts.auth.login');
    }

    public function register()
    {
        return view('layouts.auth.register');
    }

    public function registerproses(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'is_mahasiswa' => 1,
        ]);

        Auth::login($user);
        Alert::success('Berhasil', 'Register berhasil')
            ->autoclose(2000)
            ->toToast()
            ->timerProgressBar();
        // return redirect()->route('dashboard');
        return redirect()->route('mahasiswa.create');
    }


    public function loginproses(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            Alert::success('Berhasil', 'Login berhasil')
                ->autoclose(2000)
                ->toToast()
                ->timerProgressBar();
            return redirect()->route('dashboard');  
        }
        
        Alert::error('Gagal', 'Email atau password salah')
            ->autoclose(2000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('login')->withInput($request->only('email'));
    }


    public function logout()
    {
        Auth::logout();
        Alert::success('Berhasil', 'Logout berhasil')->autoclose(2000)->toToast();
        return redirect()->route('login');
    }

    public function passwordemail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $status = Password::sendResetLink(
            $request->only('email')
        );

        if ($status === Password::RESET_LINK_SENT) {
            Alert::success('Success', 'Lupa Password Berhasil di Kirim ke Email')
                ->autoclose(3000)
                ->toToast()
                ->timerProgressBar();
        } else {
            Alert::error('Error', 'Email Tidak Terdaftar')
                ->autoclose(3000)
                ->toToast()
                ->timerProgressBar();
        }

        return back();
    }

    public function showUpdatePasswordForm($id)
    {
        $user = User::findOrFail($id);
        return view('auth.reset_password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        $request->validate([
            'password' => 'required|string|min:8|confirmed',
        ]);

        $user = User::findOrFail($id);

        $user->password = Hash::make($request->password);
        $user->save();

        Alert::success('Berhasil', 'Password berhasil diperbarui')
            ->autoclose(2000)
            ->toToast()
            ->timerProgressBar();
        return redirect()->route('user.index');
    }
}
