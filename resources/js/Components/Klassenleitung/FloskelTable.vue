<template>
    <div class="flex flex-col gap-6">
        <h2 class="headline-4">Floskeln</h2>
        <hr class="border-gray">
        <div class="pb-0">
            <SvwsUiTextInput type="search" v-model="state.search" placeholder="Suche"></SvwsUiTextInput>
        </div>

        <SvwsUiNewTable :data="computedFloskeln" :columns="columns" selectionMode="multiple" :footer="true" v-on:update:modelValue="select">
            <template #footer>
                <SvwsUiButton @click="add" :type="type">Zuweisen</SvwsUiButton>
            </template>
        </SvwsUiNewTable>
    </div>
</template>


<script setup lang="ts">// TODO: TBR
    import {computed, reactive} from 'vue';

    import { Floskel, Floskelgruppe, Column } from '@/Interfaces/Interface';

    const emit = defineEmits(['added']);

    type selected = {
        text: string,
        selected: boolean,
    };

    let props = defineProps({
        floskelgruppe: String,
        floskelgruppen: Array,
    });

    const state = reactive({
        selected: <selected[]> [],
        search: <string> '',
        floskelgruppen: <Floskelgruppe[]> props.floskelgruppen as Array<any>,
        floskelgruppe: <string> props.floskelgruppe,
    });

    const columns = <Column[]>[
        { key: 'id', label: 'id', sortable: true },
        { key: 'kuerzel', label: 'Kürzel', sortable: true },
        { key: 'text', label: 'Text', sortable: true },
    ];

    const computedFloskeln = computed((): void => state.floskelgruppen
         .find((floskelgruppe: Floskelgruppe): boolean => floskelgruppe.kuerzel == state.floskelgruppe)
         .floskeln
         .filter((floskel: Floskel): boolean => searchFilter(floskel))
    );

    const searchFilter = (floskel: Floskel): boolean => {
        if (state.search == '') {
            return true;
        }
        return floskel.text.toLowerCase().includes(state.search.toLowerCase());
    }

    const select = (floskeln: Array<selected>): Array<selected> => state.selected = floskeln;

    const add = (): void => {
        let bemerkung: string = state.selected.map((selected: selected): string => selected.text).join(' ');
        emit('added', bemerkung);
    }

    const type = computed((): string => state.selected.length > 0 ? 'primary' : 'secondary');
</script>


<style scoped>
/**/
</style>
