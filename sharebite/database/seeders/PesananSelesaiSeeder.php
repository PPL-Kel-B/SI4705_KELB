<?php

namespace Database\Seeders;

use App\Models\MasterMakanan;
use App\Models\MenuAktif;
use App\Models\Pesanan;
use App\Models\UnitBisnisProfile;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class PesananSelesaiSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Ambil unit bisnis Lestari Food
        $unitUser = User::where('email', 'unit@sharebite.com')->first();

        if (!$unitUser) {
            $this->command->error('User unit@sharebite.com tidak ditemukan. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        // 2. Buat atau ambil profil unit bisnis
        $unitBisnis = UnitBisnisProfile::firstOrCreate(
            ['user_id' => $unitUser->id],
            [
                'nama_usaha'           => 'Lestari Food',
                'nama_bisnis'          => 'Lestari Food',
                'tipe_bisnis'          => 'Restoran',
                'email_bisnis'         => 'lestari@gmail.com',
                'no_telepon'           => '+6282178830750',
                'lokasi_lat'           => '-6.9271',
                'lokasi_lng'           => '107.6411',
                'radius_penjemputan'   => 15,
                'jam_buka'             => '08:00',
                'jam_tutup'            => '21:00',
                'foto_bisnis'          => 'images/placeholder-bisnis.jpg',
                'verified'             => true,
                'status_verifikasi'    => 'terverifikasi',
                'tahun_bergabung'      => 2023,
                'notifikasi_aktif'     => true,
                'notifikasi_pesanan'   => true,
                'notifikasi_penjemputan' => true,
            ]
        );

        // 3. Ambil user pembeli (individu)
        $pembeli = User::where('email', 'individu@sharebite.com')->first();

        if (!$pembeli) {
            $this->command->error('User individu@sharebite.com tidak ditemukan. Jalankan DatabaseSeeder terlebih dahulu.');
            return;
        }

        // 4. Buat data makanan untuk testing
        $makananData = [
            ['nama' => 'Nasi Box Ayam',   'kategori' => 'Makanan Berat', 'harga' => 15000, 'berat' => 0.5],
            ['nama' => 'Roti Tawar Sisa', 'kategori' => 'Roti & Kue',    'harga' => 5000,  'berat' => 0.3],
            ['nama' => 'Sayur Sop',       'kategori' => 'Lauk Pauk',     'harga' => 8000,  'berat' => 0.4],
        ];

        foreach ($makananData as $data) {
            // 5. Buat MasterMakanan
            $master = MasterMakanan::firstOrCreate(
                [
                    'unit_bisnis_id' => $unitBisnis->id,
                    'nama_makanan'   => $data['nama'],
                ],
                [
                    'kategori' => $data['kategori'],
                    'harga'    => $data['harga'],
                    'berat'    => $data['berat'],
                    'deskripsi' => 'Data test untuk ' . $data['nama'],
                ]
            );

            // 6. Buat MenuAktif (sudah ditutup karena ini historical)
            $menuAktif = MenuAktif::firstOrCreate(
                [
                    'master_makanan_id' => $master->id,
                    'unit_bisnis_id'    => $unitBisnis->id,
                ],
                [
                    'is_gratis'         => false,
                    'harga_jual'        => $data['harga'],
                    'stok_porsi'        => 0,
                    'batas_pengambilan' => now()->subDay(),
                    'status'            => 'ditutup',
                ]
            );

            // 7. Buat 3 pesanan selesai per menu
            $pesananList = [
                ['porsi' => 2, 'hari' => 3],
                ['porsi' => 3, 'hari' => 2],
                ['porsi' => 1, 'hari' => 1],
            ];

            foreach ($pesananList as $p) {
                Pesanan::create([
                    'menu_aktif_id'  => $menuAktif->id,
                    'unit_bisnis_id' => $unitBisnis->id,
                    'user_id'        => $pembeli->id,
                    'jumlah_porsi'   => $p['porsi'],
                    'total_harga'    => $data['harga'] * $p['porsi'],
                    'status'         => 'selesai',
                    'kode_unik'      => strtoupper(Str::random(8)),
                    'waktu_pesan'    => now()->subDays($p['hari']),
                    'waktu_diambil'  => now()->subDays($p['hari'])->toDateString(),
                ]);
            }
        }

        // Hitung total untuk ditampilkan di output
        $totalPorsi = Pesanan::where('unit_bisnis_id', $unitBisnis->id)
            ->where('status', 'selesai')
            ->sum('jumlah_porsi');

        $totalKg = Pesanan::where('unit_bisnis_id', $unitBisnis->id)
            ->where('status', 'selesai')
            ->with('menuAktif.masterMakanan')
            ->get()
            ->sum(fn($p) => ($p->menuAktif->masterMakanan->berat ?? 0) * $p->jumlah_porsi);

        $this->command->info('✓ Seeder berhasil!');
        $this->command->info("  Unit Bisnis  : {$unitBisnis->nama_usaha}");
        $this->command->info("  Total Pesanan: " . Pesanan::where('unit_bisnis_id', $unitBisnis->id)->where('status', 'selesai')->count() . " pesanan selesai");
        $this->command->info("  Dampak Sosial: {$totalPorsi} porsi makanan");
        $this->command->info("  Dampak Lingkungan: {$totalKg} kg diselamatkan");
        $this->command->info('');
        $this->command->info('→ Buka http://127.0.0.1:8000/unit/profil dan login dengan:');
        $this->command->info('  Email   : unit@sharebite.com');
        $this->command->info('  Password: password');
    }
}
