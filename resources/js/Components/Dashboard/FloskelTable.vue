<template>
    <div class="flex flex-col gap-6">
    <h2 class="headline-4">Floskeln</h2>
    <hr class="border-gray">
    <div class="pb-0">
        <div class="grid grid-cols-3 gap-3">
            <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
            <!-- <SvwsUiSelectInput placeholder="Niveau" v-model="filters.niveau" @update:value="(niveau: Number) => filters.niveau = niveau" :options="state.filterValues.niveau"></SvwsUiSelectInput> -->
            <!-- <SvwsUiSelectInput placeholder="Jahrgang" v-model="filters.jahrgang_id" @update:value="(jahrgang: Number) => filters.jahrgang_id = jahrgang" :options="state.filterValues.jahrgaenge"></SvwsUiSelectInput> -->
        </div>
    </div>

    <SvwsUiNewTable :data="computedFloskeln" :columns="columns" selectionMode="multiple" :footer="true" v-on:update:modelValue="select">
        <template #footer>
            <SvwsUiButton @click="add" :type="type">Zuweisen</SvwsUiButton>
        </template>
    </SvwsUiNewTable>
    </div>
</template>


<script setup lang="ts">
// TODO: TBR
// TODO: Check if this file is still being used (it seems it isn't)
    import { computed, onMounted, reactive } from 'vue';
    import axios, {AxiosPromise, AxiosResponse} from 'axios';

    const emit = defineEmits(['added']);

    import { Column, Floskel } from '@/Interfaces/Interface';

    type Selected = {
        text: string,
        selected: boolean,
    };

    type Filter = {
        [index: string]: any,
        niveau: Number|string,
        jahrgang_id: Number|string,
    };

    let props = defineProps({floskeln: Array});

    const state = reactive({
        selected: <Selected[]> [],
        search: <string> '',
        floskeln: <Floskel[]> props.floskeln as Array<any>,
        filterValues: {
            'niveau': [],
            'jahrgaenge': [],
        },
    });

    const columns = <Column[]>[
        { key: 'kuerzel', label: 'Kürzel', sortable: true },
        { key: 'fach_id', label: 'Fach', sortable: true },
        { key: 'jahrgang_id', label: 'Jahrgang', sortable: true },
        { key: 'text', label: 'Text', sortable: true },
        { key: 'niveau', label: 'Niveau', sortable: true },
    ]

    onMounted((): AxiosPromise => axios
        .get(route('get_fachbezogene_floskeln_filters'))
        .then((response: AxiosResponse): AxiosResponse => state.filterValues = response.data)
    );

    const computedFloskeln = computed(() => {
        if (props.floskeln != undefined)
            props.floskeln.filter((floskel: Floskel): boolean => {
                return searchFilter(floskel)
                    && tableFilter(floskel, 'niveau')
                    && tableFilter(floskel, 'jahrgang_id')
            })
    });

    const tableFilter = (floskel: Floskel, column: string, withOnlyEmptyOption: boolean = false): boolean => {
        if (withOnlyEmptyOption && [null, ''].includes(filters[column])) {
            return floskel[column as keyof Floskel] == null;
        }
        if (filters[column as keyof Filter] == 0) {
            return true;
        }
        return floskel[column as keyof Floskel] == filters[column as keyof Filter];
    };

    const searchFilter = (floskel: Floskel): boolean => {
        if (state.search == '') {
            return true;
        }
        return floskel.text.toLowerCase().includes(state.search.toLowerCase());
    };

    const select = (floskeln: Array<Selected>): Array<Selected> => state.selected = floskeln;

    const add = (): void => emit(
        'added',
        state.selected.map((selected: Selected): string => selected.text).join(' ')
    );

    const type = computed((): string => state.selected.length > 0 ? 'primary' : 'secondary');

    const filters: Filter = reactive({
        niveau: <Number | string> 0,
        jahrgang_id: <Number | string> 0,
    });
</script>


<style scoped>
/**/
</style>
