<template>
    <div :class="{ red: leistung.istGemahnt, green: leistung.mahndatum }">
        <button
            @click="modal.openModal()"
            v-if="leistung.mahndatum"
            @keydown.up.stop.prevent="navigate('up')"
            @keydown.down.stop.prevent="navigate('down')"
            @keydown.enter.stop.prevent="navigate('down')"
            @keydown.left.stop.prevent="navigate('left')"
            @keydown.right.stop.prevent="navigate('right')"
            @keydown.tab.stop.prevent="navigate('right')"
            :ref="(el: CellRef): CellRef => {element = el; setCellRefs(element, props.rowIndex); return el}"
        >
            <span class="icon">
               <ri-checkbox-line aria-hidden="true" aria-description="Ist gemahnt mit Mahndatum"></ri-checkbox-line>
            </span>
        </button>
        <div v-else>
            <span class="icon">
                <ri-checkbox-line v-if="leistung.istGemahnt" aria-hidden="true" aria-description="Ist gemahnt"></ri-checkbox-line>
                <ri-checkbox-blank-line v-else aria-hidden="true" aria-description="Ist nicht gemahnt"></ri-checkbox-blank-line>
            </span>
        </div>
    </div>

    <!-- TODO: check if can be ignored/deleted because SvwsUiModal is supposed to be deprecated  -->
    <SvwsUiModal ref="modal">
        <template #modalTitle>
            {{ leistung.vorname }} {{ leistung.nachname }}
            <SvwsUiBadge variant="primary">
                {{ leistung.klasse ?? leistung.kurs }}
            </SvwsUiBadge>
        </template>

        <template #modalContent>
            <strong>Mahndatum:</strong> {{ mahndatumFormatted() }}
        </template>

        <template #modalActions>
            <SvwsUiButton @click="modal.closeModal()" type="secondary">Schließen</SvwsUiButton>
        </template>
    </SvwsUiModal>
</template>


<script setup lang="ts">
    import { reactive, ref } from 'vue';
    import { Leistung } from '@/Interfaces/Interface';
    import { CellRef, setCellRefs, navigateTable } from '@/Helpers/tableNavigationHelper';
    import { SvwsUiBadge, SvwsUiButton, SvwsUiModal } from '@svws-nrw/svws-ui';
    import moment from 'moment';

    let props = defineProps<{
        leistung: Leistung
        rowIndex: number,
    }>();

    const modal = ref<any>(null);

    let element: CellRef = undefined;
    let leistung = reactive<Leistung>(props.leistung);

    const mahndatumFormatted = (): string => moment(new Date(leistung.mahndatum)).format('DD.MM.YYYY');
    const navigate = (direction: string): Promise<void> => navigateTable(direction, props.rowIndex, element);
</script>


<style scoped>
    .red {
        @apply text-red-500
    }

    .green {
        @apply text-green-500
    }

</style>
