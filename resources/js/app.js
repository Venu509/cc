import "./bootstrap.js";
import "../css/app.css";
import 'flowbite';
import './session-check';

import {createApp, h, onMounted} from "vue";
import { createInertiaApp } from "@inertiajs/inertia-vue3";
import { InertiaProgress } from "@inertiajs/progress";
import axios from 'axios';

import { router } from '@inertiajs/vue3';

const appName =
    window.document.getElementsByTagName("title")[0]?.innerText ||
    "DREAMCAREER";

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => require(`./Pages/${name}.vue`),
    setup({ el, app, props, plugin }) {
        return createApp({ render: () => h(app, props) })
            .use(plugin)
            .mixin({ methods: { route } })
            .mount(el);
    },
});

InertiaProgress.init({ color: "#4B5563" });

axios.interceptors.response.use(
    response => response,
    error => {
        if (error.response) {
            if (error.response.status === 401) {
                window.location.href = '/unauthorized';
            }
        } else {
            console.error("Network error or no response received");
        }
        return Promise.reject(error);
    }
);

document.addEventListener('DOMContentLoaded', () => {
    window.dispatchEvent(new Event('start'));
    window.dispatchEvent(new Event('finish'));
});

router.on('start', () => {
    window.dispatchEvent(new Event('start'));
});

router.on('finish', () => {
    window.dispatchEvent(new Event('finish'));
});