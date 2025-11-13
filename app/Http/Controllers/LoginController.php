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

    // public function loginproses(LoginRequest $request)
    // {
    //     $credentials = $request->only('email', 'password');
    
    //     if (Auth::attempt($credentials)) {
    //         Alert::success('Berhasil', 'Login berhasil')->autoclose(2000)->toToast();
    //         return redirect()->route('dashboard');  
    //     }
    
    //     Alert::error('Gagal', 'Email atau password salah')->autoclose(2000)->toToast();
    //     return redirect()->route('login');
    // }

    public function loginproses(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        
        if (Auth::attempt($credentials)) {
            Alert::success('Berhasil', 'Login berhasil')->autoclose(2000)->toToast();
            return redirect()->route('dashboard');  
        }
        
        // Menggunakan withInput untuk mengembalikan data email ke form
        Alert::error('Gagal', 'Email atau password salah')->autoclose(2000)->toToast();
        return redirect()->route('login')->withInput($request->only('email')); // Pastikan email dikirim kembali
    }


    public function logout()
    {
        Auth::logout();
        Alert::success('Berhasil', 'Logout berhasil')->autoclose(2000)->toToast();
        return redirect()->route('login');
    }

    public function passwordemail(Request $request)
    {
        // Validate that the email is required and in the correct format
        $request->validate(['email' => 'required|email']);

        // Attempt to send the password reset link
        $status = Password::sendResetLink(
            $request->only('email')
        );

        // Check if the email was sent successfully
        if ($status === Password::RESET_LINK_SENT) {
            // Show success message if email was sent
            Alert::success('Success', 'Lupa Password Berhasil di Kirim ke Email')->autoclose(3000)->toToast();
        } else {
            // Show error message if email was not sent (e.g., email doesn't exist)
            Alert::error('Error', 'Email Tidak Terdaftar')->autoclose(3000)->toToast();
        }

        // Redirect back to the previous page
        return back();
    }

    public function showUpdatePasswordForm($id)
    {
        $user = User::findOrFail($id);
        return view('auth.reset_password', compact('user'));
    }

    public function updatePassword(Request $request, $id)
    {
        // Validasi password
        $request->validate([
            'password' => 'required|string|min:8|confirmed', // Pastikan password minimal 8 karakter dan terkonfirmasi
        ]);

        $user = User::findOrFail($id);

        // Hash dan update password
        $user->password = Hash::make($request->password);
        $user->save();

        Alert::success('Berhasil', 'Password berhasil diperbarui')->autoclose(2000)->toToast();

        return redirect()->route('user.index');
    }
}
