@extends('layouts.admin')

@section('title', 'Chat Pusat Bantuan')

@section('content')
<div class="max-w-screen-2xl mx-auto px-6 py-8">
    <!-- Header Card -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-50 p-6 mb-6">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 bg-[#eefcf4] rounded-full flex items-center justify-center text-[#1cb764]">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z" />
                </svg>
            </div>
            <div>
                <h1 class="text-2xl font-extrabold text-gray-800">Chat Pusat Bantuan</h1>
                <p class="text-sm text-gray-500">Hubungi admin untuk bantuan teknis dan operasional</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-12 gap-6">
        {{-- SIDEBAR --}}
        <div class="col-span-4">
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 h-[700px] overflow-hidden">

                <div class="p-5 border-b">
                    <h2 class="font-bold text-xl">
                        Percakapan
                    </h2>
                </div>

                <div class="overflow-y-auto">

                    @forelse($conversations as $conversation)

                        <a
                            href="{{ route('admin.chat.show', $conversation['user_id']) }}"
                            class="flex items-center gap-3 p-4 border-b hover:bg-gray-50"
                        >

                            <img
                                src="https://ui-avatars.com/api/?name={{ urlencode($conversation['user']->name) }}&background=1cb764&color=fff"
                                class="w-12 h-12 rounded-full"
                            >

                            <div class="flex-1">

                                <h4 class="font-semibold">
                                    {{ $conversation['user']->name }}
                                </h4>

                                <p class="text-sm text-gray-500 truncate">
                                    {{ $conversation['last_message'] ?? 'Belum ada pesan' }}
                                </p>

                            </div>

                            @if($conversation['unread_count'] > 0)
                                <span class="bg-green-500 text-white text-xs rounded-full px-2 py-1">
                                    {{ $conversation['unread_count'] }}
                                </span>
                            @endif

                        </a>

                    @empty

                        <div class="p-6 text-center text-gray-400">
                            Belum ada percakapan
                        </div>

                    @endforelse
                </div>
            </div>
        </div>

        {{-- AREA CHAT --}}
    <!-- Chat Container -->
    <div class="col-span-8">
    <div class="bg-white rounded-3xl shadow-sm border border-gray-50 overflow-hidden flex flex-col h-[700px]">
        @if($otherUser)
            <div class="p-6 border-b border-gray-100 bg-gray-50">
                <div class="flex items-center gap-3">

                    <img
                        src="https://ui-avatars.com/api/?name={{ urlencode($otherUser->name) }}&background=0a2e1f&color=fff"
                        class="w-12 h-12 rounded-full"
                    >

                    <div>
                        <h3 class="font-bold text-gray-800">
                            {{ $otherUser->name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            {{ ucfirst(str_replace('_',' ',$otherUser->role)) }}
                        </p>
                    </div>

                </div>
            </div>
        @else
            <div class="flex-1 flex items-center justify-center">

                <div class="text-center">

                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                    </svg>

                    <h3 class="text-xl font-bold text-gray-700">
                        Pilih Percakapan
                    </h3>

                    <p class="text-gray-400">
                        Pilih pengguna dari daftar percakapan
                    </p>

                </div>

            </div>
        @endif
        <!-- Messages Area -->
        <div
            id="messagesContainer"
            class="flex-1 overflow-y-auto px-6 py-4 space-y-4 bg-gradient-to-b from-white to-gray-50"
        >
            @if($otherUser)

                @forelse($messages as $message)

                    @if($message->sender_id == auth()->id())

                        {{-- Pesan Admin --}}
                        <div class="flex justify-end">

                            <div class="bg-[#1CB764] text-white px-3 py-3 rounded-2xl shadow-sm max-w-md">

                                @if($message->gambar)
                                    <img
                                        src="{{ asset('storage/'.$message->gambar) }}"
                                        class="rounded-xl max-w-[260px] max-h-[320px] object-cover"
                                    >
                                @endif

                                @if($message->pesan)
                                    <p class="mt-2">
                                        {{ $message->pesan }}
                                    </p>
                                @endif

                                <div class="text-xs opacity-70 mt-2 text-right">
                                    {{ $message->waktu->format('H:i') }}
                                </div>

                            </div>

                        </div>

                    @else

                        {{-- Pesan User --}}
                        <div class="flex justify-start">

                            <div class="bg-gray-100 px-3 py-3 rounded-2xl shadow-sm max-w-md">

                                @if($message->gambar)
                                    <img
                                        src="{{ asset('storage/'.$message->gambar) }}"
                                        class="rounded-xl max-w-[260px] max-h-[320px] object-cover"
                                    >
                                @endif

                                @if($message->pesan)
                                    <p class="mt-2">
                                        {{ $message->pesan }}
                                    </p>
                                @endif

                                <div class="text-xs text-gray-500 mt-2 text-right">
                                    {{ $message->waktu->format('H:i') }}
                                </div>

                            </div>

                        </div>

                    @endif

                @empty

                    <div class="flex items-center justify-center h-full">

                        <div class="text-center text-gray-400">
                            Belum ada pesan
                        </div>

                    </div>

                @endforelse

            @endif

        </div>
        <div
            id="imagePreviewWrapper"
            class="hidden px-6 pt-4"
        >
            <div class="relative inline-block">

                <img
                    id="imagePreview"
                    class="max-h-40 rounded-2xl border shadow-sm"
                >

                <button
                    type="button"
                    id="removeImage"
                    class="absolute -top-2 -right-2 bg-red-500 text-white w-7 h-7 rounded-full"
                >
                    ✕
                </button>

            </div>
        </div>
        <!-- Message Input Form -->
        @if($otherUser)

        <form
            action="{{ route('admin.chat.store') }}"
            method="POST"
            enctype="multipart/form-data"
            class="px-6 py-4 border-t border-gray-100 bg-gray-50"
        >
            
            @csrf

            @if($otherUser)
                <input
                    type="hidden"
                    name="receiver_id"
                    value="{{ $otherUser->id }}"
                >
            @endif
            <div class="flex items-center gap-3">

                {{-- Upload Gambar --}}
                <label
                    for="gambar"
                    class="cursor-pointer bg-white border border-gray-200 rounded-2xl px-4 py-3 hover:bg-gray-50"
                    title="Kirim Gambar"
                >
                    📷
                </label>

                <input
                    type="file"
                    id="gambar"
                    name="gambar"
                    accept="image/*"
                    class="hidden"
                >

                {{-- Emoji --}}
                <button
                    type="button"
                    id="emojiBtn"
                    class="bg-white border border-gray-200 rounded-2xl px-4 py-3 hover:bg-gray-50"
                >
                    😊
                </button>

                <div class="relative">

                    <emoji-picker
                        id="emojiPicker"
                        class="hidden absolute bottom-16 left-0 z-50 shadow-2xl rounded-2xl"
                    ></emoji-picker>

                </div>

                {{-- Pesan --}}
                <div class="flex-1 relative">
                    <textarea
                        id="pesan"
                        name="pesan"
                        rows="1"
                        maxlength="1000"
                        placeholder="Ketik pesan..."
                        class="w-full border rounded-2xl px-5 py-3 pr-20 resize-none focus:outline-none focus:ring-2 focus:ring-[#1cb764]"
                    ></textarea>

                    <span
                        id="charCount"
                        class="absolute bottom-2 right-4 text-xs text-gray-400"
                    >
                        0/1000
                    </span>

                </div>
                {{-- Kirim --}}
                <button
                    type="submit"
                    class="bg-[#1cb764] hover:bg-[#15a85d] text-white px-6 py-3 rounded-2xl font-semibold"
                >
                    Kirim
                </button>

            </div>
        </form>
        @endif
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {

    const textarea = document.getElementById('pesan');
    const charCount = document.getElementById('charCount');

    if (textarea && charCount) {

        textarea.addEventListener('input', function () {

            charCount.textContent =
                this.value.length + '/1000';

            this.style.height = 'auto';
            this.style.height =
                this.scrollHeight + 'px';
        });

    }

    const gambarInput =
        document.getElementById('gambar');

    const previewWrapper =
        document.getElementById('imagePreviewWrapper');

    const previewImage =
        document.getElementById('imagePreview');

    const removeImage =
        document.getElementById('removeImage');

    if (gambarInput) {

        gambarInput.addEventListener('change', function () {

            const file = this.files[0];

            if (!file) {
                previewWrapper.classList.add('hidden');
                return;
            }

            const reader = new FileReader();

            reader.onload = function (e) {

                previewImage.src = e.target.result;

                previewWrapper.classList.remove('hidden');
            };

            reader.readAsDataURL(file);
        });

    }

    if (removeImage) {

        removeImage.addEventListener('click', function () {

            gambarInput.value = '';

            previewImage.src = '';

            previewWrapper.classList.add('hidden');
        });

    }

});
</script>
<script>
document.addEventListener('DOMContentLoaded', () => {

    const emojiBtn = document.getElementById('emojiBtn');
    const emojiPicker = document.getElementById('emojiPicker');
    const textarea = document.getElementById('pesan');

    if (!emojiBtn || !emojiPicker || !textarea) return;

    emojiBtn.addEventListener('click', () => {

        emojiPicker.classList.toggle('hidden');

    });

    emojiPicker.addEventListener('emoji-click', event => {

        textarea.value += event.detail.unicode;

        textarea.dispatchEvent(
            new Event('input')
        );

        textarea.focus();
    });

    document.addEventListener('click', function(e){

        if(
            !emojiPicker.contains(e.target) &&
            e.target !== emojiBtn
        ){
            emojiPicker.classList.add('hidden');
        }

    });

});
</script>
<link
 rel="stylesheet"
 href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.css"
/>

<script
 src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.2/cropper.min.js">
</script>
<style>
    /* Custom scrollbar untuk messages */
    #messagesContainer::-webkit-scrollbar {
        width: 6px;
    }
    #messagesContainer::-webkit-scrollbar-track {
        background: transparent;
    }
    #messagesContainer::-webkit-scrollbar-thumb {
        background: #e2e8f0;
        border-radius: 3px;
    }
    #messagesContainer::-webkit-scrollbar-thumb:hover {
        background: #cbd5e1;
    }
</style>
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>
@endsection
