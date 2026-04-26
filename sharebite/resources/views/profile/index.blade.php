<x-profile-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold tracking-tight text-emerald-900">
            Profile Saya
        </h2>
    </x-slot>

    <div class="space-y-6">
        <x-profile.profile-header-card :profile="$profile" />

        <section class="grid grid-cols-1 gap-4 sm:grid-cols-2">
            <x-profile.stat-card title="Kontribusi Berbagi" :value="$stats['contribution_items']" unit="Item" theme="emerald">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 640 640"
                        aria-hidden="true">
                        <path
                            d="M311.6 95C297.5 75.5 274.9 64 250.9 64C209.5 64 176 97.5 176 138.9L176 141.3C176 205.7 258 274.7 298.2 304.6C311.2 314.3 328.7 314.3 341.7 304.6C381.9 274.6 463.9 205.7 463.9 141.3L463.9 138.9C463.9 97.5 430.4 64 389 64C365 64 342.4 75.5 328.3 95L320 106.7L311.6 95zM141.3 405.5L98.7 448L64 448C46.3 448 32 462.3 32 480L32 544C32 561.7 46.3 576 64 576L384.5 576C413.5 576 441.8 566.7 465.2 549.5L591.8 456.2C609.6 443.1 613.4 418.1 600.3 400.3C587.2 382.5 562.2 378.7 544.4 391.8L424.6 480L312 480C298.7 480 288 469.3 288 456C288 442.7 298.7 432 312 432L384 432C401.7 432 416 417.7 416 400C416 382.3 401.7 368 384 368L231.8 368C197.9 368 165.3 381.5 141.3 405.5z" />
                    </svg>
                </x-slot:icon>
            </x-profile.stat-card>

            <x-profile.stat-card title="Dampak Ekologis" :value="$stats['eco_impact_kg']" unit="Kg Sampah" theme="amber">
                <x-slot:icon>
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 fill-current" viewBox="0 0 640 640"
                        aria-hidden="true">
                        <path
                            d="M535.3 70.7C541.7 64.6 551 62.4 559.6 65.2C569.4 68.5 576 77.7 576 88L576 274.9C576 406.1 467.9 512 337.2 512C260.2 512 193.8 462.5 169.7 393.3C134.3 424.1 112 469.4 112 520C112 533.3 101.3 544 88 544C74.7 544 64 533.3 64 520C64 445.1 102.2 379.1 160.1 340.3C195.4 316.7 237.5 304 280 304L360 304C373.3 304 384 293.3 384 280C384 266.7 373.3 256 360 256L280 256C240.3 256 202.7 264.8 169 280.5C192.3 210.5 258.2 160 336 160C402.4 160 451.8 137.9 484.7 116C503.9 103.2 520.2 87.9 535.4 70.7z" />
                    </svg>
                </x-slot:icon>
            </x-profile.stat-card>
        </section>

        <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <x-profile.personal-info-card :profile="$profile" />
            <x-profile.security-card :security="$security" />
        </section>

        <section class="grid grid-cols-1 gap-4 lg:grid-cols-3">
            <x-profile.address-card :profile="$profile" />
            <x-profile.help-card />
        </section>
    </div>
</x-profile-layout>
