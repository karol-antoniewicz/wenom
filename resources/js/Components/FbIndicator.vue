<template>
    <button type="button" @click="emit('clicked')" :aria-label="bemerkungButtonAriaLabel">
        <span>
            <ri-checkbox-line v-if="props.bemerkung"></ri-checkbox-line>
            <ri-checkbox-blank-line v-else></ri-checkbox-blank-line>
        </span>

        <span class="bemerkung">
        <!-- TODO: correct known error message; see FbEditor, line 69 -->
            {{ formatBasedOnGender(props.bemerkung, props.model) }}
        </span>
    </button>
</template>


<script setup lang="ts">
    import { Schueler, Leistung } from '@/Interfaces/Interface';
    import { formatBasedOnGender } from '@/Helpers/bemerkungen.helper';

    interface EmitsOptions {
        (event: 'clicked'): void,
    }

    const props = defineProps<{
        model: Leistung,
        bemerkung: string | null,
    }>();

    const bemerkungButtonAriaLabel = (schueler: Schueler): string => {
        return `Fachbezogene Bemerkungen für ${schueler.vorname} ${schueler.nachname} öffnen`;
    };

    const emit = defineEmits<EmitsOptions>();
</script>


<style scoped>

    button {
        @apply max-w-full flex gap-1.5 items-center justify-start
    }

    .bemerkung {
        @apply truncate
    }
    
</style>
