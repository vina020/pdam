<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class PengaturanController extends Controller
{
    /**
     * admin
     */
    public function index() {
        return view('admin.pengaturan');
    }

    /**
     * update profile admin
     */
    public function updateProfile(Request $request) {
    try {
        // Log request untuk debug
        \Log::info('Update Profile Request:', $request->all());
        
        $validator = Validator::make($request->all(), [
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'no_telepon' => 'nullable|string|max:20',
            'jabatan' => 'nullable|string|max:100',
            'alamat' => 'nullable|string',
        ]);
        
        if ($validator->fails()) {
            \Log::warning('Validation failed:', $validator->errors()->toArray());
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first(),
                'errors' => $validator->errors()
            ], 422);
        }

        $user = Auth::user();
        
        if (!$user) {
            \Log::error('User not authenticated');
            return response()->json([
                'success' => false,
                'message' => 'User tidak ditemukan'
            ], 401);
        }

        \Log::info('Updating user:', ['id' => $user->id]);

        $updateData = [
            'nama_lengkap' => $request->nama_lengkap,
            'email' => $request->email,
            'alamat' => $request->alamat,
        ];

        if ($request->filled('no_telepon')) {
            $updateData['no_telepon'] = $request->no_telepon;
            $updateData['telepon'] = $request->no_telepon;
        }

        if ($request->filled('jabatan')) {
            $updateData['jabatan'] = $request->jabatan;
        }

        \Log::info('Update data:', $updateData);

        $user->update($updateData);

        \Log::info('User updated successfully');

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui'
        ]);

    } catch (\Exception $e) {
        \Log::error('Update Profile Error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            'error' => config('app.debug') ? $e->getTraceAsString() : null
        ], 500);
    }
}

    /**
 * upload foto
 */
public function uploadFoto(Request $request) {
    $validator = Validator::make($request->all(), [
        'foto_profil' => 'required|image|mimes:jpg,jpeg,png|max:2048', // UBAH dari 'foto' ke 'foto_profil'
    ]);

    if ($validator->fails()) {
        return response()->json([
            'success' => false,
            'message' => $validator->errors()->first()
        ], 422);
    }
    
    try {
        $user = Auth::user();

        // Delete old photo if exists
        if ($user->foto_profil && Storage::disk('public')->exists($user->foto_profil)) {
            Storage::disk('public')->delete($user->foto_profil);
        }

        // Store new photo
        $file = $request->file('foto_profil');
        $filename = time() . '_' . $file->getClientOriginalName();
        $path = $file->storeAs('profile-photos', $filename, 'public');

        $user->update([
            'foto_profil' => $path
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload',
            'foto_url' => asset('storage/' . $path)
        ]);
    } catch (\Exception $e) {
        \Log::error('Upload Foto Error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    /**
     * update password
     */
    public function updatePassword(Request $request) {
        $validator = Validator::make($request->all(), [
            'password_lama' => 'required',
            'password_baru' => 'required|min:8',
            'password_konfirmasi' => 'required|same:password_baru',
        ]);
        
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }
        
        try {
            $user = Auth::user();

            if (!Hash::check($request->password_lama, $user->password)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Password lama tidak sesuai'
                ], 422);
            }

            $user->update([
                'password' => Hash::make($request->password_baru)
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Password berhasil diubah'
            ]);
        } catch (\Exception $e) {
            \Log::error('Update Password Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * update notification settings
     */
    public function updateNotifikasi(Request $request) {
        try {
            $user = Auth::user();

            $user->update([
                'notif_email_berita' => $request->email_berita ?? false,
                'notif_email_pengaduan' => $request->email_pengaduan ?? false,
                'notif_push' => $request->push_notif ?? false,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Preferensi notifikasi berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Update Notifikasi Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * update display settings
     */
    public function updateTampilan(Request $request) {
        try {
            $user = Auth::user();

            $user->update([
                'theme' => $request->theme ?? 'light',
                'accent_color' => $request->accent_color ?? 'blue',
                'font_size' => $request->font_size ?? 'medium'
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Pengaturan tampilan berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Update Tampilan Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * update preferences
     */
    public function updatePreferensi(Request $request) {
        try {
            $user = Auth::user();

            $user->update([
                'bahasa' => $request->bahasa ?? 'id',
                'timezone' => $request->timezone ?? 'Asia/Jakarta',
                'date_format' => $request->date_format ?? 'd/m/Y',
                'items_per_page' => $request->items_per_page ?? 10
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Preferensi berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            \Log::error('Update Preferensi Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * logout
     */
    public function logoutAll(Request $request) {
        try {
            Auth::logoutOtherDevices($request->password ?? '');

            return response()->json([
                'success' => true,
                'message' => 'Berhasil logout dari semua perangkat'
            ]);
        } catch (\Exception $e) {
            \Log::error('Logout All Error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    // ===== USER METHODS =====
    
    public function userIndex() {
        $user = Auth::user()->load('pelanggan');
        return view('user.user-pengaturan', compact('user'));
    }

    public function userUpdateProfile(Request $request)
{
    try {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'no_telepon' => 'nullable|string|max:20',
            'alamat' => 'nullable|string'
        ]);

        $user = Auth::user();
        
        $user->nama_lengkap = $validated['nama_lengkap'];
        $user->email = $validated['email'];
        $user->no_telepon = $validated['no_telepon'] ?? null;
        $user->save();
        
        if ($user->pelanggan && isset($validated['alamat'])) {
            $user->pelanggan->update([
                'alamat_lengkap' => $validated['alamat'],
                'nama_pelanggan' => $validated['nama_lengkap'],
                'email' => $validated['email'],
                'no_telepon' => $validated['no_telepon'] ?? null
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Profil berhasil diperbarui'
        ]);

    } catch (\Illuminate\Validation\ValidationException $e) {
        return response()->json([
            'success' => false,
            'message' => 'Validasi gagal',
            'errors' => $e->errors()
        ], 422);
        
    } catch (\Exception $e) {
        \Log::error('Update profile error: ' . $e->getMessage());
        
        return response()->json([
            'success' => false,
            'message' => 'Terjadi kesalahan: ' . $e->getMessage()
        ], 500);
    }
}

    public function userUploadFoto(Request $request) {
    try {
        $request->validate([
            'foto_profil' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048'
        ]);
        
        $user = Auth::user();
        
        // Buat folder
        if (!file_exists(public_path('uploads/users'))) {
            mkdir(public_path('uploads/users'), 0755, true);
        }
        
        // Hapus foto lama
        if ($user->foto_profil && file_exists(public_path($user->foto_profil))) {
            unlink(public_path($user->foto_profil));
        }

        // Upload
        $file = $request->file('foto_profil');
        $filename = 'user_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
        $file->move(public_path('uploads/users'), $filename);
        
        // Simpan path
        $user->foto_profil = '/uploads/users/' . $filename;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Foto berhasil diupload',
            'foto_url' => asset('uploads/users/' . $filename)
        ]);
        
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => $e->getMessage()
        ], 500);
    }
}

    public function userUpdatePassword(Request $request) {
        return $this->updatePassword($request);
    }

    public function userUpdateNotifikasi(Request $request) {
        return $this->updateNotifikasi($request);
    }

    public function userUpdateTampilan(Request $request) {
        return $this->updateTampilan($request);
    }

    public function userUpdatePreferensi(Request $request) {
        return $this->updatePreferensi($request);
    }

    public function userLogoutAll(Request $request) {
        return $this->logoutAll($request);
    }
}