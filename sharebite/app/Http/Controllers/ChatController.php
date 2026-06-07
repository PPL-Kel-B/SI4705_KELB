<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class ChatController extends Controller
{
    // ========================
    // ADMIN CHAT FUNCTIONS
    // ========================

    /**
     * Display all conversations for admin
     * Shows list of users who have chatted with admin
     */
    public function index($userId = null)
    {
        $currentUser = Auth::user();
        
        // Get all unique conversations (both sent and received)
        $conversations = Chat::where(function ($query) use ($currentUser) {
            $query->where('sender_id', $currentUser->id)
                  ->orWhere('receiver_id', $currentUser->id);
        })
        ->with(['sender', 'receiver'])
        ->latest('waktu')
        ->get()
        ->groupBy(function ($chat) use ($currentUser) {
            return $chat->sender_id === $currentUser->id ? $chat->receiver_id : $chat->sender_id;
        })
        ->map(function ($group) use ($currentUser) {
            $lastChat = $group->first();
            $otherUserId = $lastChat->sender_id === $currentUser->id ? $lastChat->receiver_id : $lastChat->sender_id;
            $otherUser = $lastChat->sender_id === $currentUser->id ? $lastChat->receiver : $lastChat->sender;
            
            // Count unread messages for current user
            $unreadCount = $group->where('receiver_id', $currentUser->id)
                                ->where('is_read', false)
                                ->count();
            
            if ($lastChat->gambar && $lastChat->pesan) {

                $lastMessage = '📷 ' . $lastChat->pesan;

            } elseif ($lastChat->gambar) {

                $lastMessage = '📷 Mengirim gambar';

            } else {

                $lastMessage = $lastChat->pesan;
            }

            return [
                'user_id' => $otherUserId,
                'user' => $otherUser,
                'last_message' => $lastMessage,
                'last_time' => $lastChat->waktu,
                'unread_count' => $unreadCount,
                'message_count' => count($group)
            ];
        })
        ->sortByDesc(function ($item) {
            return $item['last_time'];
        });

        // If specific user ID provided, get chat details
        $otherUser = null;
        $messages = collect();
        if ($userId) {
            $otherUser = User::findOrFail($userId);
            
            // Get all messages between current user and the other user
            $messages = Chat::where(function ($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $currentUser->id)
                      ->where('receiver_id', $otherUser->id);
            })
            ->orWhere(function ($query) use ($currentUser, $otherUser) {
                $query->where('sender_id', $otherUser->id)
                      ->where('receiver_id', $currentUser->id);
            })
            ->orderBy('waktu', 'asc')
            ->get();

            // Mark all received messages as read
            Chat::where('sender_id', $otherUser->id)
                ->where('receiver_id', $currentUser->id)
                ->where('is_read', false)
                ->update(['is_read' => true]);
        }

        return view('chat.admin', compact('conversations', 'otherUser', 'messages'));
    }

    /**
     * Store a new message (for admin)
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'pesan' => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);
        if (
            empty(trim($request->pesan)) &&
            !$request->hasFile('gambar')
        ) {
            return back()->with(
                'error',
                'Tidak dapat mengirim pesan kosong.'
            );
        }
        $currentUser = Auth::user();
        $path = null;
            if ($request->hasFile('gambar')) {
                $path = $request->file('gambar')
                    ->store('chat-images', 'public');
            }
        $chat = Chat::create([
            'sender_id' => $currentUser->id,
            'receiver_id' => $validated['receiver_id'],
            'pesan' => $validated['pesan'],
            'gambar' => $path,
            'waktu' => Carbon::now(),
        ]);

        if ($request->wantsJson()) {
            return response()->json($chat, 201);
        }

        return back()->with('success', 'Pesan berhasil dikirim');
    }

    // ========================
    // USER CHAT FUNCTIONS (Unit Bisnis & Individu)
    // ========================

    /**
     * Display chat with admin (for unit bisnis and individu)
     */
    public function chatWithAdmin()
    {
        $currentUser = Auth::user();
        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->with('error', 'Admin tidak ditemukan');
        }

        $messages = Chat::where(function ($query) use ($currentUser, $admin) {
            $query->where('sender_id', $currentUser->id)
                ->where('receiver_id', $admin->id);
        })
        ->orWhere(function ($query) use ($currentUser, $admin) {
            $query->where('sender_id', $admin->id)
                ->where('receiver_id', $currentUser->id);
        })
        ->orderBy('waktu', 'asc')
        ->get();

        Chat::where('sender_id', $admin->id)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        // PILIH VIEW BERDASARKAN ROLE
        if ($currentUser->role === 'unit_bisnis') {
            return view('chat.unit', compact('admin', 'messages'));
        }

        if (
            $currentUser->role === 'individu' ||
            $currentUser->role === 'komunitas'
        ) {
            return view('chat.user', compact('admin', 'messages'));
        }

        return redirect()->back()->with('error', 'Role tidak dikenali');
    }

    /**
     * Send message to admin (for unit bisnis and individu)
     */
    public function sendToAdmin(Request $request)
    {
        $request->validate([
            'pesan'  => 'nullable|string|max:1000',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png,gif,webp|max:2048',
        ]);

        if (
            empty(trim($request->pesan)) &&
            !$request->hasFile('gambar')
        ) {
            return back()->with(
                'error',
                'Tidak dapat mengirim pesan kosong.'
            );
        }

        $currentUser = Auth::user();

        $admin = User::where('role', 'admin')->first();

        if (!$admin) {
            return back()->with('error', 'Admin tidak ditemukan');
        }

        $gambarPath = null;

        if ($request->hasFile('gambar')) {
            $gambarPath = $request->file('gambar')
                ->store('chat-images', 'public');
        }

        Chat::create([
            'sender_id'   => $currentUser->id,
            'receiver_id' => $admin->id,
            'pesan'       => $request->pesan,
            'gambar'      => $gambarPath,
            'waktu'       => now(),
            'is_read'     => false,
        ]);

        return back()->with('success', 'Pesan berhasil dikirim');
    }

    /**
     * Mark conversation as read
     */
    public function markAsRead($userId)
    {
        $currentUser = Auth::user();

        Chat::where('sender_id', $userId)
            ->where('receiver_id', $currentUser->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);

        return response()->json(['success' => true]);
    }
}
