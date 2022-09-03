import axios from "axios";

declare global {
    interface Window {
        wpApiSettings: {
            root: string,
            nonce: string,
            post_types_and_taxonomies: [{
                id: string,
                templates: [
                    string
                ]
            }]
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
