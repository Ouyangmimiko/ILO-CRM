// src/api/axios.ts
import axios, {AxiosError, isAxiosError} from 'axios';

// creat Axios instance
const instance = axios.create({
    baseURL: '/backend', // base path of api
    timeout: 10000,
    headers: {
        'Content-Type': 'application/json',
    },
});

axios.isAxiosError = isAxiosError;

export default instance;
