<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Akun - Kazeo Official</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-zinc-50 pt-24 pb-12">
    <x-navbar/>

    <main class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 mb-16 space-y-8">
        
        <div>
            <a href="{{ route('dashboard') }}" class="inline-flex items-center gap-2 text-sm font-bold text-zinc-500 hover:text-zinc-900 transition-colors mb-4">
                <span>&larr;</span> Kembali ke Dashboard
            </a>
            <h1 class="text-2xl sm:text-3xl font-black text-zinc-900 tracking-tight flex items-center gap-2">
                <span>⚙️</span> Pengaturan Akun
            </h1>
        </div>

        @if (session('status') === 'profile-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium text-sm flex items-center gap-2">
                <span>✅</span> Profil berhasil diperbarui!
            </div>
        @elseif (session('status') === 'password-updated')
            <div x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 3000)" class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl font-medium text-sm flex items-center gap-2">
                <span>✅</span> Password berhasil diubah!
            </div>
        @endif

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-zinc-200 shadow-sm">
            <h2 class="text-lg font-black text-zinc-900 mb-1">Informasi Profil</h2>
            <p class="text-sm text-zinc-500 mb-6">Perbarui nama, email, dan foto profil (Avatar) akunmu.</p>

            <form method="post" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                @method('patch')

                <div class="flex items-center gap-6 pb-6 border-b border-zinc-100">
                    <div class="relative shrink-0 group">
                        @php
                            $defaultAvatar = 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=18181b&color=fff';
                            $avatarSrc = $user->avatar ? asset('storage/' . $user->avatar) : $defaultAvatar;
                        @endphp
                        <img src="{{ $avatarSrc }}" class="w-24 h-24 rounded-full object-cover border-4 border-zinc-100 shadow-sm group-hover:brightness-75 transition-all">
                        <div class="absolute inset-0 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none">
                            <span class="text-white text-2xl">📷</span>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-zinc-900 mb-2">Foto Profil Baru</label>
                        <input type="file" name="avatar" accept="image/*" class="block w-full text-sm text-zinc-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold file:bg-zinc-100 file:text-zinc-700 hover:file:bg-zinc-200 transition-all cursor-pointer">
                        <p class="text-xs text-zinc-400 mt-2">Format: JPG, PNG, GIF. Maksimal 2MB.</p>
                        <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('avatar')" />
                    </div>
                </div>

                <div>
                    <label for="name" class="block text-sm font-bold text-zinc-900 mb-2">Nama Lengkap</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm">
                    <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('name')" />
                </div>

                <div>
                    <label for="email" class="block text-sm font-bold text-zinc-900 mb-2">Alamat Email</label>
                    <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm">
                    <x-input-error class="mt-2 text-red-500 text-sm" :messages="$errors->get('email')" />
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="submit" class="px-8 py-3 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors shadow-sm active:scale-95">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 sm:p-8 rounded-3xl border border-zinc-200 shadow-sm">
            <h2 class="text-lg font-black text-zinc-900 mb-1">Keamanan Sandi</h2>
            <p class="text-sm text-zinc-500 mb-6">Pastikan akunmu menggunakan kata sandi yang panjang dan acak agar tetap aman.</p>

            <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                @csrf
                @method('put')

                <div>
                    <label for="current_password" class="block text-sm font-bold text-zinc-900 mb-2">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm">
                    <x-input-error :messages="$errors->updatePassword->get('current_password')" class="mt-2 text-red-500 text-sm" />
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="password" class="block text-sm font-bold text-zinc-900 mb-2">Password Baru</label>
                        <input id="password" name="password" type="password" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm">
                        <x-input-error :messages="$errors->updatePassword->get('password')" class="mt-2 text-red-500 text-sm" />
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-bold text-zinc-900 mb-2">Konfirmasi Password Baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password" class="w-full px-4 py-3 rounded-xl border border-zinc-200 bg-zinc-50 focus:bg-white focus:border-zinc-900 focus:ring-2 focus:ring-zinc-900/20 transition-all text-sm">
                        <x-input-error :messages="$errors->updatePassword->get('password_confirmation')" class="mt-2 text-red-500 text-sm" />
                    </div>
                </div>

                <div class="flex items-center justify-end gap-4 pt-4">
                    <button type="submit" class="px-8 py-3 bg-zinc-900 text-white text-sm font-bold rounded-xl hover:bg-zinc-800 transition-colors shadow-sm active:scale-95">
                        Update Password
                    </button>
                </div>
            </form>
        </div>

        <div class="bg-red-50 p-6 sm:p-8 rounded-3xl border border-red-200 shadow-sm">
            <h2 class="text-lg font-black text-red-700 mb-1">Zona Bahaya</h2>
            <p class="text-sm text-red-600/80 mb-6">Sekali kamu menghapus akun, semua data script dan riwayat komunitasmu akan hilang permanen.</p>

            <form method="post" action="{{ route('profile.destroy') }}" class="flex flex-col sm:flex-row items-end gap-4">
                @csrf
                @method('delete')
                
                <div class="w-full sm:flex-1">
                    <label for="password_delete" class="block text-sm font-bold text-red-900 mb-2">Masukkan Password untuk konfirmasi</label>
                    <input id="password_delete" name="password" type="password" placeholder="Password akunmu..." required class="w-full px-4 py-3 rounded-xl border border-red-300 bg-white focus:border-red-600 focus:ring-2 focus:ring-red-600/20 transition-all text-sm">
                    <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2 text-red-600 text-sm font-bold" />
                </div>

                <button type="submit" onclick="return confirm('Yakin ingin menghapus akun permanen?')" class="w-full sm:w-auto px-8 py-3 bg-red-600 text-white text-sm font-bold rounded-xl hover:bg-red-700 transition-colors shadow-sm active:scale-95">
                    Hapus Akun
                </button>
            </form>
        </div>

    </main>

    <x-footer/>
</body>
</html>