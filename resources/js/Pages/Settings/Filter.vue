<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content">
                <div>
                    <h3 class="text-headline-md">Mein Unterricht</h3>
                    <!-- TLs hidden in both tables for the momment according to ticket 386 -->
                    <!-- <SvwsUiCheckbox v-model="meinunterrichtSettings.teilleistungen" :value="true" type="toggle">Teilleistungen</SvwsUiCheckbox> -->
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.mahnungen" :value="1" type="toggle">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.fehlstunden" :value="1" type="toggle">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.bemerkungen" :value="1" type="toggle">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.kurs" :value="1" type="toggle">Kurs</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.quartalnoten" :value="1" type="toggle">Quartal</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.note" :value="1" type="toggle">Note</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="meinunterrichtSettings.fach" :value="1" type="toggle">Fach</SvwsUiCheckbox>
                </div>

                <div>
                    <h3 class="text-headline-md">Leistungsdatenübersicht</h3>
                    <!-- <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.teilleistungen" :value="true" type="toggle">Teilleistungen</SvwsUiCheckbox> -->
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.fachlehrer" :value="1" type="toggle">Fachlehrer</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.mahnungen" :value="1" type="toggle">Mahnungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.fehlstunden" :value="1" type="toggle">Fachbezogene Fehlstunden</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.bemerkungen" :value="1" type="toggle">Fachbezogene Bemerkungen</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.kurs" :value="1" type="toggle">Kurs</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.quartalnoten" :value="1" type="toggle">Quartal</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.note" :value="1" type="toggle">Note</SvwsUiCheckbox>
                    <SvwsUiCheckbox v-model="leistungsdatenuebersichtSettings.fach" :value="1" type="toggle">Fach</SvwsUiCheckbox>
                </div>
                <div class="clear"></div>

                <SvwsUiButton @click="saveSettings" class="button" :disabled="!isDirty">Speichern</SvwsUiButton>
            </div>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import { Ref, ref, watch } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import SettingsMenu from '@/Components/SettingsMenu.vue';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiHeader, SvwsUiButton, SvwsUiCheckbox } from '@svws-nrw/svws-ui';

    let props = defineProps({
        auth: Object,
    });

    //two different interfaces are used here because the usage of one interface with 2 depth levels caused a reload
    //of the whole settings page that we do not want
    interface MeinunterrichtSettings {
        teilleistungen: boolean,
        mahnungen: boolean,
        fehlstunden: boolean,
        bemerkungen: boolean,
        kurs: boolean,
        quartalnoten: boolean,
        note: boolean,
        fach: boolean,
    }

    interface LeistungsdatenuebersichtSettings {
        teilleistungen: boolean,
        fachlehrer: boolean,
        mahnungen: boolean,
        fehlstunden: boolean,
        bemerkungen: boolean,
        kurs: boolean,
        quartalnoten: boolean,
        note: boolean,
        fach: boolean,
    }

    const title = 'Spalteneinstellungen';

    const meinunterrichtSettings: Ref<MeinunterrichtSettings> = ref({} as MeinunterrichtSettings);
    const leistungsdatenuebersichtSettings: Ref<LeistungsdatenuebersichtSettings> = ref({} as LeistungsdatenuebersichtSettings);
    const storedMeinunterrichtSettings: Ref<String> = ref('');
    const storedLeistungsdatenuebersichtSettings: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(false);

    axios.get(route('api.settings.filters'))
        .then((response: AxiosResponse): void => {
            meinunterrichtSettings.value = response.data.meinunterricht;
            leistungsdatenuebersichtSettings.value = response.data.leistungsdatenuebersicht;
            storedMeinunterrichtSettings.value = JSON.stringify(meinunterrichtSettings.value);
            storedLeistungsdatenuebersichtSettings.value = JSON.stringify(leistungsdatenuebersichtSettings.value);
        });

    const saveSettings = () => axios
        .post(route('api.settings.filters'),  {
            'filters_meinunterricht': {
                'mahnungen': meinunterrichtSettings.value.mahnungen,
                'bemerkungen': meinunterrichtSettings.value.bemerkungen,
                'fehlstunden': meinunterrichtSettings.value.fehlstunden,
                'teilleistungen': meinunterrichtSettings.value.teilleistungen,
                'kurs': meinunterrichtSettings.value.kurs,
                'quartalnoten': meinunterrichtSettings.value.quartalnoten,
                'note': meinunterrichtSettings.value.note,
                'fach': meinunterrichtSettings.value.fach,
            },
            'filters_leistungsdatenuebersicht': {
                'mahnungen': leistungsdatenuebersichtSettings.value.mahnungen,
                'fachlehrer': leistungsdatenuebersichtSettings.value.fachlehrer,
                'bemerkungen': leistungsdatenuebersichtSettings.value.bemerkungen,
                'fehlstunden': leistungsdatenuebersichtSettings.value.fehlstunden,
                'teilleistungen': leistungsdatenuebersichtSettings.value.teilleistungen,
                'kurs': leistungsdatenuebersichtSettings.value.kurs,
                'quartalnoten': leistungsdatenuebersichtSettings.value.quartalnoten,
                'note': leistungsdatenuebersichtSettings.value.note,
                'fach': leistungsdatenuebersichtSettings.value.fach,
            },
        })
        .then((): void => apiSuccess())
        .then((): void => {
            isDirty.value = false;
            storedMeinunterrichtSettings.value = JSON.stringify(meinunterrichtSettings.value);
            storedLeistungsdatenuebersichtSettings.value = JSON.stringify(leistungsdatenuebersichtSettings.value);
        })
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von Filtern'
        ));

    watch(() => [meinunterrichtSettings.value, leistungsdatenuebersichtSettings.value], (): void => {
        isDirty.value = JSON.stringify(meinunterrichtSettings.value) !== storedMeinunterrichtSettings.value
        || JSON.stringify(leistungsdatenuebersichtSettings.value) !== storedLeistungsdatenuebersichtSettings.value;
    }, {
         deep: true,
    });
</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6 ml-6
    }

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