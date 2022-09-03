import {api} from "./api";
import {ref} from "vue";
import {Template} from "../../types";

export const templates = ref<Template[] | null>(null);

export function getTemplates() {
    api.get('mediamask/v1/templates')
        .then(function (response) {
            templates.value = response.data.data
        })
        .catch(function (error) {
            console.log(error);
        });
}