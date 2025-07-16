<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Providers\RouteServiceProvider;

class RegisterController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('auth.signup');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3|max:255',
            'email' => 'required|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'department' => 'required|string|max:255',
            'password' => 'required|min:7|max:255|confirmed',
            'terms' => 'accepted',
        ], [
            // Nama lengkap
            'name.required' => 'Nama lengkap wajib diisi',
            'name.min' => 'Nama lengkap minimal 3 karakter',
            'name.max' => 'Nama lengkap maksimal 255 karakter',

            // Email
            'email.required' => 'Email wajib diisi',
            'email.email' => 'Format email tidak valid',
            'email.max' => 'Email maksimal 255 karakter',
            'email.unique' => 'Email sudah terdaftar, silakan gunakan email lain',

            // Nomor telepon
            'phone.required' => 'Nomor telepon wajib diisi',
            'phone.string' => 'Nomor telepon harus berupa teks',
            'phone.max' => 'Nomor telepon maksimal 20 karakter',

            // Departemen
            'department.required' => 'Seksi/Departemen wajib dipilih',
            'department.string' => 'Seksi/Departemen harus berupa teks',
            'department.max' => 'Seksi/Departemen maksimal 255 karakter',

            // Password
            'password.required' => 'Password wajib diisi',
            'password.min' => 'Password minimal 7 karakter',
            'password.max' => 'Password maksimal 255 karakter',
            'password.confirmed' => 'Konfirmasi password tidak cocok',

            // Syarat dan ketentuan
            'terms.accepted' => 'Anda harus menyetujui syarat dan ketentuan'
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'department' => $request->department,
            'role' => 'unit_pengelola', // Set role sebagai unit pengelola secara default
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect(RouteServiceProvider::HOME);
    }
}
