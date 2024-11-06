<template>
    <SvwsUiContentCard :title="`${props.leistung.vorname} ${props.leistung.nachname}, Klasse ${props.leistung.klasse}, ${props.leistung.fach}${props.leistung.kurs ? ' (' + props.leistung.kurs + ')' : ''}`">
        <SvwsUiInputWrapper :grid="1">

            <SvwsUiTextareaInput :rows="3" v-model="bemerkung" :disabled="!isEditable" placeholder="Bemerkung" resizeable="vertical"
                @input="bemerkung = $event.target.value" @keydown="onKeyDown" />

            <div class="buttons">
                <SvwsUiButton v-if="isEditable" :disabled="!isDirty" @click="save">Zuweisen</SvwsUiButton>
                <SvwsUiButton @click="close" :type="isDirty && isEditable ? 'danger' : 'secondary'">Schließen</SvwsUiButton>
            </div>

            <SvwsUiTable v-if="isEditable" :items="rowsFiltered" :columns="columns" clickable count
                :filtered="filtered()" :filterReset="filterReset" :allowArrowKeySelection="true">
                <template #filterAdvanced>
                    <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                    <SvwsUiMultiSelect v-if="niveauItems.length" label="Niveau" :items="niveauItems" :item-text="item => item" v-model="niveauFilter" />
                    <SvwsUiMultiSelect v-if="jahrgangItems.length" label="Jahrgang" :items="jahrgangItems" :item-text="item => item" v-model="jahrgangFilter" />
                </template>
                <template #cell(kuerzel)="{ value, rowIndex }">
                    <button @click="add(rows[rowIndex])">
                    {{ value }}
                    </button>
                </template>
                <template #cell(text)="{ value, rowIndex }">
                    <button @click="add(rows[rowIndex])" class="text-cell-button">
                    {{ value }}
                    </button>
                </template>
            </SvwsUiTable>
        </SvwsUiInputWrapper>
    </SvwsUiContentCard>
</template>


<script setup lang="ts">
    import { computed, onMounted, ref, Ref, watch } from 'vue';
    import axios, { AxiosError, AxiosResponse } from 'axios';
    import { Leistung, FachbezogeneFloskel } from '@/Interfaces/Interface';
    import {
        DataTableColumn, SvwsUiButton, SvwsUiMultiSelect, SvwsUiTable, SvwsUiTextareaInput, SvwsUiTextInput, SvwsUiContentCard,
    } from '@svws-nrw/svws-ui';
    import {
        addSelectedToBemerkung, closeEditor, formatBasedOnGender, pasteShortcut, search, multiselect,
    } from '@/Helpers/bemerkungen.helper';

    const props = defineProps<{
        leistung: Leistung,
        editable: boolean,
    }>();

    const emit = defineEmits<{
        (event: 'close'): void;
        (event: 'updated', value: string|null): void;
    }>();

    const rows: Ref<FachbezogeneFloskel[]> = ref([]);
    const columns: Ref<DataTableColumn[]> = ref([
        { key: 'kuerzel', label: 'Kürzel', sortable: true, minWidth: 6 },
        { key: 'text', label: 'Text', sortable: true, span: 5 },
        { key: 'niveau', label: 'Niveau', sortable: true, minWidth: 6 },
        { key: 'jahrgang', label: 'Jahrgang', sortable: true, minWidth: 8 },
    ]);

    const bemerkung: Ref<string|null> = ref(props.leistung.fachbezogeneBemerkungen);
    const storedBemerkung: Ref<string|null> = ref(props.leistung.fachbezogeneBemerkungen);

    const isDirty: Ref<boolean> = ref(false);
    const isEditable: Ref<boolean> = ref(true);

    // Watchers
    onMounted((): void => setup());
    watch((): Leistung => props.leistung, (): void => setup());
    watch((): string | null => bemerkung.value, (): void => {
        isDirty.value = storedBemerkung.value !== bemerkung.value;
    });

    const setup = (): void => {
        bemerkung.value = props.leistung.fachbezogeneBemerkungen;
        storedBemerkung.value = props.leistung.fachbezogeneBemerkungen;
        isDirty.value = false;
        isEditable.value = props.leistung.editable.fb && props.editable;
        fetch();
    };

    const fetch = (): Promise<void> => axios
        .get(route('api.fachbezogene_floskeln', props.leistung.fach_id))
        .then((response: AxiosResponse): void => rows.value = response.data?.data || null)
        .catch((error: AxiosError): void => {
            alert('Ein Fehler ist aufgetreten.');
            console.log(error);
        })
        .finally((): void => {
            niveauItems.value = mapFilterOptions('niveau');
            jahrgangItems.value = mapFilterOptions('jahrgang');
        });

    // Filters
    const searchFilter: Ref<string> = ref('');
    const jahrgangFilter: Ref <string[]> = ref([]);
    const niveauFilter: Ref <string[]> = ref([]);

    const jahrgangItems: Ref<string[]> = ref([]);
    const niveauItems: Ref<string[]> = ref([]);

    const filterReset = (): any => {
        searchFilter.value = '';
        niveauFilter.value = [];
        jahrgangFilter.value = [];
    }
    ;
    const filtered = (): boolean => {
        return jahrgangFilter.value.length > 0
            || niveauFilter.value.length > 0
            || searchFilter.value !== '';
    };

    const mapFilterOptions = (column: keyof FachbezogeneFloskel): string[] => rows.value
        .map((item: FachbezogeneFloskel): string => item[column])
        .filter((value: string, index: number, self: string[]): boolean => self.indexOf(value) === index);

    const rowsFiltered = computed((): FachbezogeneFloskel[] =>
    rows.value
        .filter((floskel: FachbezogeneFloskel): boolean => {
            return (search(searchFilter, floskel.kuerzel) || search(searchFilter, floskel.text))
                && multiselect(niveauFilter, floskel.niveau)
                && multiselect(jahrgangFilter, floskel.jahrgang)
        })
        .map((floskel: FachbezogeneFloskel): FachbezogeneFloskel => ({
            ...floskel, text: floskel.text
        }))
    );

    // Button actions
    const add = (selectedRow: FachbezogeneFloskel): void => addSelectedToBemerkung(bemerkung, selectedRow);

    const close = (): void => closeEditor(isDirty, (): void => emit('close'));
//TODO: tsErrors: correct because helper calls types.ts while Component calls single interface file; hence the error
    const save = (): void => {
        bemerkung.value = formatBasedOnGender(bemerkung.value, props.leistung);
        axios
            .post(route('api.fachbezogene_bemerkung', props.leistung.id), { bemerkung: bemerkung.value })
            .then((): void => {
                storedBemerkung.value = bemerkung.value;
                isDirty.value = false;
                emit('updated', bemerkung.value);
            })
            .catch((error: AxiosError): void => {
                alert('Speichern nicht möglich!');
                console.log(error);
            });
    }

    const onKeyDown = (event: KeyboardEvent): void => pasteShortcut(event, bemerkung, rows);

</script>   

<style scoped>
    .buttons {
        @apply flex justify-end gap-3 mt-3 mb-3
    }

    .text-cell-button {
        @apply text-left
    }
</style>
