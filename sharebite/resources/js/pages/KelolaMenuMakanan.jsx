import React, { useState } from 'react';
import Sidebar from '../components/Sidebar';
import Header from '../components/Header';
import FoodCard from '../components/FoodCard';
import EditModal from '../components/EditModal';
import { Search, Plus, AlertCircle } from 'lucide-react';

const dummyFoods = [
  {
    id: 1,
    name: 'Paket Nasi Kotak Nusantara',
    category: 'Nasi Kotak',
    weight: '0.45 kg',
    price: 12000,
    portionCount: 15,
    description: 'Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur',
    image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=500&fit=crop',
  },
  {
    id: 2,
    name: 'Paket Nasi Kotak Nusantara',
    category: 'Nasi Kotak',
    weight: '0.45 kg',
    price: 12000,
    portionCount: 15,
    description: 'Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur',
    image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=500&fit=crop',
  },
  {
    id: 3,
    name: 'Paket Nasi Kotak Nusantara',
    category: 'Nasi Kotak',
    weight: '0.45 kg',
    price: 12000,
    portionCount: 15,
    description: 'Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur',
    image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=500&fit=crop',
  },
  {
    id: 4,
    name: 'Paket Nasi Kotak Nusantara',
    category: 'Nasi Kotak',
    weight: '0.45 kg',
    price: 12000,
    portionCount: 15,
    description: 'Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur',
    image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=500&fit=crop',
  },
  {
    id: 5,
    name: 'Pasta Carbonara Creamy',
    category: 'Pasta',
    weight: '0.50 kg',
    price: 15000,
    portionCount: 12,
    description: 'Pasta dengan saus krim, bacon, dan parmesan asli',
    image: 'https://images.unsplash.com/photo-1621996346565-e3dbc646d9a9?w=500&h=500&fit=crop',
  },
  {
    id: 6,
    name: 'Salad Sayuran Fresh',
    category: 'Salad',
    weight: '0.35 kg',
    price: 10000,
    portionCount: 20,
    description: 'Campuran sayuran segar dengan dressing madu lemon',
    image: 'https://images.unsplash.com/photo-1540189549336-e6e99c3679fe?w=500&h=500&fit=crop',
  },
  {
    id: 7,
    name: 'Chicken Katsu Western',
    category: 'Western',
    weight: '0.55 kg',
    price: 18000,
    portionCount: 10,
    description: 'Ayam goreng crispy dengan kuah curry dan nasi putih',
    image: 'https://images.unsplash.com/photo-1626082927389-6cd097cfd330?w=500&h=500&fit=crop',
  },
  {
    id: 8,
    name: 'Seafood Rice Bowl',
    category: 'Seafood',
    weight: '0.60 kg',
    price: 22000,
    portionCount: 8,
    description: 'Udang, cumi, dan ikan dengan nasi garlic dan sayuran',
    image: 'https://images.unsplash.com/photo-1546069901-ba9599a7e63c?w=500&h=500&fit=crop',
  },
];

export default function KelolaMenuMakanan() {
  const [activeTab, setActiveTab] = useState('master');
  const [foods, setFoods] = useState(dummyFoods);
  const [selectedFood, setSelectedFood] = useState(null);
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [searchTerm, setSearchTerm] = useState('');
  const [toast, setToast] = useState(null);

  const filteredFoods = foods.filter(food =>
    food.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
    food.category.toLowerCase().includes(searchTerm.toLowerCase())
  );

  const handleEditClick = (food) => {
    setSelectedFood(food);
    setIsModalOpen(true);
  };

  const handleSave = (updatedFood) => {
    // Cek apakah ini CREATE (data baru) atau UPDATE (data lama)
    const foodExists = foods.some(f => f.id === updatedFood.id);
    
    if (foodExists) {
      // UPDATE existing food
      setFoods(foods.map(f => f.id === updatedFood.id ? updatedFood : f));
      showToast('Perubahan berhasil disimpan', 'success');
    } else {
      // CREATE new food
      setFoods([...foods, updatedFood]);
      showToast('Menu baru berhasil ditambahkan', 'success');
    }
  };

  const handleDelete = (foodId) => {
    if (window.confirm('Apakah Anda yakin ingin menghapus makanan ini?')) {
      setFoods(foods.filter(f => f.id !== foodId));
      showToast('Makanan berhasil dihapus', 'success');
    }
  };

  const handleAddNew = () => {
    const newFood = {
      id: Math.max(...foods.map(f => f.id), 0) + 1,
      name: '',
      category: 'Nasi Kotak',
      weight: '',
      price: '',
      portionCount: '',
      description: '',
      image: null,
    };
    setSelectedFood(newFood);
    setIsModalOpen(true);
  };

  const showToast = (message, type) => {
    setToast({ message, type });
    setTimeout(() => setToast(null), 4000);
  };

  return (
    <div className="flex h-screen bg-gray-50 overflow-hidden">
      {/* Sidebar */}
      <Sidebar activeMenu="makanan" />

      {/* Main Content */}
      <div className="flex-1 ml-64 flex flex-col overflow-auto">
        {/* Header */}
        <Header activeTab={activeTab} onTabChange={setActiveTab} />

        {/* Page Content */}
        <div className="flex-1 px-8 py-8">
          {activeTab === 'master' ? (
            <>
              {/* Search and Add Button */}
              <div className="mb-8 flex gap-4">
                <div className="flex-1 relative">
                  <Search className="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 w-5 h-5" />
                  <input
                    type="text"
                    placeholder="Cari master data makanan..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    className="w-full pl-12 pr-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all"
                  />
                </div>
                <button
                  onClick={handleAddNew}
                  className="px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors flex items-center gap-2 whitespace-nowrap"
                >
                  <Plus className="w-5 h-5" />
                  <span>Tambah Master Menu</span>
                </button>
              </div>

              {/* Food Grid */}
              <div className="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                {filteredFoods.length > 0 ? (
                  filteredFoods.map(food => (
                    <FoodCard
                      key={food.id}
                      food={food}
                      onEdit={handleEditClick}
                      onDelete={handleDelete}
                    />
                  ))
                ) : (
                  <div className="col-span-full flex flex-col items-center justify-center py-16">
                    <AlertCircle className="w-12 h-12 text-gray-400 mb-4" />
                    <p className="text-gray-600 font-medium">Tidak ada data makanan ditemukan</p>
                    <p className="text-gray-500 text-sm mt-1">Coba ubah kata kunci pencarian Anda</p>
                  </div>
                )}
              </div>
            </>
          ) : (
            <div className="bg-white rounded-lg p-12 text-center">
              <div className="text-6xl mb-4">📋</div>
              <h3 className="text-xl font-bold text-gray-900 mb-2">Menu Aktif</h3>
              <p className="text-gray-600">Fitur Menu Aktif akan segera hadir. Kelola menu mana saja yang ditampilkan di aplikasi pelanggan.</p>
            </div>
          )}
        </div>
      </div>

      {/* Edit Modal */}
      <EditModal
        isOpen={isModalOpen}
        food={selectedFood}
        onClose={() => {
          setIsModalOpen(false);
          setSelectedFood(null);
        }}
        onSave={handleSave}
      />

      {/* Toast Notification */}
      {toast && (
        <div
          className={`fixed bottom-6 right-6 px-6 py-4 rounded-lg font-medium text-white transition-all duration-300 transform ${
            toast.type === 'success'
              ? 'bg-emerald-600 animate-slide-in'
              : 'bg-red-600 animate-slide-in'
          }`}
        >
          {toast.type === 'success' ? '✓ ' : '✕ '}
          {toast.message}
        </div>
      )}
    </div>
  );
}
