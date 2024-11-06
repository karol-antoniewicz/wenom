<template>
    <SvwsUiAppLayout :fullwidthContent="true" v-cloak>
        <template #main>
            <div class="component">
                <slot name="main" />

                <footer>
                    <div class="disclaimer">
                        <h3>Powered by SVWS-NRW, Version {{ usePage().props.value.version }}</h3>
                        <h3>NPM-Version {{ usePage().props.value.npm }}</h3>
                        <p>Um eine gute Lesbarkeit zu erzeugen, wird bei SVWS-NRW möglichst auf geschlechtsneutrale Begriffe wie Lehrkräfte, Klassenleitung, Erzieher usw. zurückgegriffen. An Stellen, wo das nicht möglich ist, wird versucht alle Geschlechter gleichermaßen zu verwenden.</p>
                        <nav>
                            <a :href="route('impressum')">Impressum</a>
                            <a :href="route('datenschutz')">Datenschutz</a>
                            <a :href="route('barrierefreiheit')">Barrierefreiheit</a>
                            <a href="https://schulverwaltungsinfos.nrw.de/svws/wiki/index.php?title=WeNoM:Handbuch" target="_blank">
                                Hilfe
                                <span class="sr-only">(öffnet in einem neuen Tab)</span>
                            </a>
                        </nav>
                    </div>
                    <img src="../Images/MSB_NRW_Logo.svg" alt="">
                </footer>
            </div>
        </template>
    </SvwsUiAppLayout>
</template>


<script setup lang="ts">
    import { SvwsUiAppLayout } from '@svws-nrw/svws-ui';
    import { usePage } from '@inertiajs/inertia-vue3';
</script>


<style scoped>

    /* .app--layout--wenom :global(.app--content-container) {
	    @apply rounded-2xl;
    } */
    div.component {
        @apply bg-chalk bg-cover w-full h-full flex flex-col justify-between
    }


    footer {
        @apply bg-white p-8 flex flex-col-reverse md:flex-row gap-8 text-lg items-center
    }

    footer .disclaimer {
        @apply flex flex-col gap-2 grow text-center md:text-left text-sm
    }

    footer .disclaimer h3 {
        @apply text-black/75
    }

    footer .disclaimer p {
        @apply text-black/30 max-w-5xl
    }

    footer .disclaimer nav {
        @apply flex flex-row items-center gap-3 justify-center md:justify-start
    }

    footer .disclaimer nav a {
        @apply
        text-black/30 hover:text-black/75
        focus-visible:outline focus-visible:text-black/75 outline-offset-2 outline-2 outline-black
        underline
        rounded-sm
    }

    footer img {
        @apply h-20 w-auto
    }
    
</style>