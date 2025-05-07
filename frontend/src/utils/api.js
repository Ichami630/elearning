import axios from 'axios';

const api = axios.create({
    baseURL: 'http://localhost/elearning/backend',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
    },
    withCredentials: true, //to allow sessions from PHP

});

export default api;