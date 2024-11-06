<template>
    <SvwsUiSecondaryMenu>
        <template #headline>
                Persönliche Einstellungen
                <p class="login">Letzter Login: {{ props.lastLogin }}</p>
                <p class="login">Ihre E-Mail-Adresse: {{ props.email }}</p>
        </template>
        <template #content>
            <div class="container">
                <SvwsUiMenuItem @click="navigate('user_settings.filter')" :active="route().current('user_settings.filter')">
                    <template #label>Spalteneinstellungen</template>
                </SvwsUiMenuItem>
                <SvwsUiMenuItem @click="navigate('user_settings.security')" :active="route().current('user_settings.security')">
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

    const props = defineProps({
        lastLogin: {
            type: String,
            required: true,
        },
        email: {
            type: String,
            required: true,
        },
    });

</script>


<style scoped>
    .container {
        @apply flex flex-col leading-3 mx-[1.1rem]
    }

    .login {
        @apply text-sm mt-2 text-black opacity-50;
    }

</style>
