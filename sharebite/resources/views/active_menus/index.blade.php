<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Menu Makanan</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-[#F5F7F6]">

<div class="flex min-h-screen">
    <!-- SIDEBAR -->
    <aside class="w-64 bg-white border-r px-6 py-6 flex flex-col justify-between fixed h-screen">
        <div>
            <h1 class="text-xl font-semibold text-green-600 mb-8">🍜 ShareBite</h1>
            <nav class="space-y-2 text-sm">
                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">
                    📊 Dashboard
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg bg-green-100 text-green-700 font-medium">
                    🍽 Kelola Makanan
                </a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">🛒 Pesanan</a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">📝 Riwayat</a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">👤 Profil</a>
                <a href="#" class="flex items-center gap-3 px-4 py-2 rounded-lg text-gray-500 hover:bg-gray-100">⚙️ Pengaturan</a>
            </nav>
        </div>
        <button class="text-red-500 text-sm font-medium">🚪 Logout</button>
    </aside>

    <!-- MAIN -->
    <main class="flex-1 ml-64 px-8 py-6">
        <!-- HEADER -->
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-semibold text-green-700">Kelola Menu Makanan</h2>
            <div class="flex items-center gap-3">
                <span class="text-sm text-gray-600">Arcamanik Hotel</span>
                <div class="w-9 h-9 rounded-full bg-gray-400"></div>
            </div>
        </div>

        <!-- TABS -->
        <div class="flex gap-6 border-b mb-6 text-sm">
            <button class="pb-3 border-b-2 border-green-600 text-green-600 font-medium">Menu Aktif</button>
            <button class="pb-3 text-gray-400 hover:text-gray-600">Master Data</button>
        </div>

        <!-- SEARCH + BUTTON -->
        <div class="flex justify-between items-center mb-6">
            <input type="text" id="searchInput" placeholder="Cari pesanan atau menu..."
                class="w-[320px] bg-[#EEF2F1] px-4 py-2 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
            <button onclick="openAddModal()" class="bg-green-600 hover:bg-green-700 text-white text-sm px-5 py-2 rounded-full flex items-center gap-2 font-medium">
                <span class="text-lg">+</span> Buka Menu
            </button>
        </div>

        <!-- STATS -->
        <div class="grid grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-green-100 flex items-center justify-center text-lg">✔</div>
                <div>
                    <p class="text-xs text-gray-500">Total Menu Aktif</p>
                    <p class="font-semibold text-lg"><span id="totalActive">0</span> <span class="text-sm text-gray-500">Menu</span></p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-red-100 flex items-center justify-center text-lg">✘</div>
                <div>
                    <p class="text-xs text-gray-500">Total Menu Habis</p>
                    <p class="font-semibold text-lg"><span id="totalEmpty">0</span> <span class="text-sm text-gray-500">Menu</span></p>
                </div>
            </div>

            <div class="bg-white rounded-xl p-4 flex items-center gap-3 shadow-sm">
                <div class="w-10 h-10 rounded-lg bg-yellow-100 flex items-center justify-center text-lg">💳</div>
                <div>
                    <p class="text-xs text-gray-500">Porsi Terjual Hari Ini</p>
                    <p class="font-semibold text-lg"><span id="soldToday">0</span> <span class="text-sm text-gray-500">Porsi</span></p>
                </div>
            </div>
        </div>

        <!-- FILTER -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex gap-2 text-sm">
                <button onclick="filterMenus('semua')" class="filter-btn px-4 py-2 rounded-full bg-green-600 text-white font-medium" data-status="semua">Semua</button>
                <button onclick="filterMenus('tersedia')" class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300" data-status="tersedia">Tersedia</button>
                <button onclick="filterMenus('segera_habis')" class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300" data-status="segera_habis">Segera Habis</button>
                <button onclick="filterMenus('habis')" class="filter-btn px-4 py-2 rounded-full bg-gray-200 text-gray-600 hover:bg-gray-300" data-status="habis">Habis</button>
            </div>
            <div class="flex items-center gap-2 text-sm text-gray-500">
                <span>Urutkan: Terbaru</span>
            </div>
        </div>

        <!-- GRID MENUS -->
        <div class="grid grid-cols-4 gap-5" id="menuGrid">
            <!-- Menu cards will be populated here by JavaScript -->
        </div>

        <!-- EMPTY STATE -->
        <div id="emptyState" class="text-center py-12">
            <p class="text-gray-500 text-lg">Belum ada menu aktif. Klik tombol "Buka Menu" untuk menambah menu.</p>
        </div>
    </main>
</div>

<!-- ADD MENU MODAL -->
<div id="addMenuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Tambah Menu Aktif</h3>

        <form id="addMenuForm" onsubmit="handleAddMenu(event)">
            <!-- Menu Selection -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Menu</label>
                <select id="menuSelect" required class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                    <option value="">-- Pilih Menu --</option>
                </select>
                <span id="menuSelectError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Stock -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
                <input type="number" id="stockInput" name="stock" min="0" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <span id="stockError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Limit Per User -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Batas Pengambilan Per User</label>
                <input type="number" id="limitInput" name="limit_per_user" min="1" value="1" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <span id="limitError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Is Free -->
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="isFreeCheckbox" name="is_free" class="w-4 h-4">
                    <span class="text-sm font-medium text-gray-700">Menu Gratis</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeAddModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">
                    Publikasikan Menu Aktif
                </button>
            </div>
        </form>
    </div>
</div>

<!-- EDIT MENU MODAL -->
<div id="editMenuModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-md mx-4 p-6">
        <h3 class="text-xl font-semibold text-gray-800 mb-4">Edit Menu</h3>

        <form id="editMenuForm" onsubmit="handleEditMenu(event)">
            <input type="hidden" id="editMenuId">

            <!-- Menu Name (readonly) -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Menu</label>
                <input type="text" id="editMenuName" readonly
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 bg-gray-100 text-gray-600">
            </div>

            <!-- Stock -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Stok</label>
                <input type="number" id="editStockInput" min="0" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <span id="editStockError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Limit Per User -->
            <div class="mb-4">
                <label class="block text-sm font-medium text-gray-700 mb-2">Batas Pengambilan Per User</label>
                <input type="number" id="editLimitInput" min="1" required
                    class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
                <span id="editLimitError" class="text-red-500 text-xs mt-1 hidden"></span>
            </div>

            <!-- Is Free -->
            <div class="mb-6">
                <label class="flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" id="editIsFreeCheckbox" class="w-4 h-4">
                    <span class="text-sm font-medium text-gray-700">Menu Gratis</span>
                </label>
            </div>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeEditModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                    Batal
                </button>
                <button type="submit" class="flex-1 px-4 py-2 bg-green-600 text-white rounded-lg font-medium hover:bg-green-700">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
</div>

<!-- DELETE CONFIRMATION MODAL -->
<div id="deleteConfirmModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
    <div class="bg-white rounded-2xl w-full max-w-sm mx-4 p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Hapus Menu?</h3>
        <p class="text-gray-600 text-sm mb-6">Apakah Anda yakin ingin menghapus menu <span id="deleteMenuName" class="font-semibold"></span>? Tindakan ini tidak dapat dibatalkan.</p>

        <div class="flex gap-3">
            <button onclick="closeDeleteModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 font-medium hover:bg-gray-50">
                Batal
            </button>
            <button onclick="confirmDelete()" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700">
                Hapus
            </button>
        </div>
    </div>
</div>

<!-- TOAST NOTIFICATION -->
<div id="toast" class="hidden fixed bottom-4 right-4 bg-white rounded-lg shadow-lg px-4 py-3 text-sm flex items-center gap-2 z-50">
    <span id="toastMessage"></span>
</div>

<script>
    let currentFilter = 'semua';
    let allMenus = [];
    let deleteTargetId = null;

    // Initialize
    document.addEventListener('DOMContentLoaded', function() {
        loadMenus();
        loadAvailableMenus();
        setupEventListeners();
    });

    // Load all active menus
    async function loadMenus() {
        try {
            const response = await fetch('/active-menus/', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                allMenus = data.data;
                renderMenus(allMenus);
                updateStats();
            }
        } catch (error) {
            showToast('Gagal memuat data menu', 'error');
            console.error(error);
        }
    }

    // Load available menus for dropdown
    async function loadAvailableMenus() {
        try {
            const response = await fetch('/active-menus/api/available', {
                headers: {
                    'Accept': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });
            const data = await response.json();
            
            if (data.success) {
                const select = document.getElementById('menuSelect');
                select.innerHTML = '<option value="">-- Pilih Menu --</option>';
                
                data.data.forEach(menu => {
                    const option = document.createElement('option');
                    option.value = menu.MakananID;
                    option.textContent = `${menu.Nama_Makanan} (Rp ${parseInt(menu.Harga).toLocaleString('id-ID')})`;
                    select.appendChild(option);
                });
            }
        } catch (error) {
            console.error(error);
        }
    }

    // Render menu cards
    function renderMenus(menus) {
        const grid = document.getElementById('menuGrid');
        const emptyState = document.getElementById('emptyState');

        if (menus.length === 0) {
            grid.innerHTML = '';
            emptyState.classList.remove('hidden');
            return;
        }

        emptyState.classList.add('hidden');
        grid.innerHTML = menus.map(menu => {
            const menuData = menu.menu;
            const statusClass = menu.status === 'tersedia' ? 'bg-green-700' : menu.status === 'segera_habis' ? 'bg-yellow-600' : 'bg-red-600';
            const statusText = menu.status === 'tersedia' ? 'TERSEDIA' : menu.status === 'segera_habis' ? 'SEGERA HABIS' : 'HABIS';

            return `
                <div class="bg-white rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition">
                    <div class="relative">
                        <img src="https://images.unsplash.com/photo-1604908176997-4317c4fcb1c4?w=400&h=300&fit=crop"
                             class="w-full h-40 object-cover">
                        <span class="absolute top-3 left-3 ${statusClass} text-white text-[10px] px-3 py-1 rounded-full font-semibold">
                            ${statusText}
                        </span>
                    </div>
                    <div class="p-4">
                        <h3 class="font-semibold text-sm mb-1">${menuData.Nama_Makanan}</h3>
                        <p class="text-xs text-gray-500 mb-2">Stok: ${menu.stock} porsi</p>
                        <div class="text-orange-500 font-semibold mb-3 text-sm">
                            ${menu.is_free ? 'GRATIS' : `Rp ${parseInt(menuData.Harga).toLocaleString('id-ID')}`}
                        </div>
                        <div class="flex gap-2 text-xs">
                            <button onclick="openEditModal(${menu.id})" class="flex-1 bg-gray-100 py-2 rounded-full hover:bg-gray-200 font-medium">
                                ✎ Edit
                            </button>
                            <button onclick="openDeleteModal(${menu.id}, '${menuData.Nama_Makanan}')" class="flex-1 bg-red-100 text-red-500 py-2 rounded-full hover:bg-red-200 font-medium">
                                🗑 Tutup
                            </button>
                        </div>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Update statistics
    function updateStats() {
        const total = allMenus.length;
        const empty = allMenus.filter(m => m.status === 'habis').length;
        
        document.getElementById('totalActive').textContent = total;
        document.getElementById('totalEmpty').textContent = empty;
    }

    // Filter menus by status
    function filterMenus(status) {
        currentFilter = status;

        // Update button styles
        document.querySelectorAll('.filter-btn').forEach(btn => {
            if (btn.dataset.status === status) {
                btn.classList.remove('bg-gray-200', 'text-gray-600');
                btn.classList.add('bg-green-600', 'text-white');
            } else {
                btn.classList.remove('bg-green-600', 'text-white');
                btn.classList.add('bg-gray-200', 'text-gray-600');
            }
        });

        // Filter and render
        const filtered = status === 'semua' ? allMenus : allMenus.filter(m => m.status === status);
        renderMenus(filtered);
    }

    // Modal handlers
    function openAddModal() {
        document.getElementById('addMenuModal').classList.remove('hidden');
        clearAddForm();
    }

    function closeAddModal() {
        document.getElementById('addMenuModal').classList.add('hidden');
    }

    function openEditModal(id) {
        const menu = allMenus.find(m => m.id === id);
        if (!menu) return;

        document.getElementById('editMenuId').value = id;
        document.getElementById('editMenuName').value = menu.menu.Nama_Makanan;
        document.getElementById('editStockInput').value = menu.stock;
        document.getElementById('editLimitInput').value = menu.limit_per_user;
        document.getElementById('editIsFreeCheckbox').checked = menu.is_free;
        document.getElementById('editMenuModal').classList.remove('hidden');
    }

    function closeEditModal() {
        document.getElementById('editMenuModal').classList.add('hidden');
    }

    function openDeleteModal(id, name) {
        deleteTargetId = id;
        document.getElementById('deleteMenuName').textContent = name;
        document.getElementById('deleteConfirmModal').classList.remove('hidden');
    }

    function closeDeleteModal() {
        document.getElementById('deleteConfirmModal').classList.add('hidden');
        deleteTargetId = null;
    }

    // Form submissions
    async function handleAddMenu(event) {
        event.preventDefault();
        clearErrors();

        const formData = new FormData();
        formData.append('menu_id', document.getElementById('menuSelect').value);
        formData.append('stock', document.getElementById('stockInput').value);
        formData.append('limit_per_user', document.getElementById('limitInput').value);
        if (document.getElementById('isFreeCheckbox').checked) {
            formData.append('is_free', '1');
        }

        try {
            const response = await fetch('/active-menus/', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                closeAddModal();
                loadMenus();
                loadAvailableMenus();
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan', 'error');
            console.error(error);
        }
    }

    async function handleEditMenu(event) {
        event.preventDefault();
        clearErrors();

        const id = document.getElementById('editMenuId').value;
        const formData = new FormData();
        formData.append('stock', document.getElementById('editStockInput').value);
        formData.append('limit_per_user', document.getElementById('editLimitInput').value);
        if (document.getElementById('editIsFreeCheckbox').checked) {
            formData.append('is_free', '1');
        }
        formData.append('_method', 'PUT');

        try {
            const response = await fetch(`/active-menus/${id}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: formData
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                closeEditModal();
                loadMenus();
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan', 'error');
            console.error(error);
        }
    }

    async function confirmDelete() {
        if (!deleteTargetId) return;

        try {
            const response = await fetch(`/active-menus/${deleteTargetId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            });

            const data = await response.json();

            if (data.success) {
                showToast(data.message, 'success');
                closeDeleteModal();
                loadMenus();
                loadAvailableMenus();
            } else {
                showToast(data.message, 'error');
            }
        } catch (error) {
            showToast('Terjadi kesalahan saat menghapus', 'error');
            console.error(error);
        }
    }

    // Utility functions
    function clearAddForm() {
        document.getElementById('addMenuForm').reset();
        clearErrors();
    }

    function clearErrors() {
        document.querySelectorAll('[id$="Error"]').forEach(el => {
            el.classList.add('hidden');
            el.textContent = '';
        });
    }

    function showToast(message, type = 'info') {
        const toast = document.getElementById('toast');
        const toastMessage = document.getElementById('toastMessage');
        
        toastMessage.textContent = message;
        toast.classList.remove('hidden', 'bg-red-500', 'bg-green-500', 'bg-blue-500');
        
        if (type === 'success') {
            toast.classList.add('bg-green-500', 'text-white');
        } else if (type === 'error') {
            toast.classList.add('bg-red-500', 'text-white');
        } else {
            toast.classList.add('bg-blue-500', 'text-white');
        }

        setTimeout(() => {
            toast.classList.add('hidden');
        }, 3000);
    }

    function setupEventListeners() {
        // Search functionality
        document.getElementById('searchInput').addEventListener('input', function(e) {
            const search = e.target.value.toLowerCase();
            const filtered = allMenus.filter(m => 
                m.menu.Nama_Makanan.toLowerCase().includes(search)
            );
            renderMenus(filtered);
        });
    }
</script>

</body>
</html>
