# 🚀 ShareBite Unit Bisnis Dashboard - Quick Start Guide

## 📦 Installation

### 1. Run Database Migrations
```bash
php artisan migrate
```

### 2. (Optional) Seed Sample Data
```bash
php artisan db:seed --class=UnitBisnisSeeder
```

### 3. Build Frontend Assets
```bash
npm install
npm run dev
```

### 4. Start Development Server
```bash
php artisan serve
```

## 🎯 Quick Testing

### Login as Unit Bisnis (Mitra)

**Demo Account (after seeding):**
- Email: `mitra@sharebite.com`
- Password: `password`

**Or create new:**
1. Go to `/register/unit-bisnis`
2. Fill in the form
3. Login

### Access Halaman

After login, navigate to:
- **Profil Unit Bisnis**: `/unit/profil`
- **Pengaturan Unit Bisnis**: `/unit/pengaturan`

## 🎨 UI/UX Features Checklist

### ✅ Profil Unit Bisnis

- [x] Display mode dengan semua informasi
- [x] Edit mode with form validation
- [x] Photo upload dengan preview
- [x] Success/error notifications
- [x] Loading state pada button
- [x] Statistics cards (porsi, kg)
- [x] Location preview (embedded map)
- [x] Ganti password modal
- [x] Responsive design (mobile-first)
- [x] Alpine.js interactivity
- [x] Tailwind CSS styling

### ✅ Pengaturan Unit Bisnis

- [x] Jam operasional (buka/tutup)
- [x] Radius penjemputan slider (1-50 km)
- [x] Realtime slider value display
- [x] Multiple notification toggles
- [x] Business info display cards
- [x] Support button (WhatsApp)
- [x] Security section with password change
- [x] Form validation
- [x] Success notifications
- [x] Responsive layout

## 🔧 Configuration

### Tailwind Colors

Main color scheme sudah dikonfigurasi di `tailwind.config.js`:
- Primary: `#16a34a` (green-600)
- Secondary: `#1cb764` (custom green)

### Font

Using Google Font:
- Family: "Plus Jakarta Sans"
- Weights: 400, 500, 600, 700, 800

## 📱 Responsive Breakpoints

- **Mobile**: < 640px (full width)
- **Tablet**: 640px - 1024px (2 columns)
- **Desktop**: > 1024px (3 columns)

## 🧪 Test Scenarios

### Scenario 1: Update Profile
1. Login sebagai unit bisnis
2. Navigate to `/unit/profil`
3. Click "Ubah Profil"
4. Edit satu atau lebih fields
5. Click "Simpan Perubahan"
6. Verify success notification

### Scenario 2: Update Settings
1. Navigate to `/unit/pengaturan`
2. Change jam buka/tutup
3. Adjust radius slider
4. Toggle notifikasi
5. Click "Simpan Pengaturan"
6. Verify all changes saved

### Scenario 3: Upload Photo
1. In profile edit mode
2. Click photo area
3. Select image from local
4. Verify preview appears
5. Save changes
6. Verify image persisted

### Scenario 4: Change Password
1. Open modal from profil page
2. Input current password
3. Input new password 2x
4. Click "Ganti Kata Sandi"
5. Verify success message

## 🛠️ File Structure

```
resources/views/
├── unit_bisnis/
│   ├── profil.blade.php           ← Profile page (350+ lines)
│   ├── pengaturan.blade.php       ← Settings page (480+ lines)
│   ├── dashboard.blade.php        ← Dashboard (can customize)
│   └── ...other pages
└── layouts/
    └── unit_bisnis.blade.php      ← Master layout with sidebar

app/Http/Controllers/
└── UnitBisnisController.php       ← Main controller (180+ lines)

app/Models/
└── UnitBisnisProfile.php          ← Model (updated fillable)

database/
└── migrations/
    └── 2026_05_22_000001_add_unit_bisnis_profile_fields.php
    └── seeders/UnitBisnisSeeder.php

routes/
└── web.php                        ← Routes registered
```

## 🎯 Key Methods

### Controller Methods

```php
UnitBisnisController::
  - dashboard()              // Show dashboard
  - showProfile()            // Show profile page
  - editProfile()            // Show edit form
  - updateProfile()          // Save profile changes
  - showSettings()           // Show settings page
  - updateSettings()         // Save settings
  - updatePassword()         // Change password
```

### Helper Methods

```php
- getUnitBisnisData()      // Get all data for display
```

## 🔐 Security

✅ CSRF Protection - Token included in forms
✅ Authorization - Middleware 'auth' required
✅ Input Validation - Server-side validation
✅ File Upload - Validation on size, extension
✅ Password - Hashed with bcrypt

## 🚨 Troubleshooting

### Issue: Routes not found
```bash
php artisan route:clear
php artisan route:cache
php artisan route:list
```

### Issue: Styles not loading
```bash
npm run dev
php artisan view:clear
```

### Issue: Upload fails
```bash
php artisan storage:link
chmod -R 775 storage/app
chmod -R 775 public/storage
```

### Issue: Database errors
```bash
php artisan migrate:refresh --seed
```

## 📊 Database Fields Added

```sql
ALTER TABLE unit_bisnis_profiles ADD COLUMN (
    nama_bisnis VARCHAR(100),
    tipe_bisnis VARCHAR(100),
    email_bisnis VARCHAR(255),
    no_telepon VARCHAR(20),
    foto_bisnis VARCHAR(255),
    lokasi_lat DECIMAL(10,8),
    lokasi_lng DECIMAL(11,8),
    radius_penjemputan INT DEFAULT 15,
    jam_buka TIME DEFAULT '08:00',
    jam_tutup TIME DEFAULT '21:00',
    verified BOOLEAN DEFAULT FALSE,
    tahun_bergabung YEAR,
    notifikasi_aktif BOOLEAN DEFAULT TRUE,
    notifikasi_pesanan BOOLEAN DEFAULT TRUE,
    notifikasi_penjemputan BOOLEAN DEFAULT TRUE
);
```

## 💡 Tips & Best Practices

1. **Always use Alpine.js for client-side logic**
   ```blade
   <div x-data="{ isEditing: false }">
   ```

2. **Use Tailwind classes for styling**
   - Consistency across pages
   - Easy to maintain
   - Good documentation

3. **Mobile-first approach**
   - Design for mobile first
   - Then enhance for larger screens

4. **Accessibility**
   - Use semantic HTML
   - Include alt text for images
   - Label form fields

5. **Performance**
   - Lazy load images
   - Minimize bundle size
   - Use CSS classes instead of inline styles

## 📚 Resources

- [Laravel Documentation](https://laravel.com/docs)
- [Tailwind CSS](https://tailwindcss.com)
- [Alpine.js](https://alpinejs.dev)
- [Heroicons](https://heroicons.com)

## ✨ Features Summary

| Feature | Profil | Pengaturan |
|---------|--------|-----------|
| View/Edit Toggle | ✅ | ✗ |
| Photo Upload | ✅ | ✗ |
| Form Validation | ✅ | ✅ |
| Statistics Cards | ✅ | ✗ |
| Embedded Map | ✅ | ✗ |
| Slider Controls | ✗ | ✅ |
| Toggle Switches | ✗ | ✅ |
| Password Change | ✅ | ✅ |
| Success Alerts | ✅ | ✅ |
| Responsive | ✅ | ✅ |
| Dark Mode Ready | ✅ | ✅ |

## 📞 Support

Untuk pertanyaan atau issues, hubungi:
- Team Lead: [@team]
- Documentation: [UNIT_BISNIS_DASHBOARD_DOCS.md](./UNIT_BISNIS_DASHBOARD_DOCS.md)

---

**Version:** 1.0.0  
**Last Updated:** May 22, 2026  
**Status:** ✅ Ready for Production
