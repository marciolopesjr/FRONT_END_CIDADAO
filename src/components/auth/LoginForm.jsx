// src/components/auth/LoginForm.jsx
import React, { useState } from 'react';
import { useNavigate } from 'react-router-dom';

const LoginForm = ({ onLogin }) => {
  const [cpf, setCpf] = useState('');
  const [password, setPassword] = useState('');
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState('');
  const navigate = useNavigate();

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);
    setError('');

    // Simulação de chamada à API de login
    setTimeout(() => {
      setLoading(false);
      if (cpf === '12345678900' && password === 'senha123') {
        // Login bem-sucedido
        onLogin({ cpf }); // Informa ao componente pai que o login foi realizado
        navigate('/'); // Redireciona para a página principal
      } else {
        setError('Credenciais inválidas.');
      }
    }, 1500); // Simula um tempo de resposta da API

    // Em um cenário real, você faria algo como:
    // try {
    //   const response = await api.post('/login', { cpf, password });
    //   if (response.status === 200) {
    //     onLogin(response.data.user);
    //     navigate('/');
    //   } else {
    //     setError('Erro ao fazer login.');
    //   }
    // } catch (error) {
    //   setError('Erro ao conectar com o servidor.');
    // } finally {
    //   setLoading(false);
    // }
  };

  return (
    <div className="max-w-md mx-auto p-6 bg-white rounded-md shadow-md">
      <h2 className="text-2xl font-semibold mb-4">Login</h2>
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label htmlFor="cpf" className="block text-gray-700 text-sm font-bold mb-2">
            CPF
          </label>
          <input
            type="text"
            id="cpf"
            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Digite seu CPF"
            value={cpf}
            onChange={(e) => setCpf(e.target.value)}
            disabled={loading}
          />
        </div>
        <div className="mb-6">
          <label htmlFor="password" className="block text-gray-700 text-sm font-bold mb-2">
            Senha
          </label>
          <input
            type="password"
            id="password"
            className="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 mb-3 leading-tight focus:outline-none focus:shadow-outline"
            placeholder="Digite sua senha"
            value={password}
            onChange={(e) => setPassword(e.target.value)}
            disabled={loading}
          />
        </div>
        <div className="flex items-center justify-between">
          <button
            className={`bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline ${loading ? 'opacity-50 cursor-not-allowed' : ''}`}
            type="submit"
            disabled={loading}
          >
            {loading ? 'Entrando...' : 'Entrar'}
          </button>
          <a className="inline-block align-baseline font-bold text-sm text-blue-500 hover:text-blue-800" href="#">
            Esqueceu a senha?
          </a>
        </div>
        {error && <p className="text-red-500 text-sm mt-2">{error}</p>}
      </form>
    </div>
  );
};

export default LoginForm;