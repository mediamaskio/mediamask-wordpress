import axios from "axios";

declare global {
    interface Window {
        wpApiSettings: {
            root: string,
            nonce: string
        };
    }
}


// @ts-ignore
export const api = axios.create({
    baseURL: window.wpApiSettings.root,
    timeout: 100000,
    headers: {
        'X-WP-Nonce': window.wpApiSettings.nonce
    }
})
