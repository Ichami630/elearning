// utils/checkSession.js
import api from './api';

const checkSession = async () => {
  try {
    const res = await api.get('/config/check-session.php');
    return res.data.status === 'active'; // true if session is valid
  } catch (error) {
    console.error(error)
    return false;
  }
};

export default checkSession;
