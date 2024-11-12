<template>
    <AppLayout title="Benutzereinstellungen">
        <template #main>
            <SvwsUiHeader>
                Spalteneinstellungen
            </SvwsUiHeader>
            <div class="content">
                <!-- Avoid "Uncaught (in promise) TypeError: Cannot read properties of undefined..." with conditional rendering  -->
                <div v-if="user_settings.filters_meinunterricht">
                    <h3 class="text-headline-md">Mein Unterricht</h3>
                        <!-- TLs hidden in both tables for the momment according to ticket 386 -->
                        <!-- <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.teilleistungen" type="toggle">Teilleistungen</SvwsUiCheckbox> -->
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.mahnungen" type="toggle">Mahnungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.fehlstunden" type="toggle">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.bemerkungen" type="toggle">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.kurs" type="toggle">Kurs</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.quartalnoten" type="toggle">Quartal</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.note" type="toggle">Note</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_meinunterricht.fach" type="toggle">Fach</SvwsUiCheckbox>
                </div>
                <div v-if="user_settings.filters_leistungsdatenuebersicht">
                        <h3 class="text-headline-md">Leistungsdatenübersicht</h3>
                        <!-- <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.teilleistungen" type="toggle">Teilleistungen</SvwsUiCheckbox> -->
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.fachlehrer" type="toggle">Fachlehrer</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.mahnungen" type="toggle">Mahnungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.fehlstunden" type="toggle">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.bemerkungen" type="toggle">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.kurs" type="toggle">Kurs</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.quartalnoten" type="toggle">Quartal</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.note" type="toggle">Note</SvwsUiCheckbox>
                        <SvwsUiCheckbox v-model="user_settings.filters_leistungsdatenuebersicht.fach" type="toggle">Fach</SvwsUiCheckbox>
                </div>
                <div class="clear"></div>
                
                <SvwsUiButton @click="saveSettings" class="button">Speichern</SvwsUiButton>
            </div>
        </template>
        <template #secondaryMenu>
            <UserSettingsMenu :lastLogin="props.auth.lastLogin" :email="props.auth.user.email"></UserSettingsMenu>
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
    import { Ref, ref } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiButton, SvwsUiCheckbox, SvwsUiHeader } from '@svws-nrw/svws-ui';
    import UserSettingsMenu from '@/Components/UserSettingsMenu.vue';
    import { Auth } from '@/Interfaces/Interface';

    const props = defineProps<{
        auth: Auth
    }>();

    interface UserSettings {
        filters_meinunterricht: {
            teilleistungen: boolean,
            mahnungen: boolean,
            fehlstunden: boolean,
            bemerkungen: boolean,
            kurs: boolean,
            quartalnoten: boolean,
            note: boolean,
            fach: boolean,
        },
        filters_leistungsdatenuebersicht: {
            teilleistungen: boolean,
            fachlehrer: boolean,
            mahnungen: boolean,
            fehlstunden: boolean,
            bemerkungen: boolean,
            kurs: boolean,
            quartalnoten: boolean,
            note: boolean,
            fach: boolean,
        },
    }

    const user_settings: Ref<UserSettings> = ref({} as UserSettings);

    axios.get(route('user_settings.get_all_filters'))
        .then((response: AxiosResponse): AxiosResponse => user_settings.value = response.data);
    
    const saveSettings = () => axios
        .post(route('user_settings.set_filters'), {
            'filters_meinunterricht': {
                'mahnungen': user_settings.value.filters_meinunterricht.mahnungen,
                'bemerkungen': user_settings.value.filters_meinunterricht.bemerkungen,
                'fehlstunden': user_settings.value.filters_meinunterricht.fehlstunden,
                'teilleistungen': user_settings.value.filters_meinunterricht.teilleistungen,
                'kurs': user_settings.value.filters_meinunterricht.kurs,
                'quartalnoten': user_settings.value.filters_meinunterricht.quartalnoten,
                'note': user_settings.value.filters_meinunterricht.note,
                'fach': user_settings.value.filters_meinunterricht.fach,
            },
            'filters_leistungsdatenuebersicht': {
                'mahnungen': user_settings.value.filters_leistungsdatenuebersicht.mahnungen,
                'fachlehrer': user_settings.value.filters_leistungsdatenuebersicht.fachlehrer,
                'bemerkungen': user_settings.value.filters_leistungsdatenuebersicht.bemerkungen,
                'fehlstunden': user_settings.value.filters_leistungsdatenuebersicht.fehlstunden,
                'teilleistungen': user_settings.value.filters_leistungsdatenuebersicht.teilleistungen,
                'kurs': user_settings.value.filters_leistungsdatenuebersicht.kurs,
                'quartalnoten': user_settings.value.filters_leistungsdatenuebersicht.quartalnoten,
                'note': user_settings.value.filters_leistungsdatenuebersicht.note,
                'fach': user_settings.value.filters_leistungsdatenuebersicht.fach,
            },
        })
        .then((): void => apiSuccess())
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von Persönlichen Filtern'
        ))
</script>


<style scoped>
    .content {
        @apply grid md:grid-cols-2 col-span-3 gap-12 md:max-w-4xl px-6 
    }

    .content > div {
        @apply flex flex-col gap-5 justify-start ml-6
    }

    .button {
        @apply justify-center top-4 w-fit left-2/4
    }
</style>
