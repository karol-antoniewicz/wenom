import './bootstrap';
import './../css/app.css';
import '@svws-nrw/svws-ui/style.css';
import { createApp, h, Plugin } from 'vue';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { createInertiaApp, InertiaApp, InertiaAppProps } from '@inertiajs/inertia-vue3';
import eventBus from '@/event-bus';

const appName = window.document.getElementsByTagName('title')[0]?.innerText || 'Laravel';

if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
	document.documentElement.classList.add('dark', 'theme-dark');
} else {
	document.documentElement.classList.remove('dark', 'theme-dark');
}

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) => resolvePageComponent(`./Pages/${name}.vue`, import.meta.glob('./Pages/**/*.vue')),
    setup({el, app, props, plugin}: { el: Element, app: InertiaApp, props: InertiaAppProps, plugin: Plugin }): void | any {
        return createApp({render: () => h(app, props)})
            .use(plugin)
            .provide('$eventBus', eventBus) 
            .mixin({ methods: {route}})
            .mount(el);
    },
});
