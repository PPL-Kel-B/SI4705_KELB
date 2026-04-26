# Dashboard Admin Panel - Kelola Menu Makanan

Aplikasi web admin panel modern untuk mengelola master data menu makanan pada sistem ShareBite.

## 📁 Struktur File

```
resources/
├── js/
│   ├── app.jsx                          # Entry point React
│   ├── bootstrap.js                     # Bootstrap file
│   ├── components/
│   │   ├── Sidebar.jsx                  # Komponen sidebar navigasi
│   │   ├── Header.jsx                   # Komponen header dengan tabs
│   │   ├── FoodCard.jsx                 # Komponen card makanan
│   │   └── EditModal.jsx                # Komponen modal edit makanan
│   └── pages/
│       └── KelolaMenuMakanan.jsx        # Halaman utama dashboard
├── css/
│   └── app.css                          # Tailwind CSS configuration
└── views/
    └── KelolaMenuMakanan.blade.php      # Laravel Blade template

routes/
└── web.php                              # Route configuration (sudah ditambahkan)
```

## 🚀 Cara Menjalankan

### 1. Development Mode
```bash
npm run dev
```
Buka browser di: `http://localhost:5173/admin/kelola-menu-makanan`

### 2. Production Build
```bash
npm run build
```

## ✨ Fitur Utama

### 1. **Sidebar Navigation**
- Logo ShareBite
- Menu navigasi: Dashboard, Kelola Makanan, Pesanan, Riwayat, Profil, Pengaturan
- Tombol Logout
- Active state indicator

### 2. **Header Section**
- Judul halaman "Kelola Menu Makanan"
- Tab switcher: Menu Aktif & Master Data
- Info user (Unit Bisnis + Avatar)
- Notification bell

### 3. **Master Data View**
- Search bar untuk filter makanan
- Tombol "+ Tambah Master Menu" untuk tambah data baru
- Grid responsive (4 kolom desktop, 2 kolom tablet, 1 kolom mobile)

### 4. **Food Card Component**
Setiap card menampilkan:
- Gambar makanan (dengan hover zoom effect)
- Badge kategori
- Nama makanan
- Harga & Berat
- Jumlah porsi
- Tombol "Edit Master" (emerald green)
- Icon delete (red)

### 5. **Edit Modal**
- Form lengkap untuk edit data makanan
- Field: Nama, Kategori, Berat, Harga, Jumlah Porsi, Deskripsi
- Upload gambar dengan drag & drop
- Validation dan error handling
- Toast notification (success/error)

### 6. **State Management**
- List makanan dengan dummy data
- Search filtering
- Modal open/close
- Loading state
- Error & success notifications

## 🎨 Design Features

### Color Scheme
- **Primary**: Emerald Green (#059669)
- **Background**: Gray (#F9FAFB)
- **Border**: Light Gray (#E5E7EB)
- **Text**: Dark Gray (#111827)

### Typography
- **Headings**: Semibold to Bold, 24px-48px
- **Body**: Regular to Medium, 14px-16px
- **Small text**: 12px-13px

### Components Styling
- Rounded corners: `rounded-lg` (8px), `rounded-xl` (12px), `rounded-full` (9999px)
- Shadows: Subtle shadows untuk depth
- Transitions: Smooth 200ms-300ms transitions
- Hover effects: Scale, color, shadow changes

## 📊 Dummy Data

Aplikasi dilengkapi dengan 8 item dummy makanan dengan kategori beragam:
- Nasi Kotak (4 items)
- Pasta (1 item)
- Salad (1 item)
- Western (1 item)
- Seafood (1 item)

Setiap item memiliki:
- ID, Nama, Kategori, Berat, Harga
- Jumlah Porsi, Deskripsi
- Image URL (menggunakan Unsplash)

## 🔄 User Flow - Edit Master Data

1. User membuka halaman `/admin/kelola-menu-makanan`
2. User melihat grid card makanan
3. Klik tombol "Edit Master" pada salah satu card
4. Modal edit terbuka dengan data makanan terisi
5. User mengubah data (nama, kategori, berat, harga, dll)
6. User upload gambar baru (optional)
7. Klik "Simpan Perubahan"
8. Validasi field diperlukan (nama, berat, harga)
9. Jika berhasil → Toast "Perubahan berhasil disimpan"
10. Data di UI terupdate dan modal tertutup
11. Jika gagal → Toast error "Data tidak berhasil disimpan, periksa koneksi Anda"

## 📱 Responsive Design

- **Desktop (lg)**: 4 kolom grid
- **Tablet (md)**: 2 kolom grid
- **Mobile (sm)**: 1 kolom grid

Semua komponen responsive dengan breakpoints Tailwind:
- `sm`: 640px
- `md`: 768px
- `lg`: 1024px

## 🔧 Tech Stack

- **Framework**: React 18
- **Build Tool**: Vite
- **Styling**: Tailwind CSS 4
- **Icons**: Lucide React
- **Backend**: Laravel
- **Templating**: Blade PHP

## 📝 Environment Setup

Package.json sudah terinstall dengan:
- react & react-dom
- lucide-react (icons)
- @vitejs/plugin-react
- tailwindcss & @tailwindcss/vite
- laravel-vite-plugin

## 🎯 Next Steps

1. **Integrasi API**: Hubungkan dengan backend Laravel untuk data makanan
2. **Authentication**: Tambahkan login & session management
3. **Validation**: Backend validation untuk form submission
4. **Image Upload**: Implementasi upload ke storage
5. **Pagination**: Tambahkan pagination untuk large datasets
6. **Advanced Filters**: Filter by kategori, harga range, dll
7. **Bulk Actions**: Delete multiple items sekaligus
8. **Menu Aktif Tab**: Implementasi tab "Menu Aktif" functionality

## 🐛 Troubleshooting

### Port Already in Use
```bash
npm run dev -- --port 3000
```

### CSS Not Compiling
Pastikan `@source` di app.css mencakup semua JSX file:
```css
@source '../**/*.jsx';
```

### Images Not Loading
Gunakan absolute URLs atau import images:
```jsx
import imageName from '../assets/image.jpg';
```

---

**Status**: ✅ Siap untuk development & integrasi API
