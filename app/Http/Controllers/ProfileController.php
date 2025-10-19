<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
<<<<<<< HEAD
use App\Models\User;
use App\Models\Transaction;
use App\Models\Notification;
use App\Models\CartItem;
use App\Models\Adress;


class ProfileController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's wallet balances
        $walletData = [
            'gopay' => $this->getGopayBalance($user->id),
            'saldo' => $this->getUserSaldo($user->id),
        ];
        
        // Get unread notifications count
        $unreadNotifications = $this->getUnreadNotificationsCount($user->id);
        
        // Get cart items count
        $cartCount = $this->getCartItemsCount($user->id);
        
        // Get recent transactions count
        $recentTransactions = $this->getRecentTransactionsCount($user->id);

        return view('profile.index', compact(
            'user', 
            'walletData', 
            'unreadNotifications', 
            'cartCount',
            'recentTransactions'
        ));
    }

    /**
     * Lihat form biodata.
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
     * Lihat form edit kontak.
     */
    public function editContact()
    {
        $user = Auth::user();
        return view('profile.edit-contact', compact('user'));
    }

    /**
     * Perbarui informasi pengguna.
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
     * Upload profile photo.
     */
    public function updatePhoto(Request $request)
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $user = User::find(Auth::id());

        // Delete old photo if exists
        if ($user->profile_photo) {
            Storage::disk('public')->delete($user->profile_photo);
        }

        // Store new photo
        $photoPath = $request->file('photo')->store('profile-photos', 'public');
        
        $user->profile_photo = $photoPath;
        $user->save();

        return redirect()->route('profile.index')
            ->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Show address list.
     */
    public function addresses()
    {
        $user = Auth::user();
        // Baris berikut bisa error (merah) jika relasi addresses tidak ada di model User
        $addresses = $user->addresses()->get();
        
        return view('profile.addresses', compact('addresses'));
    }

    /**
     * Show payment methods.
     */
    public function paymentMethods()
    {
        $user = Auth::user();
        $paymentMethods = $user->paymentMethods()->get();
        
        return view('profile.payment-methods', compact('paymentMethods'));
    }

    /**
     * Show transaction history.
     */
    public function transactions(Request $request)
    {
        $user = Auth::user();
        
        $query = Transaction::where('user_id', $user->id);
        
        // Filter by status if provided
        if ($request->has('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }
        
        // Filter by date range if provided
        if ($request->has('date_from') && $request->date_from) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->has('date_to') && $request->date_to) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        // Search by product name or invoice number
        if ($request->has('search') && $request->search) {
            $query->where(function($q) use ($request) {
                $q->where('invoice_number', 'like', '%' . $request->search . '%')
                  ->orWhereHas('items', function($itemQuery) use ($request) {
                      $itemQuery->where('product_name', 'like', '%' . $request->search . '%');
                  });
            });
        }
        
        $transactions = $query->with(['items', 'store'])
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);
        
        return view('profile.transactions', compact('transactions'));
    }

    /**
     * Show notifications.
     */
    public function notifications()
    {
        $user = Auth::user();
        $notifications = $user->notifications()
                             ->orderBy('created_at', 'desc')
                             ->paginate(20);
        
        return view('profile.notifications', compact('notifications'));
    }

    /**
     * Show wishlist.
     */
    public function wishlist()
    {
        $user = Auth::user();
        $wishlistItems = $user->wishlist()->with('product')->get();
        
        return view('profile.wishlist', compact('wishlistItems'));
    }

    /**
     * Show favorite stores.
     */
    public function favoriteStores()
    {
        $user = Auth::user();
        $favoriteStores = $user->favoriteStores()->get();
        
        return view('profile.favorite-stores', compact('favoriteStores'));
    }

    /**
     * Show settings page.
     */
    public function settings()
    {
        $user = Auth::user();
        return view('profile.settings', compact('user'));
    }

    /**
     * Update user settings.
     */
    public function updateSettings(Request $request)
    {
        $request->validate([
            'notification_email' => 'boolean',
            'notification_sms' => 'boolean',
            'newsletter_subscription' => 'boolean',
            'theme_mode' => 'in:light,dark',
        ]);

        $user = Auth::user();
        $user->settings()->updateOrCreate(
            ['user_id' => $user->id],
            [
                'notification_email' => $request->notification_email ?? false,
                'notification_sms' => $request->notification_sms ?? false,
                'newsletter_subscription' => $request->newsletter_subscription ?? false,
                'theme_mode' => $request->theme_mode ?? 'light',
            ]
        );

        return redirect()->route('profile.settings')
            ->with('success', 'Pengaturan berhasil disimpan!');
    }

    // Helper methods

    private function getGopayBalance($userId)
    {
        // Implementasi untuk mendapatkan saldo GoPay
        // Biasanya dari API GoPay atau database wallet
        return 9091; // Example static value
    }

    private function getUserSaldo($userId)
    {
        // Implementasi untuk mendapatkan saldo user
        $user = User::find($userId);
        return $user->wallet_balance ?? 300;
    }

    private function getUnreadNotificationsCount($userId)
    {
        return \App\Models\Notification::where('user_id', $userId)
                                     ->where('read_at', null)
                                     ->count();
    }

    private function getCartItemsCount($userId)
    {
        return \App\Models\CartItem::where('user_id', $userId)->sum('quantity');
    }

    private function getRecentTransactionsCount($userId)
    {
        return Transaction::where('user_id', $userId)
                         ->where('created_at', '>=', now()->subDays(30))
                         ->count();
=======
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
>>>>>>> semua-halaman
    }
}