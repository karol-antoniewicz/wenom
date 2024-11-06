<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content">
                <div>
                    <SvwsUiCheckbox @click="saveSettings(enabled)" v-model="enabled" :value="true" type="toggle">Zwei-Faktor-Authentifizierung per E-Mail
                    </SvwsUiCheckbox>
                </div>
                <div style="padding-right: 28%">
                    <p>Mit der Aktivierung der Zwei-Faktor-Authentifizierung per E-Mail wird diese für alle User verpflichtend.</p>
                    <p>Nach der Aktivierung ist bei jedem Anmeldevorgang neben dem Passwort zusätzlich die Eingabe eines einmaligen Bestätigungscodes erforderlich.
                    Dieser Code wird an die im System gespeicherte E-Mail-Adresse gesendet. Die zusätzliche Sicherheitsmaßnahme dient dem Schutz Ihres Systems
                    vor unbefugtem Zugriff.</p>
                    <p>Bitte stellen Sie sicher, dass alle Benutzer Zugriff auf ihre E-Mail-Konten haben.</p>
                </div>
                <!-- <SvwsUiButton @click="saveSettings" :disabled="!isDirty">Speichern</SvwsUiButton> -->
            </div>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import { Ref, ref, watch, reactive } from 'vue';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import axios, { AxiosResponse } from 'axios';
    import { Inertia } from '@inertiajs/inertia';
    import SettingsMenu from '@/Components/SettingsMenu.vue';
    import { apiError, apiSuccess } from '@/Helpers/api.helper';
    import { SvwsUiHeader, SvwsUiButton, SvwsUiCheckbox, SvwsUiTextInput, SvwsUiSelect } from '@svws-nrw/svws-ui';
    import { MailSendCredentialsFormData as MailSendCredentials } from '../../Interfaces/Interface';

    let props = defineProps({
        auth: Object,
    });

    const title = 'Sicherheitseinstellungen';

    //TODO: fetch 2FA data from backend
    const enabled = ref(false);

    const storedDataForm: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(true);

    //backend doesn't exist yet
    // it does now :D - K
    axios.get(route('api.settings.two_factor_authentication'))
        .then((response: AxiosResponse): void => enabled.value = response.data);

    //TODO: save 2FA data too
    const saveSettings = (value: boolean) => axios.put(route('api.settings.two_factor_authentication'), {enabled: value})
        .then((response: AxiosResponse): void => {
            apiSuccess();
            console.log(response.data, value); // Just for testing - K
        });

    //TODO: check if wanted when backend is ready
    /* Ich hab das auskomentiert weil es bei mir Fehler verursacht hatte. - K
    watch(() => data.form, (): void => {
        isDirty.value = JSON.stringify(data.form) !== storedDataForm.value;
    }, {
        deep: true,
    });
    */
</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6 ml-6
    }

    /* button {
        @apply self-start
    } */

    .content {
        @apply px-11 flex flex-col gap-12
    }

    .content>div {
        @apply flex flex-col gap-5 justify-start
    }

    .error {
        @apply text-red-500 text-sm
    }
</style>
