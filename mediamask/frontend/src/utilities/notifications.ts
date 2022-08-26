import {ref} from "vue";

interface Notification {
    message: string
}

export const notifications = ref<Array<Notification>>([]);

export function addNotification(){
    notifications.value.push({message: 'test'});
}
