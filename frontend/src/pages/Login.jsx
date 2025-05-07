import { useState } from 'react';
import api from '../utils/api';
import {toast} from 'react-toastify';
import image1 from '../assets/login1.jpg'; // Make sure this image exists in the correct path
import { useNavigate } from 'react-router-dom';

const Login = () => {
  const [email, setEmail] = useState('');
  const [password, setPassword] = useState('');

  const navigate = useNavigate()

  const handleSubmit = async (e) => {
    e.preventDefault();
    try {
      const response = await api.post('/controllers/action.login.php', {
        email,
        password,
      });
  
      // Log full response for debugging
      console.log('Login response:', response);
  
      // Check if the response has data and user
      if (response.data && response.data.status === 'success' && response.data.user) {
        const { id, role, title, name } = response.data.user;
  
        // Store user info
        localStorage.setItem('user', JSON.stringify({ id, role, title, name }));
        navigate('/');
      } else {
        toast.error(response.data.message || 'Login failed. Please try again.');
      }
  
    } catch (error) {
      console.error('Login error:', error);
      toast.error(error?.response?.data?.message || 'An unexpected error occurred.');
    }
  };
  
  return (
    <div className="flex flex-col md:flex-row bg-white rounded-lg overflow-hidden shadow-xl w-full max-w-[300px] md:max-w-[700px]">
      
      {/* Left image - hidden on small screens */}
      <div
        className="hidden md:block md:w-1/2 bg-cover bg-center"
        style={{ backgroundImage: `url(${image1})` }}
      />

      {/* Right form */}
      <div className="w-full md:w-1/2 p-8 md:p-10">
        <h2 className="text-xl font-bold mb-6 text-left text-blue-600">Login to your account</h2>
        <form className="space-y-5" onSubmit={handleSubmit}>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input
              type="email"
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-none focus:ring-2 focus:ring-blue-400"
              placeholder="Enter your email"
              value={email}
              onChange={(e) => setEmail(e.target.value)}
              required
            />
          </div>
          <div>
            <label className="block text-sm font-medium text-gray-700 mb-1">Password</label>
            <input
              type="password"
              className="w-full px-4 py-2 border rounded-lg focus:outline-none focus:border-none focus:ring-2 focus:ring-blue-400"
              placeholder="Enter your password"
              value={password}
              onChange={(e) => setPassword(e.target.value)}
              required
            />
          </div>
          <button
            type="submit"
            className="w-full cursor-pointer py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition duration-200"
          >
            Login
          </button>
          <p className="text-sm text-center text-gray-600 mt-4">
            Forgot your password? <span className="text-blue-600 cursor-pointer hover:underline">Click here</span>
          </p>
        </form>
      </div>
    </div>
  );
};

export default Login;
