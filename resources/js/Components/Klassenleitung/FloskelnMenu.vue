<template>
    <aside class="bg-white border-dark p-6 z-50 fixed top-0 right-0 bottom-0 w-1/2 border-l-2 border-black/20 flex flex-col gap-6" v-if="props.selected">
        <header class="flex gap-6 justify-between">
            <div class="flex gap-6 items-center">
                <h1 class="headline-1 text-black">
                    {{ state.schueler?.nachname }}, {{ state.schueler?.vorname }}
                </h1>

                <SvwsUiBadge variant="highlight" size="big" class="px-6 uppercase">
                    {{ props.selected?.floskelgruppe }}
                </SvwsUiBadge>
            </div>

            <SvwsUiButton @click="close" type="transparent">
                <span class="icon">
                   <span class="sr-only">Schließen</span>
                   <i-ri-close-line aria-hidden="true"></i-ri-close-line>
                </span>
            </SvwsUiButton>
        </header>
        <div class="flex flex-col gap-12">
            <div class="h-1/2 flex flex-col gap-3">
                <SvwsUiTextareaInput resizeable="none" class="flex-1" :modelValue="computedBemerkung" placeholder="Tragen Sie bitte hier Ihre Bemerkungen ein." @update:modelValue="updateBemerkung"></SvwsUiTextareaInput>
                <div class="flex gap-3">
                    <SvwsUiButton @click="setBemerkungen" :type="state.isDirty ? 'primary' : 'secondary'">Speichern</SvwsUiButton>
                    <SvwsUiButton @click="close" v-show="state.isDirty" type="secondary">Verwerfen</SvwsUiButton>
                </div>
            </div>

            <div v-if="currentFloskelGruppe">
                <FloskelTable floskelgruppe="zb" :floskelgruppen="props.floskelgruppen" @added="addFloskeln"></FloskelTable>
            </div>
        </div>
    </aside>
</template>


<script setup lang="ts">// TODO: TBR
    import {computed, reactive, watch} from "vue";
    import axios from "axios";
    import { Column, Schueler, Floskelgruppe, Occurrence, Pronoun } from '@/Interfaces/Interface'

    type Selected = {
        floskelgruppe: string,
        schueler: Schueler,
    };

    import FloskelTable from './FloskelTable.vue';

    interface Props {
        selected?: Selected | null,
        floskelgruppen: Floskelgruppe[],
    };

    const emit = defineEmits(['close', 'updated']);

    let props = defineProps<Props>();

    let state = reactive({
        bemerkung: <string> '',
        storedBemerkung: <string> '',
        schueler : <Schueler | null> null,
        isDirty: <boolean> false,
        floskelgruppen: <Floskelgruppe[]> [],
        columns: <Column[]> [
            { key: 'kuerzel', label: 'Kürzel', sortable: true },
            { key: 'text', label: 'Text', sortable: true },
        ],
    });

    watch(() => props.selected, (selected: Selected | undefined | null): void => {
        state.schueler = selected?.schueler || null;
        state.bemerkung = state.storedBemerkung = selected?.schueler[selected?.floskelgruppe];
        state.floskelgruppen = props.floskelgruppen;
    });

    const computedBemerkung = computed((): string | undefined => {
        state.isDirty = state.bemerkung != state.storedBemerkung;

        if (!state.bemerkung) {
            return;
        }

        const pattern: RegExp = /\$VORNAME\$ \$NACHNAME\$|\$VORNAME\$|\$Vorname\$|\$NACHNAME\$/;
        let schueler = state.schueler;

        let pronouns: Pronoun = { m: 'Er', w: 'Sie' };
        let pronoun: string | null = pronouns[schueler!.geschlecht] !== undefined ? pronouns[schueler!.geschlecht] : null;

        let initialOccurrence: Occurrence = {
            "$vorname$ $nachname$": [schueler!.vorname, schueler!.nachname].join(' '),
            "$vorname$": schueler!.vorname,
            "$nachname$": schueler!.nachname,
        };

        let succeedingOccurrences: Occurrence = {
            "$vorname$ $nachname$": pronoun ?? schueler!.vorname,
            "$vorname$": pronoun ?? schueler!.vorname,
            "$nachname$": null
        };

        return state.bemerkung
            .replace(new RegExp(pattern,"i"), (matched: string): string => {
                return initialOccurrence[matched.toLowerCase()];
            })
            .replaceAll(new RegExp(pattern ,"gi"), (matched: string): string => {
                return succeedingOccurrences[matched.toLowerCase()];
            });
    });

    const updateBemerkung = (bemerkung: string): string => state.bemerkung = bemerkung;

    const setBemerkungen = (): Promise<void> => axios
        .post(
            route('set_schueler_bemerkung', state.schueler!.id),
            {key: props.selected?.floskelgruppe, value: state.bemerkung}
        ).then(() => {
            emit('updated');
            state.storedBemerkung = state.bemerkung;
            state.isDirty = false;
            return;
        });

    const currentFloskelGruppe = computed((): Floskelgruppe | undefined => state.floskelgruppen.find(
        floskelgruppe => floskelgruppe.kuerzel == props.selected!.floskelgruppe
    ));

    const addFloskeln = (bemerkung: string): string => state.bemerkung = [state.bemerkung, bemerkung].join(' ').trim();

    const deleteConfirmationText: string = "Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren.";

    const close = (): void => {
        if (state.isDirty ? confirm(deleteConfirmationText) : true) {
            emit('close');
        }
    };

    window.addEventListener("beforeunload", e => {
        if (state.isDirty) {
            (e || window.event).returnValue = deleteConfirmationText;
            return deleteConfirmationText;
        }

        emit('close');
    }, {capture: true});
</script>


<style scoped>
    .icon > svg {
        @apply w-8 h-8
    }
</style>
