// src/components/Navbar.jsx
import React from 'react';
import { useNavigate, useLocation } from 'react-router-dom';

const Navbar = ({ onLogout }) => {
  const navigate = useNavigate();
  const location = useLocation();

  const handleLogout = () => {
    // Chama a função de logout passada como prop
    onLogout();
    // Redireciona para a página de login
    navigate('/login');
  };

  // Função para determinar o título da página com base na rota
  const getPageTitle = () => {
    switch (location.pathname) {
      case '/':
        return 'Demandas';
      case '/create-demand':
        return 'Criar Demanda';
      case '/login':
        return 'Login';
      case '/register':
        return 'Registro';
      default:
        return 'Página Desconhecida';
    }
  };

  return (
    <nav className="bg-blue-500 p-4 text-white flex justify-between items-center">
      <span className="font-semibold text-xl">{getPageTitle()}</span>
      <button
        className="border-white border-2 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline"
        onClick={handleLogout}
      >
        Logout
      </button>
    </nav>
  );
};

export default Navbar;