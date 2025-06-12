import Echo from 'laravel-echo';

import Pusher from 'pusher-js';

console.log(import.meta.env.VITE_PUSHER_APP_KEY);
console.log(import.meta.env.VITE_PUSHER_APP_CLUSTER);

window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: 'pusher',
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true
});
