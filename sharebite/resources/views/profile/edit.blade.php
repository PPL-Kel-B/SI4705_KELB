<x-profile-layout :back-url="route('profile.index')">
    <x-slot name="header">
        <h2 class="text-2xl font-bold tracking-tight text-emerald-900">
            Edit Profile
        </h2>
    </x-slot>

    <div class="space-y-6">
        <form method="POST" action="{{ route('profile.update') }}" id="profile-form">
            @csrf
            @method('PATCH')

            <x-profile.edit-avatar-card :profile="$profile" />

            <x-profile.edit-personal-info-card :profile="$profile" />
        </form>

        <x-profile.security-card-edit />
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('profile-form');
            const submitBtn = document.getElementById('submit-button');
            const inputs = form.querySelectorAll('.form-input-watch'); // Pakai class baru ini

            const initialValues = {};
            inputs.forEach(input => {
                initialValues[input.id] = input.value;
            });

            const checkChanges = () => {
                let hasChanged = false;
                inputs.forEach(input => {
                    if (input.value !== initialValues[input.id]) {
                        hasChanged = true;
                    }
                });
                submitBtn.disabled = !hasChanged;
            };

            inputs.forEach(input => {
                input.addEventListener('input', checkChanges);
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @if (session('status') === 'profile-updated')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: 'Profil kamu berhasil diperbarui.',
                    confirmButtonColor: '#059669',
                    confirmButtonText: 'Tutup',
                    background: '#ffffff',
                    backdrop: `rgba(15, 23, 42, 0.4)`,
                    timer: 3000,
                    timerProgressBar: true,
                    customClass: {
                        popup: 'rounded-3xl shadow-xl'
                    }
                });
            });
        </script>
    @endif
</x-profile-layout>
