
import axios from 'axios';

const checkSession = async () => {
    try {
        const response = await axios.get('/check-session');
        if (!response.data.authenticated) {
            window.location.href = '/login';
        }
    } catch (error) {
        console.error('Error checking session:', error);
    }
};

const shouldCheckSession = () => {
    const excludedRoutes = ['/login', '/register', '/admin/login', '/marketing/login', '/marketing'];
    return !excludedRoutes.includes(window.location.pathname);
};

const startSessionCheck = () => {
    if (shouldCheckSession()) {
        setInterval(checkSession, 300000);
    }
};

startSessionCheck();
