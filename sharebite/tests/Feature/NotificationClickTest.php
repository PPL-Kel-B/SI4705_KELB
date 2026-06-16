<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\MenuAktif;
use App\Models\MasterMakanan;
use App\Models\UnitBisnisProfile;
use App\Notifications\MakananDekatNotification;
use App\Notifications\ChatMasukNotification;
use App\Models\Chat;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationClickTest extends TestCase
{
    use RefreshDatabase;

    protected $user;
    protected $unitProfile;
    protected $menu;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'role' => 'individu',
            'latitude' => '-6.9271',
            'longitude' => '107.6186'
        ]);

        $unitUser = User::factory()->create([
            'role' => 'unit_bisnis',
            'latitude' => '-6.9300',
            'longitude' => '107.6200',
        ]);
        
        $this->unitProfile = UnitBisnisProfile::create([
            'user_id' => $unitUser->id,
            'nama_usaha' => 'Toko Roti Wangi',
            'jenis_usaha' => 'Kuliner',
            'radius_penjemputan' => 5,
            'jam_buka' => '00:00',
            'jam_tutup' => '23:59',
        ]);

        $master = MasterMakanan::create([
            'unit_bisnis_id' => $this->unitProfile->id,
            'nama_makanan' => 'Roti Manis',
            'kategori' => 'Makanan Ringan',
            'harga' => 12000,
            'berat' => 0.25,
        ]);

        $this->menu = MenuAktif::create([
            'master_makanan_id' => $master->id,
            'unit_bisnis_id' => $this->unitProfile->id,
            'is_gratis' => false,
            'harga_jual' => 10000,
            'stok_porsi' => 10,
            'batas_pengambilan' => now()->addHours(3),
            'status' => 'aktif',
        ]);
    }

    public function test_menu_notification_click_redirects_to_detail_if_available()
    {
        $this->user->notify(new MakananDekatNotification($this->menu));
        $notification = $this->user->unreadNotifications()->first();

        $response = $this->actingAs($this->user)
            ->get(route('notifications.click', $notification->id));

        // Assert redirect to food detail
        $response->assertRedirect(route('user.makanan.detail', $this->menu->id));

        // Assert notification marked as read
        $this->assertNull($this->user->fresh()->unreadNotifications()->first());
    }

    public function test_menu_notification_click_redirects_to_dashboard_with_error_if_out_of_stock()
    {
        // Set stock to 0
        $this->menu->update(['stok_porsi' => 0]);

        $this->user->notify(new MakananDekatNotification($this->menu));
        $notification = $this->user->unreadNotifications()->first();

        $response = $this->actingAs($this->user)
            ->get(route('notifications.click', $notification->id));

        // Assert redirect to dashboard with error session
        $response->assertRedirect(route('user.dashboard'));
        $response->assertSessionHas('error_popup', 'makanan_tutup');

        // Assert notification marked as read anyway
        $this->assertNull($this->user->fresh()->unreadNotifications()->first());
    }

    public function test_menu_notification_click_redirects_to_dashboard_with_error_if_expired()
    {
        // Set expired
        $this->menu->update(['batas_pengambilan' => now()->subHour()]);

        $this->user->notify(new MakananDekatNotification($this->menu));
        $notification = $this->user->unreadNotifications()->first();

        $response = $this->actingAs($this->user)
            ->get(route('notifications.click', $notification->id));

        $response->assertRedirect(route('user.dashboard'));
        $response->assertSessionHas('error_popup', 'makanan_tutup');
        $this->assertNull($this->user->fresh()->unreadNotifications()->first());
    }

    public function test_menu_notification_click_redirects_to_dashboard_with_error_if_inactive()
    {
        // Set status to ditutup (valid enum status value)
        $this->menu->update(['status' => 'ditutup']);

        $this->user->notify(new MakananDekatNotification($this->menu));
        $notification = $this->user->unreadNotifications()->first();

        $response = $this->actingAs($this->user)
            ->get(route('notifications.click', $notification->id));

        $response->assertRedirect(route('user.dashboard'));
        $response->assertSessionHas('error_popup', 'makanan_tutup');
        $this->assertNull($this->user->fresh()->unreadNotifications()->first());
    }

    public function test_chat_notification_click_redirects_to_chat_page()
    {
        $chat = Chat::create([
            'sender_id' => $this->unitProfile->user_id,
            'receiver_id' => $this->user->id,
            'pesan' => 'Halo user!',
            'waktu' => now(),
        ]);

        // Chat::create automatically sends the notification in observer/listener, 
        // so we find the automatically created notification.
        $notification = $this->user->unreadNotifications()->first();
        $this->assertNotNull($notification, 'Chat notification was not automatically created.');

        $response = $this->actingAs($this->user)
            ->get(route('notifications.click', $notification->id));

        // Assert redirect to chat page
        $response->assertRedirect(route('user.chat'));
        $this->assertNull($this->user->fresh()->unreadNotifications()->first());
    }
}
