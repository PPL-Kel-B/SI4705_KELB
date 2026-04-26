import React from 'react';
import { Bell } from 'lucide-react';

export default function Header({ activeTab = 'master', onTabChange }) {
  return (
    <div className="bg-white border-b border-gray-200">
      <div className="max-w-7xl mx-auto px-8 py-6">
        {/* Title Section */}
        <div className="flex items-center justify-between mb-6">
          <div>
            <h1 className="text-3xl font-bold text-gray-900">Kelola Menu Makanan</h1>
            <p className="text-gray-500 text-sm mt-1">Manage master data dan menu aktif makanan di unit bisnis Anda</p>
          </div>
          
          {/* User Info */}
          <div className="flex items-center gap-4">
            <button className="p-2 hover:bg-gray-100 rounded-lg transition-colors">
              <Bell className="w-6 h-6 text-gray-600" />
            </button>
            <div className="flex items-center gap-3 pl-4 border-l border-gray-200">
              <div className="text-right">
                <p className="font-semibold text-gray-900 text-sm">Unit Bisnis</p>
                <p className="text-xs text-gray-500">Arcamanik Hotel</p>
              </div>
              <div className="w-10 h-10 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-600 flex items-center justify-center text-white font-bold">
                AH
              </div>
            </div>
          </div>
        </div>

        {/* Tabs */}
        <div className="flex gap-6 border-b border-gray-200">
          <button
            onClick={() => onTabChange('aktif')}
            className={`px-4 py-3 font-medium text-sm transition-all duration-200 ${
              activeTab === 'aktif'
                ? 'text-emerald-600 border-b-2 border-emerald-600'
                : 'text-gray-600 hover:text-gray-900'
            }`}
          >
            Menu Aktif
          </button>
          <button
            onClick={() => onTabChange('master')}
            className={`px-4 py-3 font-medium text-sm transition-all duration-200 ${
              activeTab === 'master'
                ? 'text-emerald-600 border-b-2 border-emerald-600'
                : 'text-gray-600 hover:text-gray-900'
            }`}
          >
            Master Data
          </button>
        </div>
      </div>
    </div>
  );
}
