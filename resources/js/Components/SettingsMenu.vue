<template>
    <SvwsUiSecondaryMenu>
        <template #headline>
            Globale Einstellungen
        </template>
        <template #content>
            <div class="container">
                <SvwsUiMenuItem @click="navigate('settings.matrix')" :active="route().current('settings.matrix')">
                    <template #label>Schreibrechte</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.filter')" :active="route().current('settings.filter')">
                    <template #label>Spalteneinstellungen</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.school')" :active="route().current('settings.school')">
                    <template #label>Schuldaten</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.sicherheit')" :active="route().current('settings.sicherheit')">
                    <template #label>Mailkonfiguration</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.synchronisation')" :active="route().current('settings.synchronisation')">
                    <template #label>Synchronisation</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('settings.2fa')" :active="route().current('settings.2fa')">
                    <template #label>Sicherheitseinstellungen</template>
                </SvwsUiMenuItem>

            </div>
        </template>
    </SvwsUiSecondaryMenu>
</template>


<script setup lang="ts">
    import { Inertia } from "@inertiajs/inertia";
    import { SvwsUiSecondaryMenu, SvwsUiMenuItem } from '@svws-nrw/svws-ui';

    const navigate = (routeName: string): void => Inertia.get(route(routeName));
</script>


<style scoped>
    .container {
        @apply flex flex-col gap-1 mx-[1.1rem]
    }

</style>
