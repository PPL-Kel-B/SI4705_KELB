# ShareBite Unit Bisnis Dashboard - Dokumentasi Teknis

## 📋 Overview

Halaman dashboard modern untuk Unit Bisnis (Mitra) di platform ShareBite. Terdiri dari 2 halaman utama:

1. **Profil Unit Bisnis** - Menampilkan dan mengelola informasi bisnis
2. **Pengaturan Unit Bisnis** - Mengatur jam operasional, radius penjemputan, dan notifikasi

## 📁 Struktur File

```
resources/
├── views/
│   ├── unit_bisnis/
│   │   ├── profil.blade.php          # Halaman profil (view + edit mode)
│   │   ├── pengaturan.blade.php      # Halaman pengaturan
│   │   ├── dashboard.blade.php       # Dashboard utama
│   │   ├── kelola_makanan/           # Menu makanan
│   │   ├── pesanan.blade.php        # Pesanan masuk
│   │   └── riwayat.blade.php        # Riwayat penjualan
│   └── layouts/
│       └── unit_bisnis.blade.php    # Master layout dengan sidebar
│
app/
├── Http/
│   └── Controllers/
│       └── UnitBisnisController.php  # Controller utama
├── Models/
│   └── UnitBisnisProfile.php         # Model (updated)
│
database/
└── migrations/
    └── 2026_05_22_000001_add_unit_bisnis_profile_fields.php
```

## 🎯 Fitur Utama

### 1. Halaman Profil Unit Bisnis

**View Mode:**
- ✅ Menampilkan foto/logo bisnis
- ✅ Informasi dasar (nama, tipe, badge verified)
- ✅ Statistik dampak sosial (total porsi, total kg)
- ✅ Informasi bisnis (email, telp, alamat)
- ✅ Embedded map preview
- ✅ Tombol ubah profil & ganti password

**Edit Mode:**
- ✅ Upload foto bisnis dengan preview
- ✅ Edit semua informasi bisnis
- ✅ Form validation
- ✅ Success/error notifications
- ✅ Loading state button

### 2. Halaman Pengaturan Unit Bisnis

**Fitur:**
- ✅ Jam operasional penjemputan (buka/tutup)
- ✅ Radius penjemputan slider (1-50 km)
- ✅ Realtime radius display
- ✅ Toggle notifikasi (master + detail)
- ✅ Informasi identitas bisnis
- ✅ Support button (WhatsApp)
- ✅ Keamanan akun & ganti password

## 🔄 Routes

```php
// Profil
GET    /unit/profil                  → showProfile()
POST   /unit/profil/update           → updateProfile()

// Pengaturan
GET    /unit/pengaturan              → showSettings()
POST   /unit/pengaturan/update       → updateSettings()
POST   /unit/pengaturan/update-password → updatePassword()
```

## 📊 Database Schema

### UnitBisnisProfile Table

```sql
- id                    : unsigned big integer (PK)
- user_id              : unsigned big integer (FK)
- nama_bisnis          : string (100) nullable
- tipe_bisnis          : string (100) nullable
- email_bisnis         : string (255) nullable
- no_telepon           : string (20) nullable
- foto_bisnis          : string (255) nullable
- alamat               : string (255)
- lokasi_lat           : decimal (10,8) nullable
- lokasi_lng           : decimal (11,8) nullable
- radius_penjemputan   : integer (default: 15)
- jam_buka             : time (default: 08:00)
- jam_tutup            : time (default: 21:00)
- verified             : boolean (default: false)
- tahun_bergabung      : year nullable
- notifikasi_aktif     : boolean (default: true)
- notifikasi_pesanan   : boolean (default: true)
- notifikasi_penjemputan : boolean (default: true)
- status_verifikasi    : enum('pending','terverifikasi','ditolak')
- nib_file             : string (255) nullable
- total_makanan_terjual : unsigned integer (default: 0)
- total_berat_terjual  : decimal (8,2) (default: 0)
- timestamps
```

## 🎨 Design Details

### Color Scheme
- **Primary Green:** #16a34a (Tombol utama, badges)
- **Secondary Colors:** Blue, Purple, Amber, Red (Sections)
- **Background:** #F4F8F6 (Soft green aesthetic)
- **Cards:** White dengan border-radius-xl

### Components
- **Cards:** Rounded-2xl, shadow-sm, border border-gray-100
- **Buttons:** Rounded-xl dengan padding y-2.5
- **Forms:** Rounded-xl inputs dengan focus states
- **Sliders:** Custom range dengan gradient
- **Toggles:** Switch dengan green color
- **Icons:** SVG heroicons/lucide

### Responsive
- Mobile-first design
- Grid: 1 col (mobile) → 2 col (tablet) → 3 col (desktop)
- Sidebar minimizable di desktop, collapsible di mobile
- Flexible layouts dengan gap spacing

## 🧪 Testing Test Cases

```
TC-01: Load Profil
- Navigasi ke /unit/profil
- Verifikasi data bisnis ditampilkan dengan benar
- Verifikasi statistik ter-load

TC-02: Update Profil Valid
- Klik tombol "Ubah Profil"
- Edit form fields
- Submit form
- Verifikasi success notification

TC-03: Update Profil Tidak Valid
- Klik tombol "Ubah Profil"
- Submit dengan field kosong
- Verifikasi validation errors

TC-04: Upload Foto
- Pilih foto dari local
- Verifikasi preview
- Submit form
- Verifikasi foto tersimpan

TC-05: Update Jam Operasional
- Navigasi ke /unit/pengaturan
- Ubah jam buka/tutup
- Submit
- Verifikasi preview jam

TC-06: Update Radius Slider
- Drag slider
- Verifikasi realtime display
- Submit form

TC-07: Toggle Notifikasi
- Click toggle notifikasi
- Verifikasi state berubah
- Submit form

TC-08: Ganti Password
- Buka modal ganti password
- Input current password
- Input password baru 2x
- Submit
- Verifikasi success

TC-09: Responsive Mobile
- Test di mobile view
- Verifikasi layout beradaptasi
- Verifikasi sidebar collapsible
- Verifikasi form usable

TC-10: API Failure
- Matikan internet
- Submit form
- Verifikasi error handling
```

## 🚀 Setup & Installation

### 1. Run Migrations
```bash
php artisan migrate
```

### 2. Seed Data (Optional)
```bash
php artisan db:seed --class=UnitBisnisSeeder
```

### 3. Test Routes
```bash
php artisan route:list | grep unit
```

### 4. Build Assets
```bash
npm run dev
```

### 5. Start Server
```bash
php artisan serve
```

## 📝 Usage Examples

### Controller Method Examples

```php
// Get Unit Bisnis Data
$data = $this->getUnitBisnisData();

// Update Profile
$unitBisnis->update($validated);

// Update Settings
$unitBisnis->update([
    'jam_buka' => $validated['jam_buka'],
    'jam_tutup' => $validated['jam_tutup'],
    'radius_penjemputan' => $validated['radius_penjemputan'],
]);
```

### Blade Template Usage

```blade
<!-- Edit Mode Toggle -->
<div x-data="{ isEditing: false }">
    <input :disabled="!isEditing" />
    <button @click="isEditing = !isEditing">Toggle Edit</button>
</div>

<!-- File Upload Preview -->
<input type="file" @change="preview" />

<!-- Slider with Realtime Display -->
<input type="range" @change="radiusValue = $el.value" />
<span x-text="radiusValue + ' KM'"></span>

<!-- Form Submission -->
<form @submit="isSaving = true">
    <button type="submit" :disabled="isSaving">
        <span x-show="!isSaving">Save</span>
        <span x-show="isSaving">Saving...</span>
    </button>
</form>
```

## 🔐 Security Notes

- ✅ CSRF protection (csrf token di semua form)
- ✅ Authorization (middleware 'auth')
- ✅ Input validation (di controller)
- ✅ File upload validation
- ✅ Password hashing (bcrypt)

## 📚 Dependencies

- Laravel 11.x
- Tailwind CSS 3.x
- Alpine.js 3.x
- Heroicons SVG

## ⚡ Performance Optimization

- Lazy loading untuk images
- Debounce slider input
- Caching untuk statistics
- Optimized queries dengan eager loading

## 🐛 Troubleshooting

### Masalah: Routes tidak terdaftar
**Solusi:** Clear route cache
```bash
php artisan route:clear
php artisan route:cache
```

### Masalah: Styles tidak muncul
**Solusi:** Rebuild Tailwind
```bash
npm run dev
```

### Masalah: Upload foto gagal
**Solusi:** Check storage permissions
```bash
php artisan storage:link
chmod -R 775 storage/
```

## 📞 Support

Untuk bantuan, hubungi support team melalui WhatsApp di menu Pengaturan.

---

**Last Updated:** May 22, 2026
**Version:** 1.0.0
