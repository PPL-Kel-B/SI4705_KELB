<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use App\Models\User;
use App\Models\Pesanan;
use Illuminate\Support\Facades\Auth;

$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$userId = 1; // Try with user ID 1 or find the first user
$user = User::find($userId);

if (!$user) {
    echo "No user found with ID $userId\n";
    exit;
}

echo "Checking stats for User: " . $user->name . " (Role: " . $user->role . ")\n";

$pesanans = Pesanan::where('user_id', $user->id)
    ->where('status', '!=', 'dibatalkan')
    ->with('menuAktif.masterMakanan')
    ->get();

echo "Total Pesanan Found: " . $pesanans->count() . "\n";

foreach ($pesanans as $p) {
    $berat = $p->menuAktif->masterMakanan->berat ?? 'N/A';
    echo "Order ID: {$p->id}, Porsi: {$p->jumlah_porsi}, Status: {$p->status}, Berat: {$berat}\n";
}

$contribution_items = $pesanans->sum('jumlah_porsi');
$eco_impact_kg = $pesanans->sum(function ($pesanan) {
    $beratPerItem = $pesanan->menuAktif->masterMakanan->berat ?? 0;
    return $beratPerItem * $pesanan->jumlah_porsi;
});

echo "Contribution Items: $contribution_items\n";
echo "Eco Impact KG: $eco_impact_kg\n";
