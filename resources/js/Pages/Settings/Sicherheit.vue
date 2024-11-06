<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="form-control-area">
                <div>
                    <h3 class="text-headline-md">Kontoeinstellungen</h3>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.mailer" :valid="() => !hasErrors('MAIL_MAILER')" type="text"
                            placeholder="Mailer"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_MAILER')" class="error">
                            {{ getError('MAIL_MAILER') }}
                        </span>
                    </div>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.host" :valid="() => !hasErrors('MAIL_HOST')" type="text"
                            placeholder="HOST_URL"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_HOST')" class="error">
                            {{ getError('MAIL_HOST') }}
                        </span>
                    </div>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.port" :valid="() => !hasErrors('MAIL_PORT')" type="text"
                            placeholder="PORT"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_PORT')" class="error">
                            {{ getError('MAIL_PORT') }}
                        </span>
                    </div>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.username" :valid="() => !hasErrors('MAIL_USERNAME')"
                            type="text" placeholder="Benutzername"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_USERNAME')" class="error">
                            {{ getError('MAIL_USERNAME') }}
                        </span>
                    </div>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.password" :valid="() => !hasErrors('MAIL_PASSWORD')"
                            type="password" placeholder="Passwort"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_PASSWORD')" class="error">
                            {{ getError('MAIL_PASSWORD') }}
                        </span>
                    </div>
                    <div class="form-control">
                        <SvwsUiSelect v-model="data.form.encryption" :items="verschluesselungOptions"
                            :item-text="item => item" label="Verschlüsselung"></SvwsUiSelect>
                    </div>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.from_address" :valid="() => !hasErrors('MAIL_FROM_ADDRESS')"
                            type="email" placeholder="No-Reply-Adresse"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_FROM_ADDRESS')" class="error">
                            {{ getError('MAIL_FROM_ADDRESS') }}
                        </span>
                    </div>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="data.form.from_name" :valid="() => !hasErrors('MAIL_FROM_NAME')"
                            type="text" placeholder="Absender"></SvwsUiTextInput>
                        <span v-if="hasErrors('MAIL_FROM_NAME')" class="error">
                            {{ getError('MAIL_FROM_NAME') }}
                        </span>
                    </div>
                    <SvwsUiButton id="save-button" @click="saveSettings" :disabled="!isDirty">Speichern</SvwsUiButton>
                </div>
                <div>
                    <h3 class="text-headline-md">Testmail</h3>
                    <div class="form-control">
                        <SvwsUiTextInput v-model="recipient" type="text" placeholder="Empfänger"></SvwsUiTextInput>
                        <span class="error"></span>
                    </div>
                    <SvwsUiButton id="testmail-button" @click="sendTestmail" type="secondary">Testmail senden</SvwsUiButton>
                </div>
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

    const title = 'Mailkonfiguration';

    //TODO: fetch 2FA data from backend
    const enabled = ref(false);

        //items (iterable group of strings) for SvwsUiSelect; these strings are displayed as dropdown options within the element
    const verschluesselungOptions = reactive<string[]>([
        "keine",
        "tls",
        "ssl"
    ]);

    //"encryption: verschluesselungOptions[1]" to display "tls" as default would have no effect here because axios.get(route('api.settings.mail_send_credentials')
    //always overwrites all form defaults with the variables present in the .env file
    let data: MailSendCredentials = reactive({
        form: {
            mailer: 0,
            host: '',
            port: '',
            username: '',
            password: '',
            encryption: '',
            from_address: '',
            from_name: '',
        },
        //TODO: unused
        processing: false,
        errors: {},
        //TODO: unused
        successMessage: false,
    });

    const recipient: Ref<String> = ref('');

    //when the encryption variable is "null" or null in the .env file, we want to display "keine" instead of just an empty string in the select options
    const convertEncryptionValueForDisplay = (): void => {
        if (JSON.stringify(data.form.encryption) === "null") {
            data.form.encryption = "keine";
        }
    }

    const getError = (column: string): string => data.errors[column][0];
    const hasErrors = (column: string): boolean => column in data.errors;

    const storedDataForm: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(true);

    axios.get(route('api.settings.mail_send_credentials'))
        .then((response: AxiosResponse): void => {
            data.form = response.data;
            convertEncryptionValueForDisplay();
            storedDataForm.value = JSON.stringify(data.form);
        });

    //TODO: save 2FA data too
    const saveSettings = () => axios
        .put(route('api.settings.mail_send_credentials'), {
            'MAIL_MAILER': data.form.mailer,
            'MAIL_HOST': data.form.host,
            'MAIL_PORT': data.form.port,
            'MAIL_USERNAME': data.form.username,
            'MAIL_PASSWORD': data.form.password,
            'MAIL_ENCRYPTION': data.form.encryption === "keine" ? "null" : data.form.encryption,
            'MAIL_FROM_ADDRESS': data.form.from_address,
            'MAIL_FROM_NAME': data.form.from_name,
        })
        .then((): void => apiSuccess())
        .then((): void  => {
                isDirty.value = false;
                storedDataForm.value = JSON.stringify(data.form);
        })
        .catch((error: any): void => {
            apiError(error, 'Speichern der Änderungen fehlgeschlagen!');
            data.errors = error.response.data.errors;
        });

    const sendTestmail = (): void => {
        axios.post(route('settings.mail_test'), { email: recipient.value });
    };

    watch(() => data.form, (): void => {
        isDirty.value = JSON.stringify(data.form) !== storedDataForm.value;
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

    .form-control-area {
        @apply grid md:grid-cols-2 max-w-[68rem] px-6 pt-6
    }

    .form-control-area > div {
        @apply flex flex-col gap-12 w-9/12 justify-start ml-6
    }

    .form-control {
        @apply -my-2
    }

    button {
        @apply justify-center top-4 w-fit self-end
    }

    .error {
        @apply text-red-500 text-sm
    }
</style>
