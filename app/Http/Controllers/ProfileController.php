<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;
use App\Models\Adress;
use App\Models\CartItem;

class ProfileController extends Controller
{
    /**
     * Tampilkan halaman utama profil.
     */
    public function index()
    {
        $user = Auth::user();
        
        $walletData = [
            'gopay' => $this->getGopayBalance($user->id),
            'saldo' => $this->getUserSaldo($user->id),
        ];

        // Ambil data item dari keranjang belanja
        $cartItems = CartItem::with('product')
            ->where('user_id', Auth::id())
            ->latest()
            ->take(3)
            ->get();
        
        // Hitung total harga dari item yang diambil
        $total = $cartItems->sum(function($item) {
            return $item->quantity * $item->price;
        });
        
        // Kirim semua variabel yang dibutuhkan ke view
        return view('pages.userProfile', compact( 
            'user', 
            'walletData',
            'cartItems',
            'total'
        ));
    }

    /**
     * Tampilkan form edit biodata.
     */
    public function editBiodata()
    {
        $user = Auth::user();
        return view('profile.edit-biodata', compact('user'));
    }

    /**
     * Perbarui biodata pengguna.
     */
    public function updateBiodata(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:male,female',
        ]);

        $user = User::find(Auth::id());
        $user->name = $request->name;
        $user->birth_date = $request->birth_date;
        $user->gender = $request->gender;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Biodata berhasil diperbarui!');
    }

    /**
     * Tampilkan form edit kontak.
     */
    public function editContact()
    {
        $user = Auth::user();
        return view('profile.edit-contact', compact('user'));
    }

    /**
     * Perbarui informasi kontak pengguna.
     */
    public function updateContact(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email,' . Auth::id(),
            'phone' => 'nullable|string|max:20',
        ]);

        $user = User::find(Auth::id());
        $user->email = $request->email;
        $user->phone = $request->phone;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Kontak berhasil diperbarui!');
    }

    /**
     * Unggah dan perbarui foto profil.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());

        // Hapus foto lama jika ada
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Simpan foto baru
        $photoPath = $request->file('photo')->store('profile-photos', 'public');
        
        $user->profile_photo = $photoPath;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    // Metode Helper
    private function getGopayBalance($userId)
    {
        return 9091; 
    }

    private function getUserSaldo($userId)
    {
        $user = User::find($userId);
        return $user->wallet_balance ?? 300;
    }
}