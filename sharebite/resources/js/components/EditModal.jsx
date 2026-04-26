import React, { useState, useEffect } from 'react';
import { X, Upload } from 'lucide-react';

export default function EditModal({ isOpen, food, onClose, onSave }) {
  const [formData, setFormData] = useState({
    id: '',
    name: '',
    category: 'Nasi Kotak',
    weight: '',
    price: '',
    description: '',
    image: null,
    portionCount: '',
  });

  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');

  const categories = ['Nasi Kotak', 'Pasta', 'Salad', 'Western', 'Seafood', 'Vegetarian'];

  useEffect(() => {
    if (food) {
      setFormData({
        id: food.id || '',
        name: food.name || '',
        category: food.category || 'Nasi Kotak',
        weight: food.weight || '',
        price: food.price || '',
        description: food.description || '',
        image: null,
        portionCount: food.portionCount || '',
      });
      setError('');
      setSuccess('');
    }
  }, [food, isOpen]);

  const handleInputChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleImageChange = (e) => {
    const file = e.target.files?.[0];
    if (file) {
      processImageFile(file);
    }
  };

  const processImageFile = (file) => {
    // Validate file size (max 5MB)
    if (file.size > 5 * 1024 * 1024) {
      setError('Ukuran file terlalu besar (max 5MB)');
      return;
    }

    // Validate file type
    if (!file.type.startsWith('image/')) {
      setError('File harus berupa gambar');
      return;
    }

    const reader = new FileReader();
    reader.onload = (event) => {
      setFormData(prev => ({
        ...prev,
        image: event.target.result
      }));
      setError('');
    };
    reader.onerror = () => {
      setError('Gagal membaca file');
    };
    reader.readAsDataURL(file);
  };

  const handleDragOver = (e) => {
    e.preventDefault();
    e.stopPropagation();
  };

  const handleDrop = (e) => {
    e.preventDefault();
    e.stopPropagation();
    const files = e.dataTransfer.files;
    if (files.length > 0) {
      processImageFile(files[0]);
    }
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    
    // Validation
    if (!formData.name.trim()) {
      setError('Nama makanan harus diisi');
      return;
    }
    if (!formData.weight.trim()) {
      setError('Berat makanan harus diisi');
      return;
    }
    if (!formData.price) {
      setError('Harga makanan harus diisi');
      return;
    }

    setLoading(true);
    setError('');
    setSuccess('');

    try {
      // Simulate API call
      await new Promise(resolve => setTimeout(resolve, 1000));
      
      // In real app, you would make API call here
      onSave(formData);
      setSuccess('Perubahan berhasil disimpan');
      
      setTimeout(() => {
        onClose();
      }, 1500);
    } catch (err) {
      setError('Data tidak berhasil disimpan, periksa koneksi Anda');
    } finally {
      setLoading(false);
    }
  };

  if (!isOpen) return null;

  return (
    <div className="fixed inset-0 bg-black/50 flex items-center justify-center z-50 p-4">
      <div className="bg-white rounded-2xl shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">
        {/* Header */}
        <div className="sticky top-0 bg-white border-b border-gray-200 px-8 py-6 flex items-center justify-between">
          <h2 className="text-2xl font-bold text-gray-900">Edit Master Makanan</h2>
          <button
            onClick={onClose}
            className="p-2 hover:bg-gray-100 rounded-lg transition-colors"
          >
            <X className="w-6 h-6" />
          </button>
        </div>

        {/* Form Content */}
        <form onSubmit={handleSubmit} className="p-8 space-y-6">
          {/* Alert Messages */}
          {error && (
            <div className="p-4 bg-red-50 border border-red-200 rounded-lg flex items-start gap-3">
              <div className="text-red-600 font-medium flex-1">{error}</div>
              <button
                type="button"
                onClick={() => setError('')}
                className="text-red-400 hover:text-red-600"
              >
                <X className="w-4 h-4" />
              </button>
            </div>
          )}

          {success && (
            <div className="p-4 bg-green-50 border border-green-200 rounded-lg flex items-start gap-3">
              <div className="text-green-600 font-medium flex-1">✓ {success}</div>
              <button
                type="button"
                onClick={() => setSuccess('')}
                className="text-green-400 hover:text-green-600"
              >
                <X className="w-4 h-4" />
              </button>
            </div>
          )}

          {/* Grid Layout */}
          <div className="grid grid-cols-2 gap-6">
            {/* Nama Makanan */}
            <div className="col-span-2">
              <label className="block text-sm font-semibold text-gray-900 mb-2">
                Nama Makanan *
              </label>
              <input
                type="text"
                name="name"
                value={formData.name}
                onChange={handleInputChange}
                placeholder="Contoh: Paket Nasi Kotak Nusantara"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all"
              />
            </div>

            {/* Kategori */}
            <div>
              <label className="block text-sm font-semibold text-gray-900 mb-2">
                Kategori *
              </label>
              <select
                name="category"
                value={formData.category}
                onChange={handleInputChange}
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all"
              >
                {categories.map(cat => (
                  <option key={cat} value={cat}>{cat}</option>
                ))}
              </select>
            </div>

            {/* Berat */}
            <div>
              <label className="block text-sm font-semibold text-gray-900 mb-2">
                Berat (kg) *
              </label>
              <input
                type="text"
                name="weight"
                value={formData.weight}
                onChange={handleInputChange}
                placeholder="0.45"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all"
              />
            </div>

            {/* Harga */}
            <div>
              <label className="block text-sm font-semibold text-gray-900 mb-2">
                Harga (Rp) *
              </label>
              <input
                type="number"
                name="price"
                value={formData.price}
                onChange={handleInputChange}
                placeholder="12000"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all"
              />
            </div>

            {/* Jumlah Porsi */}
            <div>
              <label className="block text-sm font-semibold text-gray-900 mb-2">
                Jumlah Porsi
              </label>
              <input
                type="number"
                name="portionCount"
                value={formData.portionCount}
                onChange={handleInputChange}
                placeholder="15"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all"
              />
            </div>

            {/* Deskripsi */}
            <div className="col-span-2">
              <label className="block text-sm font-semibold text-gray-900 mb-2">
                Deskripsi
              </label>
              <textarea
                name="description"
                value={formData.description}
                onChange={handleInputChange}
                placeholder="Deskripsi lengkap makanan..."
                rows="3"
                className="w-full px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:border-transparent transition-all resize-none"
              />
            </div>

            {/* Upload Gambar */}
            <div className="col-span-2">
              <label className="block text-sm font-semibold text-gray-900 mb-3">
                Upload Gambar
              </label>
              
              {/* Image Preview */}
              {formData.image && (
                <div className="mb-4 relative">
                  <img 
                    src={formData.image} 
                    alt="Preview" 
                    className="w-full h-48 object-cover rounded-lg"
                  />
                  <button
                    type="button"
                    onClick={() => setFormData(prev => ({ ...prev, image: null }))}
                    className="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full hover:bg-red-700 transition-colors"
                  >
                    <X className="w-4 h-4" />
                  </button>
                </div>
              )}

              {/* Upload Area */}
              <div
                onDragOver={handleDragOver}
                onDrop={handleDrop}
                className="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-emerald-600 hover:bg-emerald-50 transition-all cursor-pointer"
              >
                <input
                  type="file"
                  accept="image/*"
                  onChange={handleImageChange}
                  className="hidden"
                  id="image-input"
                />
                <label htmlFor="image-input" className="cursor-pointer block">
                  <Upload className="w-10 h-10 text-gray-400 mx-auto mb-2" />
                  <p className="text-gray-600 font-medium">
                    {formData.image ? 'Klik untuk ganti gambar' : 'Klik untuk upload atau drag gambar di sini'}
                  </p>
                  <p className="text-xs text-gray-500 mt-1">PNG, JPG, GIF hingga 5MB</p>
                </label>
              </div>
            </div>
          </div>

          {/* Buttons */}
          <div className="flex gap-3 pt-6 border-t border-gray-200">
            <button
              type="button"
              onClick={onClose}
              disabled={loading}
              className="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition-colors disabled:opacity-50"
            >
              Batal
            </button>
            <button
              type="submit"
              disabled={loading}
              className="flex-1 px-6 py-3 bg-emerald-600 text-white font-semibold rounded-lg hover:bg-emerald-700 transition-colors disabled:opacity-50 disabled:cursor-not-allowed"
            >
              {loading ? 'Menyimpan...' : 'Simpan Perubahan'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}
