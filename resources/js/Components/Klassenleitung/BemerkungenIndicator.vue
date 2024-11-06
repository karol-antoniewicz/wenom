<template>
    <button @click="open">
        <span class="icon" v-if="props.bemerkung">
           <span class="sr-only">Ist ausgefüllt</span>
           <i-ri-checkbox-line aria-hidden="true"></i-ri-checkbox-line>
        </span>

        <span class="icon" v-else>
           <span class="sr-only">Ist nicht ausgefüllt</span>
           <i-ri-checkbox-blank-line aria-hidden="true"></i-ri-checkbox-blank-line>
        </span>
    </button>
</template>


<script setup lang="ts">// TODO: TBR
    const emit = defineEmits(['open']);
    const open = (): void => emit('open');

    let props = defineProps({
        bemerkung: Boolean,
    });
</script>


<style scoped>
    .icon > svg {
        @apply w-6 h-6
    }
</style>
