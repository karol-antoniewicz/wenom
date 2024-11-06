<template>
    <div class="bg-white text-black">
        <span>Powered by SVWS-NRW</span>
        <span>WeNoM v0.9</span>
        <a :href="route('impressum')" title="Impressum" target="_blank">Impressum</a>
        <a :href="route('datenschutz')" title="Datenschutz" target="_blank">Datenschutz</a>
        <a :href="route('barrierefreiheit')" title="Barrierefreiheit" target="_blank">Barrierefreiheit</a>
        <a href="https://schulverwaltungsinfos.nrw.de/svws/wiki/index.php?title=WeNoM:Handbuch" target="_blank" title="Hilfe">Hilfe</a>
        <button @click="darkMode">
            <span class="icon">
                <i-ri-sun-line aria-hidden="true" v-if="store.darkmode"></i-ri-sun-line>
                <i-ri-moon-line aria-hidden="true" v-else></i-ri-moon-line>
            </span>
        </button>
    </div>
</template>


<script setup lang="ts">
    // TODO: TBR
    import { useStore } from "../store";

    const store = useStore();

    const darkMode = (): void => {
        if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.remove('dark', 'theme-dark');
            localStorage.removeItem('theme');
            store.darkmode = false;
            return;
        }

        document.documentElement.classList.add('dark', 'theme-dark');
        localStorage.theme = 'dark';
        store.darkmode = true;
    }
</script>


<style scoped>

    div {
        @apply
        flex gap-3 justify-end
        py-3 px-6
        border-t
    }

    a {
        @apply underline
    }

    a, button {
        @apply
        focus-visible:outline outline-offset-2 outline-2 outline-black dark:outline-white
        rounded-sm
    }
    
</style>
