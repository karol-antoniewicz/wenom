<template>
    <strong  :class="{ 'low-score' : !valid() }">
        <span style="padding: 0px 0.7em; font-weight: bold;" v-if="props.disabled">
            {{ note }}
        </span>
        <SvwsUiTextInput
            v-else
            v-model="note"
            :disabled="props.disabled"
            :valid="() => valid()"
            style="font-weight: bold;"
            :ref="(el) => updateItemRefs(rowIndex, el as Element, itemRefsNoteInputName)"
            @click="$event.target.select()"
            @keydown.up.stop.prevent="navigate('previous', props.rowIndex)"
            @keydown.down.stop.prevent="navigate('next', props.rowIndex)"
            @keydown.enter.stop.prevent="navigate('next', props.rowIndex)"
        ></SvwsUiTextInput>
    </strong>
</template>
 on

<script setup lang="ts">
    import { watch, ref, Ref } from 'vue';
    import { Leistung, Teilleistung } from '@/Interfaces/Interface';
    import { SvwsUiTextInput } from '@svws-nrw/svws-ui';
    import axios from 'axios';

    const props = defineProps<{
        leistung: Leistung|Teilleistung|string,
        disabled: boolean,
        rowIndex: number,
        column: 'quartalnote'|'note',
        teilleistung?: boolean;
        teilleistungId?: any;
    }>();

    const emit = defineEmits(['navigated','updatedItemRefs'])

    const itemRefsNoteInputName: string = props.teilleistung ? 'itemRefsTeilleistung' + props.teilleistungId : 'itemRefs' + props.column + 'Input';

    const navigated = ( direction: string, rowIndex: number, itemRefsNoteInputName: string) : void => emit("navigated", direction, rowIndex, itemRefsNoteInputName)

    //corresponding itemRefs map in the parent gets a new pair every time the NoteInput "itemRefsNoteInputName" is uploaded
    const updateItemRefs = (rowIndex: number, el: Element, itemRefsNoteInputName: string): void => {
        emit("updatedItemRefs", rowIndex, el, itemRefsNoteInputName)
    }

    const navigate = (direction: string, rowIndex: number): void => {
        navigated(direction, rowIndex, itemRefsNoteInputName);
    }

    const lowScoreArray: Array<string> = ['6', '5-', '5', '5+', '4-'];
    //type of note can be note or quartalnote
    const note: Ref<string | null> = props.column == "note"  ? ref(props.leistung.note) : ref(props.leistung.quartalnote)
    const noteType: Ref<string | null> = props.column == "note"  ? ref("note") : ref("note_quartal")

    let debounce: ReturnType<typeof setTimeout>;
    watch(note, (): void => {
        clearTimeout(debounce);
        debounce = setTimeout((): Promise<string | null> => saveNote(), 500);
    })

    //return type Promise<string | null>???
    const saveNote = () => {
        if (props.teilleistung) {
            axios.put(route('teilleistungen.update_note', { teilleistung: props.leistung, note: note.value }))
                .then((): string | null => props.leistung[props.column] = note.value)
                .catch((): string | null => {
                    if (props.leistung[props.column] === null) {
                        return note.value = ""
                    } else {
                        return note.value = props.leistung[props.column]
                    }
                })
        } else {
            axios.post(route('api.noten', { leistung: props.leistung, type: noteType.value }), { note: note.value })
                .then((): string | null => props.leistung[props.column] = note.value)
                .catch((): string | null => {
                    if (props.leistung[props.column] === null) {
                        return note.value = ""
                    } else {
                        return note.value = props.leistung[props.column]
                    }
                });
        }
    }

    const valid = (): boolean => !lowScoreArray.includes(note.value as string);
</script>


<style scoped>
    .low-score {
        @apply text-red-500
    }
    
</style>
