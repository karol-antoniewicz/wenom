<template>
    <div>
        <div class="relative flex flex-col gap-6 w-full h-screen">
            <TopMenu>
                <SvwsUiCheckbox v-model="teilleistungen">Teilleistungen</SvwsUiCheckbox>
                <SvwsUiCheckbox v-model="klassenleitung">Klassenleitung</SvwsUiCheckbox>
            </TopMenu>
            <div id="filter-menu">
                <SvwsUiTextInput type="search" v-model="state.filters.search" placeholder="Suche"></SvwsUiTextInput>
<!--                <SvwsUiSelectInput placeholder="Klasse" v-model="state.klasse" @update:value="(klasse: Number) => state.filters.klasse = klasse" :options="state.filterValues.klassen"></SvwsUiSelectInput>-->
<!--                <SvwsUiSelectInput placeholder="Jahrgang" v-model="state.jahrgang" @update:value="(jahrgang: Number) => state.filters.jahrgang = jahrgang" :options="state.filterValues.jahrgaenge"></SvwsUiSelectInput>-->
<!--                <SvwsUiSelectInput placeholder="Kurs" v-model="state.kurs" @update:value="(kurs: Number) => state.filters.kurs = kurs" :options="state.filterValues.kurse"></SvwsUiSelectInput>-->
<!--                <SvwsUiSelectInput placeholder="Note" v-model="state.note" @update:value="(note: Number) => state.filters.note = note" :options="state.filterValues.noten"></SvwsUiSelectInput>-->
                <SvwsUiButton type="secondary">
                    <i-ri-filter-3-line aria-hidden="true"></i-ri-filter-3-line>
                    Erweiterte Filter
                </SvwsUiButton>
            </div>

            <div id="table" v-if="filteredLeistungen.length > 0">
                <SvwsUiNewTable :data="filteredLeistungen" :columns="columns">
                    <template #cell-mahnung="{ row }">
                        <MahnungIndicator :leistung="row" :key="row.id" @updated="updateLeistungMahnung"></MahnungIndicator>
                    </template>
                </SvwsUiNewTable>
            </div>
        </div>
    </div>
</template>


<script setup lang="ts">
    // TODO: TBR
    // TODO: Check if this file is still being used (it seems it isn't)

    import { ref, computed, reactive, onMounted } from 'vue';
    import { useStore } from '../store'
    import axios from 'axios';
    import MahnungIndicator from "./MahnungIndicator.vue";
    import TopMenu from "../Components/TopMenu.vue";
    import { Leistung, Column } from '../Interfaces/Interface'

    type filterElementType = Array<{ id: string, label: string }>
    type filterValuesType = {
        jahrgaenge: filterElementType,
        noten: filterElementType,
        klassen: filterElementType,
        kurse: filterElementType,
    };

    const store = useStore();
    let teilleistungen = ref(true);
    let klassenleitung = ref(true);

    let state = reactive({
        leistungen: <Leistung[]> [],
        teilleistungen: <boolean>true,
        klassenleitung: <boolean>true,
        filterValues: <filterValuesType> {
            'jahrgaenge': [],
            'klassen': [],
            'kurse': [],
            'noten': [],
        },
        filters: {
            search: <string> '',
            klasse: <Number | string> '',
            jahrgang: <Number | string> '',
            kurs: <Number | string> '',
            note: <Number | string> '0',
        },
    });

    const columns = <Column[]> [
        {key: 'klasse', label: 'Klasse', sortable: true },
        {key: 'name', label: 'Name', sortable: true },
        {key: 'fach', label: 'Fach', sortable: true},
        {key: 'lehrer', label: 'Lehrer'},
        {key: 'kurs', label: 'Kurs', sortable: true},
        {key: 'note', label: 'Note', sortable: true},
        {key: 'mahnung', label: 'M', sortable: false},
        {key: 'fs', label: 'FS'},
        {key: 'ufs', label: 'uFS'},
    ];

    onMounted(() => {
        axios.get(route('get_filters')).then(response => state.filterValues = response.data);
        axios.get(route('get_leistungen')).then(response => state.leistungen = response.data);
    })

    const filteredLeistungen = computed((): Leistung[] => {
        return state.leistungen.map((leistung: Leistung): Leistung => {
            leistung.name = [leistung.nachname, leistung.vorname].join(' ')
            return leistung
        })
        .filter((leistung: Leistung): boolean => {
            return searchFilter(leistung)
                && tableFilter(leistung, 'klasse', true)
                && tableFilter(leistung, 'kurs')
                && tableFilter(leistung, 'jahrgang')
                && tableFilter(leistung, 'note')
        });
    });

    const searchFilter = (leistung: Leistung): boolean => {
        if (state.filters.search === '') return true;
        const search = (search: string) => search.toLowerCase().includes(state.filters.search.toLowerCase());
        return search(leistung.vorname) || search(leistung.nachname);
    };

    const tableFilter = (leistung: Leistung, column: string, withOnlyEmptyOption: boolean = false): boolean => {
        if (withOnlyEmptyOption && state.filters[column] == '') return leistung[column] == null;
        if (state.filters[column] == 0) return true;
        return leistung[column] == state.filters[column];
    };

    const updateLeistungMahnung = (leistung: Leistung, istGemahnt: boolean, mahndatum: string): void => {
        let current = state.leistungen.find((current: Leistung) => current.id === leistung.id);
        current.istGemahnt = istGemahnt;
        current.mahndatum = Boolean(mahndatum);
    };

    const updateLeistungNote = (leistung: Leistung, note: string) =>
        state.leistungen.find((current: Leistung) => current.id === leistung.id)['note'] = note;
</script>


<style scoped>
/**/
</style>