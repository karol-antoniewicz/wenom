<template>
    <AppLayout title="Einstellungen">
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content">
                <p v-if="clientExists">Letzte Änderung: {{ convertedClientRecordTimestamp }}</p>
                <div>Klicken Sie auf den Button, um einen neuen Zugang für den SVWS-Server einzurichten.
                    <SvwsUiButton @click="openModal()" type="secondary">
                        Generieren
                    </SvwsUiButton>
                </div>
            </div>
            <SvwsUiModal id="clientModal" ref="modal" :show="showModal" size="medium">
                <template #modalTitle>
                    {{ modalTitle }}
                </template>
                <template #modalContent>
                    <div ref="newClientDataInfo" class="client-data-block" v-if="newClientCreated">
                        <p style="margin-bottom: 1.1em;">Diese Information wird Ihnen einmalig in diesem Fenster eingeblendet.</p>
                        <br />
                        <p style="margin-bottom: 1em;"><span class="client-data-fields">URL:</span> {{ url }} <ri-file-copy-line class="copy-button" @click="copyToClipboard(newClientDataInfo, 0)"></ri-file-copy-line></p>
                        <p><span class="client-data-fields">Secret:</span> {{ clientRecord.secret }} <ri-file-copy-line class="copy-button" @click="copyToClipboard(newClientDataInfo, 1)"></ri-file-copy-line></p>
                        <br />
                    </div>
                    <p v-else>{{ adjustSettingsInfo }}</p>
                </template>
                <template #modalActions>
                    <div class="buttons-block">
                        <SvwsUiButton v-if="!newClientCreated" @click="adjustSettings()" type="secondary">Generieren
                        </SvwsUiButton>
                        <SvwsUiButton @click="closeModal()" type="secondary">Schließen</SvwsUiButton>
                    </div>
                </template>
            </SvwsUiModal>
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
    import { apiError } from '@/Helpers/api.helper';
    import { SvwsUiHeader, SvwsUiButton, SvwsUiModal } from '@svws-nrw/svws-ui';
    import { usePage } from '@inertiajs/inertia-vue3';

    let props = defineProps({
        auth: Object,
    });

    //TODO: remove whatever if finally unused/unnecessary
    interface ClientRecord {
        id: number,
        name: string,
        secret: string,
        created_at: Date,
    }

    const title = 'Synchronisation';

    //TODO: name is always SVWS-Server now, so some things here are probably not necessary anymore
    const newClientName: string = usePage().props.value.schoolName as string;
    const clientRecord: Ref<ClientRecord> = ref({} as ClientRecord);
    const url: string = usePage().props.value.appUrl as string;
    const clientExists: Ref<boolean> = ref(false);
    const convertedClientRecordTimestamp: Ref<string> = ref("");
    const newClientCreated: Ref<boolean> = ref(false);
    const newClientDataInfo: Ref<HTMLDivElement>= ref({} as HTMLDivElement);
    const modal = ref<any>(null);
    const modalTitle: Ref<string> = ref("Warnung");
    const adjustSettingsInfo: Ref<string> = ref("Es müssen die Einstellungen im zugehörigen SVWS-Server angepasst werden.");
    const _showModal: Ref<boolean> = ref(false);

    const showModal = (): Ref<boolean> => _showModal;

    const openModal = (): boolean => _showModal.value = true;

    const closeModal = (): boolean => _showModal.value = false;

    const copyToClipboard = (receivedClientDataInfo: HTMLDivElement, position: number): void => {
        const textToCopy: string[] = receivedClientDataInfo.innerText.slice(74).split(" \n");
        navigator.clipboard.writeText(textToCopy[position].replace(new RegExp('(URL: |Secret: )'), ""));
    };


    axios.get(route('passport.index'))
        .then((response: AxiosResponse): void => {
            for (let record in response.data) {
                if (response.data[record].name == "SVWS-Server") {
                    clientRecord.value = response.data[record];
                    convertedClientRecordTimestamp.value =
                        new Date(clientRecord.value.created_at).toLocaleString('de-DE').replace(", ", ", Uhr ");
                    clientExists.value = true;
                }
            }
        });

    const adjustSettings = (): void => {
        axios.post(route('passport.store'))
            .then((response: AxiosResponse) => {
                clientRecord.value = response.data;
                convertedClientRecordTimestamp.value =
                    new Date(clientRecord.value.created_at).toLocaleString('de-DE', {
                        dateStyle: "medium",
                        timeStyle: "medium"
                    }).replace(", ", ", Uhr ");
                clientExists.value = true;
                newClientCreated.value = true;
                modalTitle.value = "Neuer Zugang erfolgreich angelegt";
            })
            .catch((error: any): void => apiError(error));
    }

    watch(() => _showModal.value, () => {
        if (_showModal.value == false) {

            newClientCreated.value = false;
            modalTitle.value = "Warnung";
        }
    });
</script>

<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6
    }

    .content {
        @apply px-11 flex flex-col gap-12
    }

    .content>div {
        @apply flex flex-col gap-5 justify-start
    }

    .client-data-fields {
        @apply font-bold
    }

    .client-data-block {
        @apply text-left pl-2
    }
    
    .buttons-block {
        @apply flex justify-end gap-2 -mr-[55%]
    }

    .button {
        @apply self-start
    }
    
    .copy-button { 
        @apply inline hover:text-black hover:border-black active:text-sortingblue
    }
</style>