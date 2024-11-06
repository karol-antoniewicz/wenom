import { defineStore } from 'pinia';

export const useStore = defineStore('main', {
    state: () => ({
        sidebarCollapsed: true,
        darkmode: false,
        progress: 0,
    }),
    actions: {
        startProgress() {
            this.progress = Math.floor(Math.random() * (90 - 50 + 1)) + 50;
        },
        finishProgress() {
            this.progress = 100;
            setTimeout(() => this.progress = 0, 500);
        },
    },
});
