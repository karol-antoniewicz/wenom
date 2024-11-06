<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content">
                <div>
                    <h3 class="headline-3">Schule</h3>
                    <SvwsUiTextInput v-model="settings.name" placeholder="Name der Schule"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.address" placeholder="Adresse der Schule"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.email" placeholder="E-Mail Adresse der Schule"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Schulleitung</h3>
                    <SvwsUiTextInput v-model="settings.management_name" placeholder="Name Schulleitung"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.management_telephone" placeholder="Sekretariat"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.management_email" placeholder="E-Mail-Adresse"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Schulträger</h3>
                    <SvwsUiTextInput v-model="settings.board_name" placeholder="Name des Schulträgers"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.board_address" placeholder="Anschrift des Schulträgers"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.board_contact" placeholder="Kontaktdaten des Schulträgers"></SvwsUiTextInput>
                </div>

                <div>
                    <h3 class="headline-3">Datenschutz</h3>
                    <SvwsUiTextInput v-model="settings.gdpr_email" placeholder="[Email des Datenschutzbeauftragten]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.gdpr_address" placeholder="[Anschrift des Datenschutzbeauftragten]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.hosting_provider_name" placeholder="[Name des Hosters]"></SvwsUiTextInput>
                    <SvwsUiTextInput v-model="settings.hosting_provider_address" placeholder="[Anschrift des Hosters]"></SvwsUiTextInput>
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
    import { ref, Ref, watch } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import SettingsMenu from '@/Components/SettingsMenu.vue';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiHeader, SvwsUiTextInput, SvwsUiButton } from '@svws-nrw/svws-ui';

    let props = defineProps({
        auth: Object,
    });

    interface Settings {
        name: number,
        address: string,
        email: string,
        management_name: string,
        management_telephone: string,
        management_email: string,
        board_name: string,
        board_address: string,
        board_contact: string,
        gdpr_email: string,
        gdpr_address: string,
        hosting_provider_name: string,
        hosting_provider_address: string,
    }

    const title = 'Schuldaten';

    const settings: Ref<Settings> = ref({} as Settings);
    const storedSettings: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(false);

    axios.get(route('api.settings.index', 'general'))
        .then((response: AxiosResponse): void => {
            settings.value = response.data;
            storedSettings.value = JSON.stringify(settings.value);
        });

    const saveSettings = () => axios
        .put(route('api.settings.bulk_update', { group: 'general' }),  { settings: settings.value })
        .then((): void => apiSuccess())
        .then((): void => {
            isDirty.value = false;
            storedSettings.value = JSON.stringify(settings.value);
        })
        .catch((error: any): void => apiError(
            error,
            'Ein Problem ist aufgetreten bei Speichern von "Die Klassenleitung darf alle Leistungsdaten bearbeiten."'
        ));

    watch(() => settings.value, (): void => {
        isDirty.value = JSON.stringify(settings.value) !== storedSettings.value;
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
        @apply justify-center top-4 w-fit left-3/4
    }
</style>
