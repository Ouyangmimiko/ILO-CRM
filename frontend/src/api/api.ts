import axios from "./axios";
import {AxiosError} from "axios";

// Define the datatype of request and response

interface LoginCredentials {
    email: string;
    password: string;
}

interface User {
    id: number;
    email: string;
    name: string;
}
interface LoginResponse {
    status: boolean;
    message: string;
    user?: User;
    token?: string;
}

// API functions

// Login
export const login = async (credentials: LoginCredentials): Promise<LoginResponse> => {
    try {
        const response = await axios.post<LoginResponse>('/login', credentials);
        return  response.data;
    } catch (error) {
        if (error instanceof AxiosError) {
            const axiosError = error as AxiosError;
            const errorResponse = axiosError.response?.data as LoginResponse;
            if (errorResponse) {
                throw new Error(errorResponse.message);
            } else {
                throw new Error('An error occurred during the request.');
            }
        } else {
            throw new Error("An unexpected error occurred.");
        }
    }
};