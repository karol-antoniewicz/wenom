<template>
    <!-- Seitentitel -->
    <Head>
        <title>Mein Unterricht</title>
    </Head>

    <AppLayout>
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content-area">
                <!-- Tabelle mit SvwsUiTable -->
                <SvwsUiTable :items="rowsFiltered" :columns="cols" :toggle-columns="true" clickable count noDataText="" :sortByAndOrder= "{ key: 'klasse', order: true}"
                :filtered="isFiltered()" :filterReset="filterReset" :hiddenColumns="hiddenColumns" :filterOpen="false" :allowArrowKeySelection="true">

                    <template #filter>
                        <div class="filter-area-icon">
                            <SvwsUiButton @click="exportToFile()" type="transparent" size="big"
                                :class="'hover:opacity-100 focus-visible:opacity-100 export-button'">
                                <ri-download-2-line></ri-download-2-line>csv
                            </SvwsUiButton>
                        </div>
                    </template>

                    <!-- Erweiterte Filteroptionen -->
                    <template #filterAdvanced>
                        <SvwsUiTextInput type="search" placeholder="Suche" v-model="searchFilter" />
                        <SvwsUiMultiSelect label="Klasse" :items="klasseItems" :item-text="item => item" v-model="klasseFilter" />
                        <SvwsUiMultiSelect label="Jahrgang" :items="jahrgangItems" :item-text="item => item" v-model="jahrgangFilter" />
                        <SvwsUiMultiSelect label="Fach" :items="fachItems" :item-text="item => item" v-model="fachFilter" />
                        <SvwsUiMultiSelect label="Kurs" :items="kursItems" :item-text="item => item" v-model="kursFilter" />
                        <SvwsUiMultiSelect label="Note" :items="noteItems" :item-text="item => item" v-model="noteFilter" />
                    </template>

                    <!-- Individuelle Zellen-Template -->
                    <!-- BemerkungButton in der Zelle 'klasse' -->
                    <template #cell(klasse)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'name' -->
                    <template #cell(name)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'fach' -->
                    <template #cell(fach)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <!-- BemerkungButton in der Zelle 'kurs' -->
                    <template #cell(kurs)="{ value, rowData }">
                        <BemerkungButton :value="value" :model="rowData" floskelgruppe="fb" @clicked="selectLeistung(rowData)" />
                    </template>

                    <template #cell(quartalnoten)="{ value, rowData, rowIndex }">
                        <NoteInput column="quartalnote" :leistung="rowData" :disabled="!rowData.editable.noten" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        ></NoteInput>
                    </template>

                    <template #cell(note)="{ value, rowData, rowIndex }">
                        <NoteInput column="note" :leistung="rowData" :disabled="!rowData.editable.noten" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        ></NoteInput>
                    </template>

                    <template #cell(istGemahnt)="{ value, rowData, rowIndex }">
                        <MahnungIndicator :leistung="rowData" :disabled="!rowData.editable.mahnungen" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs" />
                    </template>

                    <template #cell(fs)="{ value, rowData, rowIndex }">
                        <FehlstundenInput column="fs" :model="rowData" :disabled="!rowData.editable.fehlstunden" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        />
                    </template>

                    <template #cell(fsu)="{ value, rowData,  rowIndex}">
                        <FehlstundenInput column="fsu" :model="rowData" :disabled="!rowData.editable.fehlstunden" :row-index="rowIndex" @navigated="navigateTable" @updatedItemRefs="updateItemRefs"
                        />
                    </template>

                    <template #cell(fachbezogeneBemerkungen)="{ value, rowData }">
                        <BemerkungIndicator :model="rowData" :bemerkung="rowData['fachbezogeneBemerkungen']"
                            @clicked="selectLeistung(rowData, true)" floskelgruppe="fb" />
                    </template>
                </SvwsUiTable>
            </div>
        </template>

        <!-- Anzeige des ausgewählten Elements -->
        <template v-slot:aside v-if="selectedLeistung">
            <FbEditor :leistung="selectedLeistung" @updated="selectedLeistung.fachbezogeneBemerkungen = $event;"
                @close="selectedLeistung = null" editable />
        </template>
    </AppLayout>
</template>

<script setup lang="ts">
    import { computed, onMounted, Ref, ref, toRaw} from 'vue';
    import axios, { AxiosResponse } from 'axios';
    import { Head } from '@inertiajs/inertia-vue3';
    import { Leistung, TableColumnToggle } from '@/Interfaces/Interface';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import { mapFilterOptionsHelper, multiSelectHelper, searchHelper } from '@/Helpers/tableHelper';
    import { SvwsUiHeader, DataTableColumn, SvwsUiTable, SvwsUiTextInput, SvwsUiMultiSelect, SvwsUiButton, } from '@svws-nrw/svws-ui';
    import { BemerkungIndicator, MahnungIndicator, NoteInput, FehlstundenInput, FbEditor, BemerkungButton, } from '@/Components/Components';
    import { exportDataToCSV } from '@/Helpers/exportHelper';

    //Correlation filter names and column names on this page
    interface MeinUnterrichtFiltersToCols {
        [index: string]: string,
        fach: string,
        kurs: string,
        teilleistungen: string,
        quartalnoten: string,
        note: string,
        mahnungen: string,
        fehlstunden: string,
        bemerkungen: string,
    };

    const title = 'Notenmanager - mein Unterricht';

    //rows will receive a reference map which will allow navigation within the three input columns of MeinUnterricht
    const itemRefsNoteInput = ref(new Map());
    const itemRefsQuartalNoteInput = ref(new Map());
    const itemRefsfs = ref(new Map());
    const itemRefsfsu = ref(new Map());
    //testing here for ticket 341
    const mahnungIndicator = ref(new Map());

    // Data received from DB
    const rows: Ref<Leistung[]> = ref([]);

    // The different filters on top of the screen may get input and thus the data from DB will be filtered and then displayed
    const rowsFiltered = computed((): Leistung[] => {
        return rows.value.filter((leistung: Leistung): boolean => {
            return searchHelper(leistung, ['name'], searchFilter.value || '')
                && multiSelectHelper(leistung, 'klasse', klasseFilter.value)
                && multiSelectHelper(leistung, 'fach', fachFilter.value)
                && multiSelectHelper(leistung, 'kurs', kursFilter.value)
                && multiSelectHelper(leistung, 'jahrgang', jahrgangFilter.value)
                && multiSelectHelper(leistung, 'note', noteFilter.value)
        })
    });

    // some columns may be displayed/hidden on demand
    const toggles: Ref<TableColumnToggle> = ref({
        teilleistungen: false,
        quartalnoten: false,
        mahnungen: false,
        bemerkungen: false,
        fehlstunden: false,
        fachlehrer: false,
        kurs: false,
        note: false,
        fach: false,
    });

    // all notes present in the noten DB table
    const allNotes: Ref<string[]> = ref([]);

    // Api Call - Daten für meinUnterricht
    onMounted((): Promise<void> => axios
        .get(route('api.mein_unterricht'))
        .then((response: AxiosResponse): void => {
            rows.value = response.data.data;
            toggles.value = response.data.toggles;
            allNotes.value = response.data.allNotes;
            getHiddenColumns(toggles);
        })
        .finally((): void => mapFilters())
    );

    // Standard-Spalten für die Tabelle
    const default_cols : DataTableColumn[] = [
        { key: 'klasse', label: 'Klasse', sortable: true, span: 1, fixedWidth: 6, disabled: false, toggleInvisible:true },
        { key: 'name', label: 'Name, Vorname', sortable: true, span: 3, minWidth: 16, disabled: false, toggleInvisible:true },
    ];

     // Ein- und Ausblendbare Spalten
    const cols = computed((): DataTableColumn[] => {
        const result = [...default_cols];
            result.push({ key: 'fach', label: 'Fach', sortable: true, span: 1, minWidth: 5, disabled: false, toggle: true });
            result.push({ key: 'kurs', label: 'Kurs', sortable: true, span: 2, minWidth: 5, disabled: false, toggle: true });
            result.push({ key: 'teilnoten', label: 'Teilnoten', sortable: true, span: 5, minWidth: 15, toggle: true });
            result.push({ key: 'quartalnoten', label: 'Quartal', sortable: true, span: 1, minWidth: 6, toggle: true });
            result.push({ key: 'note', label: 'Note', sortable: true, span: 1, minWidth: 5, toggle: true });
            result.push({ key: 'istGemahnt', label: 'Mahnungen', sortable: true, span: 1, minWidth: 8, toggle: true });
            result.push({ key: 'fs', label: 'FS', sortable: true, span: 1, minWidth: 6,  tooltip: "Fachbezogene Fehlstunden", toggle: true });
            result.push({ key: 'fsu', label: 'FSU', sortable: true, span: 1, minWidth: 6,  tooltip: "Unentschuldigte fachbezogene Fehlstunden", toggle: true });
            result.push({ key: 'fachbezogeneBemerkungen', label: 'FB', sortable: true, span: 12, minWidth: 4,  tooltip: "Fachbezogene Bemerkungen", toggle: true });
        return result;
    });
    
    //filters from settings or user settings determine whether columns are hidden or shown in the table
    const hiddenColumns = ref<Set<string>>(new Set<string>());
    //filter names from DB do not match our cols; TODO: check whether it may be corrected at some point
    const filtersToCols: MeinUnterrichtFiltersToCols = {
        fach: 'fach',
        kurs: 'kurs',
        teilleistungen: 'teilnoten',
        quartalnoten: 'quartalnoten',
        note: 'note',
        mahnungen: 'istGemahnt',
        fehlstunden: 'fs',
        bemerkungen: 'fachbezogeneBemerkungen',
    };

    const getHiddenColumns = (toggles: Ref<TableColumnToggle>) => {
        for (const filter in toggles.value) {
            if (toggles.value[filter] === false) {
                hiddenColumns.value.add(filtersToCols[filter]);
            }
        }
    }

    // when clicked on, Leistung is "selected"
    const selectedLeistung: Ref<Leistung | null> = ref(null);

    const selectLeistung = (leistung: Leistung, always: boolean = false): Leistung | null =>
        selectedLeistung.value = (selectedLeistung.value || always) ? leistung : null;

    // Filter
    const searchFilter: Ref<string|null> = ref(null);
    const klasseFilter: Ref <string[]> = ref([]);
    const fachFilter: Ref <string[]> = ref([]);
    const kursFilter: Ref <string[]> = ref([]);
    const jahrgangFilter: Ref <string[]> = ref([]);
    const noteFilter: Ref <string[]> = ref([]);

    const klasseItems: Ref<string[]> = ref([]);
    const fachItems: Ref<string[]> = ref([]);
    const kursItems: Ref<string[]> = ref([]);
    const jahrgangItems: Ref<string[]> = ref([]);
    const noteItems: Ref<string[]> = ref([]);

    // Filter zurücksetzen
    const filterReset = (): void => {
        searchFilter.value = '';
        klasseFilter.value = [];
        jahrgangFilter.value = [];
        fachFilter.value = [];
        kursFilter.value = [];
        noteFilter.value = [];
    }

    // Prüfen, ob Filter aktiv sind
    const isFiltered = (): boolean => {
        return searchFilter.value !== null
            || klasseFilter.value.length > 0
            || jahrgangFilter.value.length > 0
            || fachFilter.value.length > 0
            || kursFilter.value.length > 0
            || noteFilter.value.length > 0;
    }

    // Filteroptionen mappen
    const mapFilters = (): void => {
        klasseItems.value = mapFilterOptionsHelper(rows.value, 'klasse');
        fachItems.value = mapFilterOptionsHelper(rows.value, 'fach');
        kursItems.value = mapFilterOptionsHelper(rows.value, 'kurs');
        jahrgangItems.value = mapFilterOptionsHelper(rows.value, 'jahrgang');
        noteItems.value = Array.from(Object.values(allNotes.value));
    };

    //TODO: find out why Mahnung is not being updated like the others
    //TODO: adjust name if necessary; if so: make into helper under Helpers
    //input html element and reference map name are determined by child
    function updateItemRefs(rowIndex: number, el: Element, itemRefsName: string): void {
        //console.log(itemRefsName);
        switch (itemRefsName) {
            case "itemRefsquartalnoteInput":
                itemRefsQuartalNoteInput.value.set(rowIndex, el);
                break;
            case "itemRefsnoteInput":
                itemRefsNoteInput.value.set(rowIndex, el);
                break;
            case "itemRefsfs":
                itemRefsfs.value.set(rowIndex, el);
                break;
            case "itemRefsfsu":
                itemRefsfsu.value.set(rowIndex, el);
                break;
            case "mahnungIndicator":
                mahnungIndicator.value.set(rowIndex, el);
                break;
            default:
                console.log("Map not found: " + itemRefsName)
        }
	}

    //table navigation actions (go up/down within the column)
	function next(id: number, item: Ref) {
		const el = item.value.get(id + 1);
        console.log(el.$nextTick);
		el.input ? el.input.select() : console.log("no input");
	}

	const previous = (id: number, item: Ref) => {
        const el = item.value.get(id - 1);
		if (el)
        el.input.select();
	}

    //direction (up/down within the column) and map name are received from child component
    const navigateTable = (direction: string, rowIndex: number, itemRefsName: string): void => {
        switch (itemRefsName) {
            case "itemRefsquartalnoteInput":
                direction === "next" ? next(rowIndex, itemRefsQuartalNoteInput) : previous(rowIndex, itemRefsQuartalNoteInput);
                break;
            case "itemRefsnoteInput":
                direction === "next" ? next(rowIndex, itemRefsNoteInput) : previous(rowIndex, itemRefsNoteInput);
                break;
            case "itemRefsfs":
                direction === "next" ? next(rowIndex, itemRefsfs) : previous(rowIndex, itemRefsfs);
                break;
            case "itemRefsfsu":
                direction === "next" ? next(rowIndex, itemRefsfsu) : previous(rowIndex, itemRefsfsu);
                break;
            //testing here for ticket 341
            case "mahnungIndicator":
                direction === "next" ? next(rowIndex, mahnungIndicator) : previous(rowIndex, mahnungIndicator);
                break;
            default:
                console.log("itemRefs map not found");
        }	
	}    

    const exportToFile = (): void => {
        exportDataToCSV(cols.value, hiddenColumns.value, rowsFiltered.value, 'meinUnterricht');
    };
</script>


<style scoped>

    /*.truncate {
        @apply truncate
    } */

    header {
        @apply flex flex-col gap-4 p-6
    }

    header #headline {
        @apply flex items-center justify-start gap-6 ml-6
    }

    .content-area {
        @apply mx-4 overflow-auto ml-6
    }

    .myToggles {
        @apply m-4
    }

    .filter-area-icon {
        @apply -m-1.5
    }

</style>

