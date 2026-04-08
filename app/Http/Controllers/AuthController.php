<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pelanggan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            $user = Auth::user();

            if (!$user->is_active) {
                Auth::logout();
                return back()->withErrors([
                    'email' => 'Akun tidak aktif',
                ]);
            }

            $user->update([
                'last_login' => now(),
                'last_login_ip' => $request->ip()
            ]);

            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            }

            return redirect()->route('homepage');
        }

        return back()->withErrors([
            'email' => 'Email atau password salah',
        ]);
    }

    public function showRegister()
    {
        return view('auth.registrasi');
    }

    public function register(Request $request)
{
    $validated = $request->validate([
        'nama_lengkap'   => 'required|string|max:255',
        'email'          => 'required|email|unique:users,email',
        'password'       => 'required|min:8|confirmed',
        'alamat_lengkap' => 'required|string',
        'kecamatan'      => 'required|string',
        'kelurahan'      => 'required|string',
        'no_telepon'     => 'required|string',
    ], [
        'nama_lengkap.required' => 'Nama lengkap wajib diisi',
        'email.required' => 'Email wajib diisi',
        'email.email' => 'Format email tidak valid',
        'email.unique' => 'Email sudah terdaftar',
        'password.required' => 'Password wajib diisi',
        'password.min' => 'Password minimal 8 karakter',
        'password.confirmed' => 'Konfirmasi password tidak cocok',
        'alamat_lengkap.required' => 'Alamat lengkap wajib diisi',
        'kecamatan.required' => 'Kecamatan wajib diisi',
        'kelurahan.required' => 'Kelurahan wajib diisi',
        'no_telepon.required' => 'Nomor telepon wajib diisi',
    ]);

    DB::beginTransaction();

    try {
        // Generate nomor pelanggan
        $noPelanggan = $this->generateNoPelanggan();

        // Create user
        $user = User::create([
            'nama_lengkap' => $validated['nama_lengkap'],
            'email'        => $validated['email'],
            'password'     => Hash::make($validated['password']),
            'no_telepon'   => $validated['no_telepon'],
            'role'         => 'pelanggan',
            'is_active'    => true,
        ]);

        // Create pelanggan
        $pelanggan = Pelanggan::create([
            'user_id'          => $user->id,
            'no_pelanggan'     => $noPelanggan,
            'nama_pelanggan'   => $validated['nama_lengkap'],
            'alamat_lengkap'   => $validated['alamat_lengkap'],
            'kecamatan'        => $validated['kecamatan'],
            'kelurahan'        => $validated['kelurahan'],
            'no_telepon'       => $validated['no_telepon'],
            'email'            => $validated['email'],
            'status_pelanggan' => 'aktif',
        ]);

        DB::commit();

        Auth::login($user);
        
        return redirect()->route('registrasi')->with([
            'show_modal' => true,
            'nomor_pelanggan' => $noPelanggan,
            'nama_pelanggan' => $validated['nama_lengkap']
        ]);
        
    } catch (\Exception $e) {
        DB::rollBack();
        
        return back()
            ->withErrors(['error' => 'Terjadi kesalahan: ' . $e->getMessage()])
            ->withInput();
    }
}

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('success', 'Berhasil logout');
    }

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    public function sendResetLink(Request $request)
    {
        return back()->with('success', 'Link reset password dikirim');
    }

    private function generateNoPelanggan()
    {
        $prefix = 'PEL';
        $tahun = date('Y');
        $bulan = date('m');

        $lastNumber = Pelanggan::whereYear('created_at', $tahun)
            ->whereMonth('created_at', $bulan)
            ->count();

        $urutan = str_pad($lastNumber + 1, 4, '0', STR_PAD_LEFT);

        return "{$prefix}/{$tahun}{$bulan}/{$urutan}";
    }

public function updateProfile(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
    ]);

    $user = Auth::user();
    $user->name = $request->name;

    if ($request->hasFile('avatar')){
        if ($user->avatar && Storage::exists('public/' . $user->avatar)) {
            Storage::delete('public/' . $user->avatar);
        }

        $avatarPath = $request->file('avatar')->store('avatars', 'public');
        $user->avatar = $avatarPath;
    }
    $user->save();
    return redirect()->back()->with('success', 'Profile berhasil diubah');
}
}