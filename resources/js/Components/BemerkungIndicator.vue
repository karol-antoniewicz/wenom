<template>
    <button type="button" @click="emit('clicked')" :aria-label="bemerkungButtonAriaLabel">
            <ri-more-2-line id="more-icon"></ri-more-2-line>
        <span class="bemerkung">
            {{ formatBasedOnGender(props.bemerkung, props.model) }}
        </span>
    </button>
</template>


<script setup lang="ts">
    import { Schueler, Leistung } from '@/Interfaces/Interface';
    import { floskelgruppen } from '@/Interfaces/Floskelgruppe';
    import { formatBasedOnGender } from '@/Helpers/bemerkungen.helper';
    import { computed, Ref, ref } from 'vue';
    

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Schueler | Leistung,
        bemerkung: string | null,
        floskelgruppe: 'asv' | 'aue' | 'zb' | 'fb',
    }>();
    
    const bemerkungCheckboxStatus = computed(() => (props.bemerkung === null || props.bemerkung === "" )  ? false : true);

    //testing here for ticket 338
    const bemerkungTabindex = computed(() => {
        return (props.bemerkung === null || props.bemerkung === "" ) ? -1 : 0
    });

    const bemerkungButtonAriaLabel = (schueler: Schueler): string => { 
        return `${floskelgruppen[props.floskelgruppe]} für ${schueler.vorname} ${schueler.nachname} öffnen`;
    }

    const emit = defineEmits<EmitsOptions>();
</script>


<style scoped>

    button {
        @apply max-w-full flex gap-1.5 items-center justify-start
    }

    .bemerkung {
        @apply truncate w-screen
    }

    #more-icon {
        @apply min-w-5 -ml-1 -mr-2
    }
    
</style>
