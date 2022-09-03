import {ref} from "vue";
import {api} from "./api";
import {addNotification} from "./notifications";
import {getTemplates} from "./templates";

export const apiKey = ref('');
export const savedApiKey = ref('');

export function getApiKey() {
    api.get('mediamask/v1/api-key')
        .then(function (response) {
            apiKey.value = response.data.api_token;
            savedApiKey.value = response.data.api_token;
        })
        .catch(function (error) {
            console.log(error);
        });
}

export function updateApiToken() {
    api.post('mediamask/v1/api-key', {
        api_token: apiKey.value,
    })
        .then(function (response) {
            addNotification();
            getTemplates();
            savedApiKey.value = response.data.api_token;
        })
        .catch(function (error) {
            console.log(error);
        });
}