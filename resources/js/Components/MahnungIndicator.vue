<template>
    <svws-ui-modal :show="modal" size="small">
        <template #modalTitle>
            {{ props.leistung.vorname }} {{ props.leistung.nachname }}
            <SvwsUiBadge variant="primary">
                {{ props.leistung.klasse ?? props.leistung.kurs }}
            </SvwsUiBadge>
        </template>

        <template #modalDescription>
            <strong>Mahndatum:</strong> {{ mahndatumFormatted() }}
        </template>

        <template #modalActions>
            <svws-ui-button type="secondary" @click="close">
                Schliessen
            </svws-ui-button>
        </template>
    </svws-ui-modal>

    <span>
        <span v-if="props.leistung.mahndatum" aria-description="Ist gemahnt mit Mahndatum">
            <SvwsUiCheckbox v-model="gemahnt" @click="checkboxActions($event)" color="success" readonly
            :ref="(el) => updateItemRefs(rowIndex, el as Element, mahnungIndicator)"
            @keydown.up.stop.prevent="navigate('previous', props.rowIndex)"
            @keydown.down.stop.prevent="navigate('next', props.rowIndex)"
            @keydown.enter.stop.prevent="navigate('next', props.rowIndex)">
            </SvwsUiCheckbox>
        </span>
        <span v-else-if="leistung.istGemahnt" aria-description="Ist gemahnt ohne Mahndatum">
            <SvwsUiCheckbox v-model="gemahnt" @click="checkboxActions($event);" color="error" readonly
            :ref="(el) => updateItemRefs(rowIndex, el as Element, mahnungIndicator)"
            @keydown.up.stop.prevent="navigate('previous', props.rowIndex)"
            @keydown.down.stop.prevent="navigate('next', props.rowIndex)"
            @keydown.enter.stop.prevent="navigate('next', props.rowIndex)">
            </SvwsUiCheckbox>
        </span>
        <span v-else aria-description="Ist nicht gemahnt">
            <SvwsUiCheckbox v-model="notGemahnt" @click="checkboxActions($event);" readonly
            :ref="(el) => updateItemRefs(rowIndex, el as Element, mahnungIndicator)"
            @keydown.up.stop.prevent="navigate('previous', props.rowIndex)"
            @keydown.down.stop.prevent="navigate('next', props.rowIndex)"
            @keydown.enter.stop.prevent="navigate('next', props.rowIndex)">
            </SvwsUiCheckbox>
        </span>
    </span>
</template>


<script setup lang="ts">
    import { Ref, ref } from 'vue';
    import axios from 'axios';
    import moment from 'moment';
    import { Leistung } from '@/Interfaces/Interface';
    import { SvwsUiBadge, SvwsUiButton, SvwsUiModal, SvwsUiCheckbox } from '@svws-nrw/svws-ui';

    const props = defineProps<{
        leistung: Leistung,
        disabled: boolean,
        rowIndex: number,
    }>();

    //TODO: unify names if only one function is applied in the end
    const emit = defineEmits(['navigated','updatedItemRefs'])

    const mahnungIndicator: string = 'mahnungIndicator';

    const navigated = ( direction: string, rowIndex: number, mahnungIndicator: string) : void => emit("navigated", direction, rowIndex, mahnungIndicator)

    //corresponding item map in the parent gets a new pair every time MahungIndicator is uploaded
    const updateItemRefs = (rowIndex: number, el: Element, mahnungIndicator: string): void => {
        //console.log(mahnungIndicator);
        emit("updatedItemRefs", rowIndex, el, mahnungIndicator)
    }

    const navigate = (direction: string, rowIndex: number): void => {
        navigated(direction, rowIndex, mahnungIndicator);
    }

    const modalVisible: Ref<boolean> = ref(false);
    const modal = (): Ref<boolean> => modalVisible;
    const open = () => {
        modal().value = true;
    }
    const close = () => modal().value = false;

    const leistung: Ref<Leistung> = ref(props.leistung);

    const toggleMahnung = (): void => {
        leistung.value.istGemahnt = !leistung.value.istGemahnt;
        axios.post(route('api.mahnung', props.leistung.id), props.leistung)
            .catch((): boolean => leistung.value.istGemahnt = !leistung.value.istGemahnt);
    }

    const mahndatumFormatted = (): string => moment(new Date(props.leistung.mahndatum)).format('DD.MM.YYYY');

    //checkbox statuses
    const gemahnt: boolean = true;
    const notGemahnt: boolean = false;

    const checkboxActions = (event: Event): void => {
        if (props.disabled) {
            if (event) {
                console.log("readonly")
                event.preventDefault();
            }
        } else if (props.leistung.mahndatum) {
            event.preventDefault();
            open();
        } else {
            toggleMahnung();
        }
    }

</script>


<style scoped>
    .red {
        @apply text-red-500
    }

    .green {
        @apply text-green-500
    }
    
</style>
