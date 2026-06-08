@extends('layouts.unit_bisnis')

@section('title', 'Chat Pusat Bantuan')

@section('content')

<div class="max-w-screen-2xl mx-auto px-6 py-8">
    {{-- AREA CHAT --}}
    <div class="col-span-8">
    <div class="bg-white rounded-3xl shadow-sm overflow-hidden flex flex-col h-[calc(100vh-180px)]">
            {{-- Header --}}
            <div class="p-6 border-b bg-gray-50">
                <div class="flex items-center gap-3">

                    <div class="w-12 h-12 rounded-full bg-[#0A2E1F] text-white flex items-center justify-center font-bold">
                        AD
                    </div>

                    <div>
                        <h3 class="font-bold">
                            {{ $admin->name }}
                        </h3>

                        <p class="text-sm text-gray-500">
                            Tim Dukungan ShareBite
                        </p>
                    </div>

                </div>
            </div>

            {{-- Messages --}}
            <div
                id="messagesContainer"
                class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50 min-h-[500px]"
            >
                @forelse($messages as $message)
                    @if($message->sender_id == auth()->id())
                        <div class="flex justify-end">
                            <div class="bg-[#1CB764] text-white px-4 py-3 rounded-2xl shadow-sm max-w-md">
                                @if($message->gambar)
                                    <img
                                        src="{{ asset('storage/'.$message->gambar) }}"
                                        class="rounded-xl mb-2 max-w-[260px]"
                                        onclick="openImage(this.src)"
                                    >
                                @endif
                                @if($message->pesan)

                                    <p>{{ $message->pesan }}</p>

                                @elseif($message->gambar)

                                    <p class="text-xs opacity-70">
                                        📷 Foto
                                    </p>
                                @endif
                            </div>
                        </div>

                    @else

                        <div class="flex justify-start">

                            <div class="bg-white px-4 py-3 rounded-2xl shadow-sm max-w-md">

                                @if($message->gambar)
                                    <img
                                        src="{{ asset('storage/'.$message->gambar) }}"
                                        class="rounded-xl mb-2 max-w-xs"
                                        onclick="openImage(this.src)"
                                    >
                                @endif

                                @if($message->pesan)
                                    <p>{{ $message->pesan }}</p>
                                @endif

                                <div class="text-xs text-gray-500 text-right mt-2">
                                    {{ $message->waktu->format('H:i') }}
                                </div>

                            </div>

                        </div>

                    @endif

                @empty

                    <div class="h-full flex items-center justify-center">

                        <div class="text-center">

                            <h3 class="text-xl font-bold text-gray-600">
                                Belum ada pesan
                            </h3>

                            <p class="text-gray-400">
                                Mulai percakapan dengan admin
                            </p>

                        </div>

                    </div>

                @endforelse

            </div>


            @if(session('error'))
                <div class="mx-4 mb-3 bg-red-100 border border-red-300 text-red-700 px-4 py-3 rounded-xl">
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="mx-4 mb-3 bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
                    {{ session('success') }}
                </div>
            @endif


            {{-- Form --}}
            <form
                action="{{ route('unit.chat.send') }}"
                method="POST"
                enctype="multipart/form-data"
                class="border-t border-gray-100 bg-white p-4"
            >
                @csrf

                {{-- Preview Gambar --}}
                <div
                    id="imagePreviewWrapper"
                    class="hidden mb-3 relative inline-block"
                >
                    <img
                        id="imagePreview"
                        class="max-h-40 rounded-2xl border shadow-sm"
                    >

                    <button
                        type="button"
                        id="removeImage"
                        class="absolute -top-2 -right-2 w-7 h-7 rounded-full bg-red-500 text-white text-sm"
                    >
                        ✕
                    </button>
                </div>

                {{-- Input Area --}}
                <div class="flex items-center gap-3">

                    {{-- Upload --}}
                    <label
                        for="gambar"
                        class="w-12 h-12 flex items-center justify-center border rounded-2xl cursor-pointer hover:bg-gray-50"
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
                        class="w-12 h-12 border rounded-2xl hover:bg-gray-50"
                    >
                        😊
                    </button>

                    <div class="relative">

                        <emoji-picker
                            id="emojiPicker"
                            class="hidden absolute bottom-16 left-0 z-50 shadow-2xl rounded-2xl"
                        ></emoji-picker>

                    </div>

                    {{-- Textarea --}}
                    <div class="flex-1 relative">

                        <textarea
                            id="pesan"
                            name="pesan"
                            rows="1"
                            maxlength="1000"
                            placeholder="Jelaskan masalah atau pertanyaan Anda..."
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
                        class="bg-[#1cb764] hover:bg-[#15a85d] text-white px-8 py-3 rounded-2xl font-semibold"
                    >
                        Kirim
                    </button>

                </div>
            </form>
        </div>
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
<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@^1/index.js"></script>

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
            !emojiBtn.contains(e.target)
        ){
            emojiPicker.classList.add('hidden');
        }

    });

});
</script>
<!-- Modal Preview -->
<div
    id="imageModal"
    class="fixed inset-0 bg-black/90 hidden items-center justify-center z-50"
>

    <button
        onclick="closeImage()"
        class="absolute top-5 right-5 text-white text-4xl"
    >
        &times;
    </button>

    <img
        id="modalImage"
        class="max-w-[90vw] max-h-[90vh] rounded-xl"
    >

</div>
<script>

function openImage(src)
{
    document
        .getElementById('modalImage')
        .src = src;

    document
        .getElementById('imageModal')
        .classList.remove('hidden');

    document
        .getElementById('imageModal')
        .classList.add('flex');
}

function closeImage()
{
    document
        .getElementById('imageModal')
        .classList.add('hidden');

    document
        .getElementById('imageModal')
        .classList.remove('flex');
}

</script>

@endsection