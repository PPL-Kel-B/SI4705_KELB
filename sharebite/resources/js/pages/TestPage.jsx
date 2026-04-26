import React from 'react';

export default function TestPage() {
  return (
    <div className="w-full h-screen bg-emerald-50 flex items-center justify-center">
      <div className="text-center">
        <div className="text-6xl mb-4">✅</div>
        <h1 className="text-4xl font-bold text-emerald-600 mb-2">Aplikasi Berhasil Dimuat!</h1>
        <p className="text-gray-600 text-lg">Kelola Menu Makanan Dashboard siap digunakan</p>
        <div className="mt-8 bg-white p-6 rounded-lg shadow-md max-w-md">
          <p className="text-sm text-gray-600 mb-4">Dev Server: http://localhost:5173</p>
          <p className="text-xs text-gray-500">Vite + React + Tailwind CSS</p>
        </div>
      </div>
    </div>
  );
}
