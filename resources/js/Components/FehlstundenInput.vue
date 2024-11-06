<template>
    <span style="padding: 0px 0.7em;" v-if="props.disabled">
        {{ model }}
    </span>

    <SvwsUiTextInput
        v-else
        v-model="model"
        :disabled="props.disabled"
        :ref="(el) => updateItemRefs(rowIndex, el as Element, itemRefsName)"
        @click="$event.target.select()"
        @keydown.up.stop.prevent="navigate('previous', props.rowIndex)"
        @keydown.down.stop.prevent="navigate('next', props.rowIndex)"
        @keydown.enter.stop.prevent="navigate('next', props.rowIndex)"
    ></SvwsUiTextInput>
</template>


<script setup lang="ts">
    import {watch, ref, Ref} from 'vue';
    import axios from 'axios';
    import { Leistung, Schueler } from '@/Interfaces/Interface';
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui';

    let props = defineProps<{
        model: Leistung | Schueler,
        column: 'fs'|'fsu'|'gfs'|'gfsu',
        disabled: boolean,
        rowIndex: number,
    }>();

    const emit = defineEmits(['navigated','updatedItemRefs'])

    const itemRefsName: string = 'itemRefs' + props.column;

    const navigated = ( direction: string, rowIndex: number, itemRefs: string) : void => emit("navigated", direction, rowIndex, itemRefsName)

    //corresponding itemRefs map in the parent gets a new pair every time the FehlstundenInput "itemRefsName" is uploaded
    const updateItemRefs = (rowIndex: number, el: Element, itemRefsName: string): void => {
        emit("updatedItemRefs", rowIndex, el, itemRefsName)
    }

    const navigate = (direction: string, rowIndex: number): void => {
        navigated(direction, rowIndex, itemRefsName);
    }
    
    const model: Ref<number|string> = ref(props.model[props.column]);

    let debounce: ReturnType<typeof setTimeout>;
    watch(model, (): void => {
        clearTimeout(debounce);
        debounce = setTimeout((): void => saveFehlstunden(), 500);
    });

    const saveFehlstunden = (): void => {
        if (isNaN(parseInt(model.value as string))) {
            model.value = '';
        }
        axios.post(route(`api.fehlstunden.${props.column}`, props.model), {value: model.value})
            .then((): number|string => props.model[props.column] = model.value)
            .catch((): number => model.value = props.model[props.column]);
    }
</script>


<style scoped>
/**/
</style>