import { defineStore } from 'pinia';

export const floskelStore = defineStore('main', {
    state: () => ({
        sidebarCollapsed: true,
        darkmode: false,
        progress: 0,
    }),
    actions: {
        
    },
});
