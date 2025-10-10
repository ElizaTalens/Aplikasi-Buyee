<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Menampilkan form edit profil yang digabung.
     */
    public function edit(): View
    {
        $user = Auth::user();
        
        // FIX: Method 'edit' harus mengembalikan sebuah 'view'
        return view('profile.edit', compact('user'));
    }

    /**
     * Memperbarui semua informasi profil pengguna dari satu form.
     */
    public function update(Request $request): RedirectResponse
    {
        // 1. Ambil pengguna yang sedang login
        $user = $request->user();

        // 2. Validasi semua input dari form
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'string', 'in:male,female'],
            'phone' => ['nullable', 'string', 'max:20'],
            'photo' => ['nullable', 'image', 'mimes:jpeg,png,jpg', 'max:2048'],
        ]);

        // 3. Logika untuk update foto profil jika ada file yang diunggah
        if ($request->hasFile('photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            // Simpan foto baru dan update path di database
            $validated['profile_photo_path'] = $request->file('photo')->store('profile-photos', 'public');
        }

        // 4. Update data pengguna dengan data yang sudah divalidasi
        $user->update($validated);

        // 5. Arahkan kembali ke halaman edit dengan pesan sukses
        return redirect()->route('profile.edit')->with('status', 'profile-updated');
    }
}