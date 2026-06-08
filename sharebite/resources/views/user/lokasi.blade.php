@php
    $hideSearch = true;
@endphp
@extends('layouts.user')

@section('title', 'Lokasi Saya')

@section('content')
<!-- Include Leaflet CSS and JS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin=""/>
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>

<div class="relative w-full h-[calc(100vh-120px)] rounded-3xl overflow-hidden shadow-sm border border-gray-50">
    <!-- Map Container -->
    <div id="map" class="w-full h-full z-0"></div>

    <!-- Filter Buttons & Controls Overlay -->
    <div class="absolute top-4 left-4 right-4 z-10 flex flex-col md:flex-row justify-between items-start gap-4 pointer-events-none">
        
        <div class="flex flex-col gap-3 pointer-events-auto w-max max-w-full">
            <!-- Search Bar -->
            <div class="relative w-full shadow-md rounded-full bg-white flex flex-col overflow-hidden">
                <div class="flex items-center w-full">
                    <button onclick="fetchNearbyBusinesses()" class="pl-4 pr-2 text-gray-500 hover:text-[#1cb764] transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </button>
                    <input type="text" id="searchInput" placeholder="Cari unit bisnis atau lokasi..." class="w-full py-3 pr-4 text-sm outline-none bg-transparent" onkeyup="if(event.key === 'Enter') fetchNearbyBusinesses()">
                </div>
                <div id="search-error" class="hidden w-full bg-red-50 text-red-500 text-xs text-center py-1 font-medium border-t border-red-100">
                    Unit bisnis tidak ditemukan.
                </div>
            </div>

            <!-- Filter Shortcuts -->
            <div class="flex flex-wrap gap-2" x-data="{ activeFilter: '' }">
            <button @click="activeFilter = (activeFilter === 'Hotel' ? '' : 'Hotel'); updateMapFilter(activeFilter)" 
                    :class="activeFilter === 'Hotel' ? 'bg-[#1cb764] text-white border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-full font-bold text-sm shadow-md border transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>
                Hotel
            </button>
            
            <button @click="activeFilter = (activeFilter === 'Restoran' ? '' : 'Restoran'); updateMapFilter(activeFilter)" 
                    :class="activeFilter === 'Restoran' ? 'bg-[#1cb764] text-white border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-full font-bold text-sm shadow-md border transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>
                Restoran
            </button>

            <button @click="activeFilter = (activeFilter === 'Cafe' ? '' : 'Cafe'); updateMapFilter(activeFilter)" 
                    :class="activeFilter === 'Cafe' ? 'bg-[#1cb764] text-white border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-full font-bold text-sm shadow-md border transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 19h16v2H4zM20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.9 2-2V5c0-1.11-.89-2-2-2zm-2 5h-2V5h2v3z"/></svg>
                Cafe
            </button>

            <button @click="activeFilter = (activeFilter === 'Lainnya' ? '' : 'Lainnya'); updateMapFilter(activeFilter)" 
                    :class="activeFilter === 'Lainnya' ? 'bg-[#1cb764] text-white border-transparent' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                    class="flex items-center gap-2 px-4 py-2.5 rounded-full font-bold text-sm shadow-md border transition-all">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M4 4h6v6H4zm10 0h6v6h-6zM4 14h6v6H4zm10 0h6v6h-6z"/></svg>
                Lainnya
            </button>
        </div>
        </div>

        <!-- Location Button -->
        <button onclick="centerMapToUser()" class="pointer-events-auto bg-white p-3 rounded-full shadow-lg text-gray-700 hover:text-[#1cb764] transition-colors border border-gray-100">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                <circle cx="12" cy="12" r="3" />
            </svg>
        </button>
    </div>

    <!-- Status Banner -->
    <div id="status-banner" class="absolute bottom-4 left-1/2 transform -translate-x-1/2 z-10 bg-white/90 backdrop-blur-sm px-6 py-3 rounded-full shadow-lg border border-gray-100 flex items-center gap-3 transition-opacity duration-300">
        <div class="w-2 h-2 bg-yellow-400 rounded-full animate-pulse"></div>
        <span class="text-sm font-bold text-gray-700" id="status-text">Mencari lokasi Anda...</span>
    </div>
</div>

<style>
    /* Custom Map Styles */
    .leaflet-control-zoom {
        border: none !important;
        box-shadow: 0 4px 6px -1px rgb(0 0 0 / 0.1), 0 2px 4px -2px rgb(0 0 0 / 0.1) !important;
        border-radius: 0.75rem !important;
        overflow: hidden;
        margin-bottom: 2rem !important;
        margin-right: 1rem !important;
    }
    .leaflet-control-zoom a {
        background-color: white !important;
        color: #374151 !important;
        border-bottom: 1px solid #f3f4f6 !important;
    }
    .leaflet-control-zoom a:hover {
        background-color: #f9fafb !important;
        color: #1cb764 !important;
    }
    .leaflet-popup-content-wrapper {
        border-radius: 1rem;
        box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.1), 0 4px 6px -4px rgb(0 0 0 / 0.1);
        padding: 0;
        overflow: hidden;
    }
    .leaflet-popup-content {
        margin: 0;
        line-height: 1.5;
    }
    .leaflet-popup-tip {
        background: white;
    }
    /* Marker Animation */
    .user-marker {
        position: relative;
    }
    .user-marker::before {
        content: '';
        position: absolute;
        width: 100%;
        height: 100%;
        background-color: #3b82f6;
        border-radius: 50%;
        animation: ping 2s cubic-bezier(0, 0, 0.2, 1) infinite;
        opacity: 0.7;
    }
    @keyframes ping {
        75%, 100% {
            transform: scale(2.5);
            opacity: 0;
        }
    }
</style>

<script>
    let map;
    let userMarker;
    let userCircle;
    let businessMarkers = [];
    let currentLat = null;
    let currentLng = null;
    let currentFilter = '';
    let currentSearch = '';

    // Icons SVG Generators based on Category
    function getCategoryIcon(category) {
        const baseColor = '#1a5f40'; // Mockup dark green
        let innerSvg = '';
        
        switch(category) {
            case 'Hotel':
                // Bed icon (filled)
                innerSvg = '<svg x="6" y="6" width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M7 13c1.66 0 3-1.34 3-3S8.66 7 7 7s-3 1.34-3 3 1.34 3 3 3zm12-6h-8v7H3V5H1v15h2v-3h18v3h2v-9c0-2.21-1.79-4-4-4z"/></svg>';
                break;
            case 'Restoran':
                // Fork & Knife icon (filled)
                innerSvg = '<svg x="6" y="6" width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M11 9H9V2H7v7H5V2H3v7c0 2.12 1.66 3.84 3.75 3.97V22h2.5v-9.03C11.34 12.84 13 11.12 13 9V2h-2v7zm5-3v8h2.5v8H21V2c-2.76 0-5 2.24-5 4z"/></svg>';
                break;
            case 'Cafe':
            case 'Kafe':
                // Cup icon (filled)
                innerSvg = '<svg x="6" y="6" width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M4 19h16v2H4zM20 3H4v10c0 2.21 1.79 4 4 4h6c2.21 0 4-1.79 4-4v-3h2c1.11 0 2-.9 2-2V5c0-1.11-.89-2-2-2zm-2 5h-2V5h2v3z"/></svg>';
                break;
            default:
                // Grid/4 dots icon (filled)
                innerSvg = '<svg x="6" y="6" width="20" height="20" viewBox="0 0 24 24" fill="white"><path d="M4 4h6v6H4zm10 0h6v6h-6zM4 14h6v6H4zm10 0h6v6h-6z"/></svg>';
                break;
        }

        return `
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 32 40" width="36" height="45">
                <path d="M16 0C7.163 0 0 7.163 0 16c0 10.5 16 24 16 24s16-13.5 16-24C32 7.163 24.837 0 16 0z" fill="${baseColor}"/>
                ${innerSvg}
            </svg>
        `;
    }

    // Initialize Map
    document.addEventListener('DOMContentLoaded', function() {
        // Init map with default center (e.g. Bandung)
        map = L.map('map', {
            zoomControl: false // We will add it manually for positioning
        }).setView([-6.917464, 107.619123], 13);

        L.control.zoom({
            position: 'bottomright'
        }).addTo(map);

        L.tileLayer('https://{s}.basemaps.cartocdn.com/rastertiles/voyager/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; OpenStreetMap &copy; CARTO',
            subdomains: 'abcd',
            maxZoom: 20
        }).addTo(map);

        // Get User Location
        if (navigator.geolocation) {
            navigator.geolocation.watchPosition(
                position => {
                    const { latitude, longitude, accuracy } = position.coords;
                    currentLat = latitude;
                    currentLng = longitude;

                    updateUserLocationMarker(latitude, longitude, accuracy);
                    fetchNearbyBusinesses();

                    document.getElementById('status-text').innerText = 'Lokasi Ditemukan';
                    document.getElementById('status-banner').querySelector('.bg-yellow-400').classList.replace('bg-yellow-400', 'bg-[#1cb764]');
                    
                    // Hide banner after 3 seconds
                    setTimeout(() => {
                        document.getElementById('status-banner').style.opacity = '0';
                    }, 3000);
                },
                error => {
                    document.getElementById('status-text').innerText = 'Gagal mendapatkan lokasi. Pastikan GPS aktif.';
                    document.getElementById('status-text').classList.add('text-red-500');
                    document.getElementById('status-banner').querySelector('div').classList.replace('bg-yellow-400', 'bg-red-500');
                },
                { enableHighAccuracy: true, maximumAge: 10000, timeout: 5000 }
            );
        } else {
            document.getElementById('status-text').innerText = 'Browser tidak mendukung Geolocation';
        }
    });

    function updateUserLocationMarker(lat, lng, accuracy) {
        // User blue dot icon
        const userIcon = L.divIcon({
            html: `<div class="w-5 h-5 bg-blue-500 rounded-full border-4 border-white shadow-md user-marker"></div>`,
            className: '',
            iconSize: [20, 20],
            iconAnchor: [10, 10]
        });

        if (userMarker) {
            userMarker.setLatLng([lat, lng]);
            userCircle.setLatLng([lat, lng]);
            userCircle.setRadius(accuracy > 100 ? 100 : accuracy); // Cap visual radius
        } else {
            userCircle = L.circle([lat, lng], {
                color: '#3b82f6',
                fillColor: '#3b82f6',
                fillOpacity: 0.1,
                weight: 1,
                radius: accuracy > 100 ? 100 : accuracy
            }).addTo(map);

            userMarker = L.marker([lat, lng], { icon: userIcon }).addTo(map);
            userMarker.bindTooltip("Lokasi Saya", {
                permanent: true,
                direction: 'bottom',
                className: 'bg-white text-blue-600 font-bold text-xs px-2 py-1 rounded-full shadow-sm border-0 mt-2',
                offset: [0, 10]
            });
            
            // Initial center
            map.setView([lat, lng], 15);
        }
    }

    function centerMapToUser() {
        if (currentLat && currentLng) {
            map.setView([currentLat, currentLng], 15, { animate: true });
        }
    }

    function updateMapFilter(filterValue) {
        currentFilter = filterValue;
        fetchNearbyBusinesses();
    }

    function fetchNearbyBusinesses() {
        if (!currentLat || !currentLng) return;

        currentSearch = document.getElementById('searchInput').value.trim();

        let url = `/user/api/lokasi/terdekat?lat=${currentLat}&lng=${currentLng}`;
        if (currentFilter) {
            url += `&kategori=${encodeURIComponent(currentFilter)}`;
        }
        if (currentSearch) {
            url += `&search=${encodeURIComponent(currentSearch)}`;
        }

        fetch(url)
            .then(res => res.json())
            .then(data => {
                businessMarkers.forEach(m => map.removeLayer(m));
                businessMarkers = [];
                
                const errorDiv = document.getElementById('search-error');
                
                if (data.length === 0) {
                    errorDiv.classList.remove('hidden');
                    setTimeout(() => {
                        errorDiv.classList.add('hidden');
                    }, 3000);
                    return;
                } else {
                    errorDiv.classList.add('hidden');
                }

                data.forEach(unit => {
                    const icon = L.divIcon({
                        className: 'custom-marker',
                        html: getCategoryIcon(unit.jenis_usaha),
                        iconSize: [36, 45],
                        iconAnchor: [18, 45],
                        popupAnchor: [0, -45]
                    });

                    const distanceStr = parseFloat(unit.distance).toFixed(1) + ' km';
                    const marker = L.marker([unit.lokasi_lat, unit.lokasi_lng], { icon: icon }).addTo(map);
                    const imageUrl = unit.foto_bisnis ? `/storage/${unit.foto_bisnis}` : 'https://ui-avatars.com/api/?name=' + encodeURIComponent(unit.nama_usaha) + '&background=eefcf4&color=1cb764&size=200';

                    const popupContent = `
                        <div class="w-64 bg-white rounded-2xl overflow-hidden pointer-events-auto">
                            <div class="h-24 w-full bg-gray-100 overflow-hidden relative">
                                <img src="${imageUrl}" class="w-full h-full object-cover" onerror="this.src='https://ui-avatars.com/api/?name=' + encodeURIComponent('${unit.nama_usaha}') + '&background=eefcf4&color=1cb764&size=200'">
                                <div class="absolute top-2 right-2 bg-white/90 backdrop-blur-sm px-2 py-1 rounded-full text-[10px] font-bold text-gray-700 flex items-center gap-1 shadow-sm">
                                    <svg class="w-3 h-3 text-[#1cb764]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                    ${distanceStr}
                                </div>
                            </div>
                            <div class="p-4">
                                <h4 class="font-extrabold text-gray-800 text-base leading-tight mb-1 truncate">${unit.nama_usaha}</h4>
                                <p class="text-xs text-gray-500 font-medium mb-3">${unit.jenis_usaha || 'Unit Bisnis'}</p>
                                <a href="/user/unit-bisnis/${unit.id}" class="block w-full py-2 bg-[#eefcf4] hover:bg-[#1cb764] text-[#1cb764] hover:text-white text-center font-bold text-xs rounded-xl transition-colors">
                                    Lihat Profil
                                </a>
                            </div>
                        </div>
                    `;

                    marker.bindPopup(popupContent, { closeButton: false, offset: [0, -35] });

                    let hoverTimeout;
                    marker.on('mouseover', function (e) {
                        hoverTimeout = setTimeout(() => {
                            this.openPopup();
                        }, 1000);
                    });

                    marker.on('mouseout', function (e) {
                        clearTimeout(hoverTimeout);
                    });

                    businessMarkers.push(marker);
                });
                
                // Auto-pan/zoom map to fit results
                if (businessMarkers.length > 0) {
                    const group = new L.featureGroup(businessMarkers);
                    map.flyToBounds(group.getBounds(), { padding: [50, 50], maxZoom: 16, duration: 1.5 });
                    
                    // If exactly 1 result, show popup automatically after panning
                    if (businessMarkers.length === 1) {
                        setTimeout(() => {
                            businessMarkers[0].openPopup();
                        }, 1500); // Wait for flyTo animation to mostly finish
                    }
                }
            })
            .catch(err => console.error("Error fetching nearby locations:", err));
    }
</script>
@endsection
