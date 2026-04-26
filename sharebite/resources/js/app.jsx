import React from 'react';
import ReactDOM from 'react-dom/client';
import KelolaMenuMakanan from './pages/KelolaMenuMakanan';
import '../css/app.css';

const root = document.getElementById('app');

if (root) {
  ReactDOM.createRoot(root).render(
    <React.StrictMode>
      <KelolaMenuMakanan />
    </React.StrictMode>
  );
}
