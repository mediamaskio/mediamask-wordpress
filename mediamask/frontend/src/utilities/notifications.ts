import {ref} from "vue";

export const notifications = ref([]);

export function addNotification(){
    notifications.value.push({message: 'test'});
}
