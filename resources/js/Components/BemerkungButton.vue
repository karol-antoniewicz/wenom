<template>
    <button
        type="button"
        @click="emit('clicked')"
        :aria-label="bemerkungButtonAriaLabel()"
    >
        {{ value }}   
    </button>
</template>


<script setup lang="ts">
    import { Leistung, Schueler, Teilleistung } from '@/Interfaces/Interface';
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe';

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const emit = defineEmits<EmitsOptions>();

    const props = defineProps<{
        model: Leistung|Schueler|Teilleistung,
        floskelgruppe: any,
        value: any,
    }>();

    const bemerkungButtonAriaLabel = (): string => {
        return `Wechseln zu ${floskelgruppen[props.floskelgruppe]} für ${props.model.vorname} ${props.model.nachname}`;
    }
</script>


<style scoped>
/**/
</style>