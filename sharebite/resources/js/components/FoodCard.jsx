import React from 'react';
import { Edit2, Trash2 } from 'lucide-react';

export default function FoodCard({ food, onEdit, onDelete }) {
  return (
    <div className="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 overflow-hidden group">
      {/* Image */}
      <div className="relative h-48 bg-gradient-to-br from-gray-100 to-gray-200 overflow-hidden">
        {food.image ? (
          <img 
            src={food.image} 
            alt={food.name} 
            className="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
          />
        ) : (
          <div className="w-full h-full flex items-center justify-center">
            <div className="text-center">
              <div className="text-4xl mb-2">🍱</div>
              <p className="text-gray-500 text-sm">Tidak ada gambar</p>
            </div>
          </div>
        )}
        
        {/* Category Badge */}
        <div className="absolute top-3 right-3">
          <span className="inline-block px-3 py-1 bg-emerald-600 text-white text-xs font-semibold rounded-full">
            {food.category}
          </span>
        </div>
      </div>

      {/* Content */}
      <div className="p-4">
        <h3 className="font-bold text-gray-900 text-lg mb-2 line-clamp-2">{food.name}</h3>
        
        <div className="flex items-center justify-between mb-4 pb-4 border-b border-gray-100">
          <div className="flex items-baseline gap-2">
            <span className="text-2xl font-bold text-emerald-600">Rp{food.price?.toLocaleString('id-ID') || '12.000'}</span>
            <span className="text-sm text-gray-500">{food.weight || '0.45 kg'}</span>
          </div>
          <span className="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
            {food.portionCount || '15'} Porsi
          </span>
        </div>

        {/* Description */}
        <p className="text-sm text-gray-600 mb-4 line-clamp-2">
          {food.description || 'Ayam bakar madu, sambal terasi, tahu tempe, dan urap sayur'}
        </p>

        {/* Actions */}
        <div className="flex gap-2">
          <button
            onClick={() => onEdit(food)}
            className="flex-1 flex items-center justify-center gap-2 px-3 py-2 bg-emerald-600 text-white rounded-lg font-medium hover:bg-emerald-700 transition-colors duration-200"
          >
            <Edit2 className="w-4 h-4" />
            <span>Edit Master</span>
          </button>
          <button
            onClick={() => onDelete(food.id)}
            className="px-3 py-2 border border-red-200 text-red-600 rounded-lg hover:bg-red-50 transition-colors duration-200"
            title="Hapus"
          >
            <Trash2 className="w-4 h-4" />
          </button>
        </div>
      </div>
    </div>
  );
}
