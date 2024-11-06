<template>
    <SvwsUiAppLayout>
        <template #sidebar>
            <SvwsUiMenu>
                <template #header>
                    <SvwsUiMenuHeader :user="currentUser()" :schule="(usePage().props.value.schoolName as string)" @click="navigate('user_settings.filter')"/>
                </template>

                <template #default>
                    <SvwsUiMenuItem
                        v-if="visible('mein_unterricht')"
                        :active="activePage('mein_unterricht')"
                        @click="navigate('mein_unterricht')"
                    >
                        <template #icon><ri-book-2-line /></template>
                        <template #label>Notenmanager</template>
                    </SvwsUiMenuItem>

                    <!-- TODO: visibility? -->
                    <SvwsUiMenuItem
                        :active="activePage('teilleistungen')"
                        @click="navigate('teilleistungen')"
                    >
                        <template #icon><ri-calendar-line /></template>
                        <template #label>Teilleistungen</template>
                    </SvwsUiMenuItem>

                    <SvwsUiMenuItem
                        :active="activePage('leistungsdatenuebersicht')"
                        @click="navigate('leistungsdatenuebersicht')"
                    >
                        <template #icon><ri-book-read-line /></template>
                        <template #label>Leistungsdatenübersicht</template>
                    </SvwsUiMenuItem>

                    <SvwsUiMenuItem
                        v-if="visible('klassenleitung')"
                        :active="activePage('klassenleitung')"
                        @click="navigate('klassenleitung')"
                    >
                        <template #icon><ri-team-line /></template>
                        <template #label>Klassenleitung</template>
                    </SvwsUiMenuItem>
                </template>

                <template #footer>
                    <SvwsUiMenuItem
                        v-if="visible('settings')"
                        @click="navigate('settings.matrix')"
                    >
                        <template #icon>
                           <ri-settings-3-line />
                        </template>
                        <template #label>Einstellungen</template>
                    </SvwsUiMenuItem>

                    <SvwsUiMenuItem @click="logout()">
                        <template #icon><ri-logout-circle-line /></template>
                        <template #label>Abmelden</template>
                    </SvwsUiMenuItem>
                </template>

                <template #version>
                    {{ usePage().props.value.version }}
                </template>
            </SvwsUiMenu>
        </template>

        <template #main>
            <toast />
            <slot name="main" />
        </template>

        <template #secondaryMenu v-if="slots.secondaryMenu">
            <slot name="secondaryMenu" />
        </template>

        <template v-slot:aside v-if="slots.aside">
            <slot name="aside" />
        </template>
    </SvwsUiAppLayout>
</template>


<script setup lang="ts">
    import { ref, useSlots } from 'vue';
    import { Inertia } from '@inertiajs/inertia'
    import { usePage } from '@inertiajs/inertia-vue3'
    import { Auth } from '@/Interfaces/Interface'
    import Toast from '@/Components/Toast.vue';
    import { SvwsUiAppLayout, SvwsUiMenu, SvwsUiMenuHeader, SvwsUiMenuItem } from '@svws-nrw/svws-ui';

    const modal = ref<any>(null);
    const modalInfo = ref<any>(null);
    const slots = useSlots();
    const auth: Auth = usePage().props.value.auth as Auth;

    const visible = (item: 'mein_unterricht' | 'klassenleitung' | 'settings'): boolean => {
        switch (item) {
            case 'mein_unterricht':
                return !auth.administrator || (auth.user.lerngruppen.length > 0 && auth.administrator);
            case 'klassenleitung':
                return auth.user.klassen.length > 0;
            case 'settings':
                return auth.administrator;
            default:
                return false;
        }
    };

    const navigate = (routeName: string): void => Inertia.get(route(routeName));
    const logout = (): void => Inertia.post(route('logout'));
    const activePage = (routeName: string): boolean => route().current(routeName);
    const currentUser = (): string => [auth.user.vorname, auth.user.nachname].join(' ');
</script>


<style scoped>
    #header {
        @apply hover:cursor-pointer
    }

    .app--layout--wenom :global(.app--content) {
	    @apply rounded-2xl
    }

    .app--layout--wenom :global(.app--sidebar + .app--content) {
	    @apply rounded-none
    }


</style>
