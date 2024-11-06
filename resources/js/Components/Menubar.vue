<template>
    <SvwsUiSidebarMenu :collapsed="store.sidebarCollapsed" @toggle="toggleSidebar">
        <template #header>
            <SvwsUiSidebarMenuHeader :collapsed="store.sidebarCollapsed">SVWS-NRW</SvwsUiSidebarMenuHeader>
        </template>

        <template #default>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" @click="navigate('dashboard')" :active="route().current('dashboard')">
                <template #label>Notenmanager</template>
                <template #icon>
                    <span class="icon">
                       <i-ri-team-line aria-hidden="true"></i-ri-team-line>
                    </span>
                </template>
            </SvwsUiSidebarMenuItem>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" @click="navigate('teilleistungen')" :active="route().current('teilleistungen')">
                <template #label>Teilleistungen</template>
                <template #icon>
                    <span class="icon">
                       <i-ri-team-line aria-hidden="true"></i-ri-team-line>
                    </span>
                </template>
            </SvwsUiSidebarMenuItem>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" @click="navigate('   ')" :active="route().current('leistungsdatenuebersicht')">
                <template #label>Leistungsdatenübersicht</template>
                <template #icon>
                    <span class="icon">
                       <i-ri-book-read-line aria-hidden="true"></i-ri-book-read-line>
                    </span>
                </template>
            </SvwsUiSidebarMenuItem>
            <SvwsUiSidebarMenuItem v-if="props.auth.user.klassen.length > 0 || props.auth.administrator" :collapsed="store.sidebarCollapsed" @click="navigate('klassenleitung')" :active="route().current('klassenleitung')">
                <template #label>Klassenleitung</template>
                <template #icon>
                    <span class="icon">
                        <i-ri-user2-line aria-hidden="true"></i-ri-user2-line>
                    </span >
                </template>
            </SvwsUiSidebarMenuItem>
        </template>

        <template #footer>
            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" :active="route().current('settings.*')" @click="navigate('settings.matrix')" v-if="props.auth.administrator">
                <template #label>Einstellungen</template>
                <template #icon>
                    <span class="icon">
                       <i-ri-settings-3-line aria-hidden="true"></i-ri-settings-3-line>
                    </span>
                </template>
            </SvwsUiSidebarMenuItem>

            <SvwsUiSidebarMenuItem :collapsed="store.sidebarCollapsed" subline="Abmelden" @click="logout">
                <template #label>{{ auth.user.vorname }} {{ auth.user.nachname }}</template>
                <template #icon>
                    <span class="icon">
                       <i-ri-logout-box-line aria-hidden="true"></i-ri-logout-box-line>
                    </span>
                </template>
            </SvwsUiSidebarMenuItem>
        </template>
    </SvwsUiSidebarMenu>
</template>


<script setup lang="ts">
// TODO: TBR
    import { useStore } from '@/store';
    import { Inertia } from '@inertiajs/inertia';
    import { Auth } from '@/Interfaces/Interface'

    const store = useStore();

    let props = defineProps<{
        auth: Auth
    }>();

    const logout = (): void => Inertia.post(route('logout'));
    const navigate = (routeName: string): void => Inertia.get(route(routeName));
    const toggleSidebar = (value: boolean): boolean => store.sidebarCollapsed = value;
</script>


<style scoped>
    .sidebar--menu-header {
        @apply py-3
    }
    
</style>
