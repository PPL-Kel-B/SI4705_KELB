# 📖 ShareBite Unit Bisnis API Documentation

## Overview

API endpoints untuk Unit Bisnis profile dan settings management. Semua endpoints require authentication dan role `unit_bisnis`.

## Base URL
```
/unit
```

---

## 🔐 Authentication

All endpoints require:
- Middleware: `auth`
- User Role: `unit_bisnis`

```php
Route::middleware('auth')->group(function () {
    Route::prefix('unit')->name('unit.')->group(function () {
        // All endpoints below
    });
});
```

---

## 📋 Endpoints

### 1. Get Profile Page

**Route:** `GET /unit/profil`  
**Name:** `unit.profil`  
**Method:** `UnitBisnisController@showProfile()`

**Response:**
```php
[
    'unitBisnis' => UnitBisnisProfile,
    'user' => User,
    'stats' => [
        'total_porsi' => int,
        'total_kg' => float,
        'jumlah_pesanan' => int,
        'total_kontribusi' => int,
    ],
    'isNew' => bool
]
```

**Example Response:**
```json
{
    "unitBisnis": {
        "id": 1,
        "user_id": 5,
        "nama_bisnis": "Arcamanik Hotel",
        "tipe_bisnis": "Hotel",
        "email_bisnis": "partnership@arcamanikhotel.com",
        "no_telepon": "+62 (22) 781-4455",
        "foto_bisnis": "images/placeholder-bisnis.jpg",
        "alamat": "Jl. Soekarno-Hatta No. 789...",
        "radius_penjemputan": 15,
        "jam_buka": "08:00",
        "jam_tutup": "21:00",
        "verified": true,
        "tahun_bergabung": 2023
    },
    "stats": {
        "total_porsi": 1420,
        "total_kg": 580.5,
        "jumlah_pesanan": 142,
        "total_kontribusi": 142
    }
}
```

**Status Code:** `200 OK`

---

### 2. Update Profile

**Route:** `POST /unit/profil/update`  
**Name:** `unit.profil.update`  
**Method:** `UnitBisnisController@updateProfile()`

**Request Headers:**
```
Content-Type: multipart/form-data
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "nama_bisnis": "string (required, max:255)",
    "email_bisnis": "email (required)",
    "no_telepon": "string (required, max:20)",
    "tipe_bisnis": "string (required, max:100)",
    "alamat": "string (required, max:500)",
    "foto_bisnis": "file (optional, image, max:2MB)"
}
```

**Validation Rules:**
```php
[
    'nama_bisnis' => 'required|string|max:255',
    'email_bisnis' => 'required|email|max:255',
    'no_telepon' => 'required|string|max:20',
    'tipe_bisnis' => 'required|string|max:100',
    'alamat' => 'required|string|max:500',
    'foto_bisnis' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
]
```

**Success Response:**
```
Redirect to /unit/profil
Session message: "Profil berhasil diperbarui!"
```

**Error Response (Validation Failed):**
```
Redirect back with errors
Status: 422 Unprocessable Entity
```

**Example cURL:**
```bash
curl -X POST http://localhost:8000/unit/profil/update \
  -H "X-CSRF-TOKEN: token" \
  -F "nama_bisnis=My Restaurant" \
  -F "email_bisnis=info@myrestaurant.com" \
  -F "no_telepon=+62812345678" \
  -F "tipe_bisnis=Restoran" \
  -F "alamat=Jl. Bandung No. 123" \
  -F "foto_bisnis=@/path/to/image.jpg"
```

---

### 3. Get Settings Page

**Route:** `GET /unit/pengaturan`  
**Name:** `unit.pengaturan`  
**Method:** `UnitBisnisController@showSettings()`

**Response:**
```php
[
    'unitBisnis' => UnitBisnisProfile,
    'user' => User,
    'stats' => [
        'total_porsi' => int,
        'total_kg' => float,
        'jumlah_pesanan' => int,
        'total_kontribusi' => int,
    ],
    'isNew' => bool
]
```

**Example Response:**
```json
{
    "unitBisnis": {
        "id": 1,
        "radius_penjemputan": 15,
        "jam_buka": "08:00",
        "jam_tutup": "21:00",
        "notifikasi_aktif": true,
        "notifikasi_pesanan": true,
        "notifikasi_penjemputan": true,
        "nama_bisnis": "Arcamanik Hotel",
        "email_bisnis": "partnership@arcamanikhotel.com",
        "no_telepon": "+62 (22) 781-4455"
    }
}
```

**Status Code:** `200 OK`

---

### 4. Update Settings

**Route:** `POST /unit/pengaturan/update`  
**Name:** `unit.pengaturan.update`  
**Method:** `UnitBisnisController@updateSettings()`

**Request Headers:**
```
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "jam_buka": "08:00",
    "jam_tutup": "21:00",
    "radius_penjemputan": 15,
    "notifikasi_aktif": 1,
    "notifikasi_pesanan": 1,
    "notifikasi_penjemputan": 1
}
```

**Validation Rules:**
```php
[
    'jam_buka' => 'required|date_format:H:i',
    'jam_tutup' => 'required|date_format:H:i|after:jam_buka',
    'radius_penjemputan' => 'required|integer|min:1|max:50',
    'notifikasi_aktif' => 'boolean',
    'notifikasi_pesanan' => 'boolean',
    'notifikasi_penjemputan' => 'boolean',
]
```

**Validation Messages:**
```
jam_buka.required = "Jam buka harus diisi"
jam_tutup.after = "Jam tutup harus lebih besar dari jam buka"
radius_penjemputan.min = "Radius minimum 1 km"
radius_penjemputan.max = "Radius maksimal 50 km"
```

**Success Response:**
```
Redirect to /unit/pengaturan
Session message: "Pengaturan berhasil diperbarui!"
Status: 302 Found
```

**Error Response (Validation Failed):**
```
Redirect back with errors and old input
Status: 302 Found
$errors->bag('default') contains validation errors
```

**Example cURL:**
```bash
curl -X POST http://localhost:8000/unit/pengaturan/update \
  -H "X-CSRF-TOKEN: token" \
  -d "jam_buka=08:00" \
  -d "jam_tutup=21:00" \
  -d "radius_penjemputan=20" \
  -d "notifikasi_aktif=1" \
  -d "notifikasi_pesanan=1" \
  -d "notifikasi_penjemputan=1"
```

---

### 5. Update Password

**Route:** `POST /unit/pengaturan/update-password`  
**Name:** `unit.pengaturan.update-password`  
**Method:** `UnitBisnisController@updatePassword()`

**Request Headers:**
```
Content-Type: application/x-www-form-urlencoded
X-CSRF-TOKEN: {token}
```

**Request Body:**
```json
{
    "current_password": "string (required)",
    "password": "string (required, min:8, confirmed)",
    "password_confirmation": "string (required, same as password)"
}
```

**Validation Rules:**
```php
[
    'current_password' => 'required',
    'password' => 'required|string|min:8|confirmed',
]
```

**Validation Messages:**
```
current_password.required = "Kata sandi saat ini harus diisi"
password.required = "Kata sandi baru harus diisi"
password.min = "Kata sandi minimal 8 karakter"
password.confirmed = "Konfirmasi kata sandi tidak cocok"
```

**Success Response:**
```
Redirect back with message: "Kata sandi berhasil diubah!"
Status: 302 Found
```

**Error Response (Wrong Current Password):**
```json
{
    "error": "Kata sandi saat ini tidak sesuai"
}
```

**Error Response (Validation Failed):**
```
Redirect back with validation errors
Status: 302 Found
```

**Example cURL:**
```bash
curl -X POST http://localhost:8000/unit/pengaturan/update-password \
  -H "X-CSRF-TOKEN: token" \
  -d "current_password=oldpassword" \
  -d "password=newpassword123" \
  -d "password_confirmation=newpassword123"
```

---

## 🔄 Request/Response Examples

### Example 1: Complete Profile Update Flow

**Step 1: GET Profile**
```bash
curl -H "Cookie: XSRF-TOKEN=token; Laravel_Session=session" \
  http://localhost:8000/unit/profil
```

**Step 2: POST Update**
```bash
curl -X POST http://localhost:8000/unit/profil/update \
  -H "X-CSRF-TOKEN: csrf_token" \
  -H "Cookie: XSRF-TOKEN=token; Laravel_Session=session" \
  -F "nama_bisnis=Updated Name" \
  -F "email_bisnis=newemail@example.com" \
  -F "no_telepon=+628123456789" \
  -F "tipe_bisnis=Restoran" \
  -F "alamat=New Address" \
  -F "foto_bisnis=@/path/to/new-image.jpg"
```

**Response:**
```
Location: /unit/profil
Set-Cookie: LARAVEL_SESSION=...
Session: flash message "Profil berhasil diperbarui!"
```

### Example 2: Settings Update with Validation Error

**Request:**
```json
{
    "jam_buka": "21:00",
    "jam_tutup": "08:00",
    "radius_penjemputan": 0
}
```

**Response:**
```
Status: 302 (Redirect back)
Session errors:
{
    "jam_tutup": ["Jam tutup harus lebih besar dari jam buka"],
    "radius_penjemputan": ["Radius minimum 1 km"]
}
Old input is preserved in session
```

### Example 3: Password Change

**Request:**
```json
{
    "current_password": "currentpass",
    "password": "newpassword123",
    "password_confirmation": "newpassword123"
}
```

**Success Response:**
```
Status: 302
Message: "Kata sandi berhasil diubah!"
User can login with new password
```

---

## 📊 Data Models

### UnitBisnisProfile Model

```php
class UnitBisnisProfile extends Model {
    protected $fillable = [
        'user_id',
        'nama_bisnis',
        'tipe_bisnis',
        'email_bisnis',
        'no_telepon',
        'foto_bisnis',
        'alamat',
        'lokasi_lat',
        'lokasi_lng',
        'radius_penjemputan',
        'jam_buka',
        'jam_tutup',
        'verified',
        'tahun_bergabung',
        'notifikasi_aktif',
        'notifikasi_pesanan',
        'notifikasi_penjemputan',
        'status_verifikasi',
        'total_makanan_terjual',
        'total_berat_terjual',
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
}
```

### User Model (Unit Bisnis)

```php
class User extends Authenticatable {
    // For unit_bisnis role:
    // role = 'unit_bisnis'
    // has relationship with UnitBisnisProfile
    public function unitBisnis() {
        return $this->hasOne(UnitBisnisProfile::class);
    }
}
```

---

## 🔍 Query Examples

### Get All Unit Bisnis Data

```php
$user = Auth::user();
$unitBisnis = UnitBisnisProfile::where('user_id', $user->id)->firstOrFail();
$pesanans = Pesanan::where('user_id', $user->id)
    ->where('status', '!=', 'dibatalkan')
    ->with('menuAktif.masterMakanan')
    ->get();
```

### Calculate Statistics

```php
$totalPorsi = $pesanans->sum('jumlah_porsi');
$totalKg = $pesanans->sum(function ($pesanan) {
    return ($pesanan->menuAktif->masterMakanan->berat ?? 0) 
        * $pesanan->jumlah_porsi;
});
```

---

## ⚠️ Error Codes

| Code | Meaning | Example |
|------|---------|---------|
| 200 | Success | Profile loaded |
| 302 | Redirect | After successful update |
| 422 | Validation Failed | Invalid form data |
| 401 | Unauthorized | User not authenticated |
| 403 | Forbidden | Wrong role/permissions |
| 404 | Not Found | Resource doesn't exist |
| 500 | Server Error | Database error |

---

## 📝 Response Headers

All responses include:
```
Content-Type: text/html; charset=UTF-8
Content-Security-Policy: ...
X-Content-Type-Options: nosniff
X-Frame-Options: SAMEORIGIN
X-CSRF-TOKEN: {token}
```

---

## 🔐 Security Considerations

1. **CSRF Protection**
   - All POST/PUT/DELETE require CSRF token
   - Token included in `X-CSRF-TOKEN` header or form

2. **Authentication**
   - All endpoints require `auth` middleware
   - User must be logged in

3. **Authorization**
   - Only `unit_bisnis` role can access
   - Can only modify own profile

4. **Input Validation**
   - Server-side validation required
   - File upload validation (size, type)
   - Email format validation

5. **Password Security**
   - Always hashed with bcrypt
   - Never logged or displayed
   - Requires current password confirmation

---

## 📚 Related Documentation

- [UNIT_BISNIS_DASHBOARD_DOCS.md](./UNIT_BISNIS_DASHBOARD_DOCS.md) - Full technical docs
- [UNIT_BISNIS_QUICK_START.md](./UNIT_BISNIS_QUICK_START.md) - Quick start guide
- [Laravel API Documentation](https://laravel.com/docs/11.x)

---

**Version:** 1.0.0  
**Last Updated:** May 22, 2026  
**Status:** ✅ Complete
