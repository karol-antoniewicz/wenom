<template>
    <div 
        class="toast" 
        :class="{ 
            'toast--success': toast.variant === 'success', 
            'toast--error': toast.variant === 'error',
            'toast--visible': toast.visible,
        }"
    >
        {{ toast.message }}
    </div>
</template>


<script setup lang="ts">
    import { ref, Ref } from 'vue';
    import eventBus from '@/event-bus';

    const duration: number = 3000;

    type Variant = 'success' | 'error' ;

    type Toast = {
        message: string,
        variant: Variant,
        visible: boolean,
    };

    const toast: Ref<Toast> = ref({
        message: '',
        variant: 'success',
        visible: false,
    });

    eventBus.$on('toast-success', (message: string): void => 
        setMessage('success', message)
    );
    
    eventBus.$on('toast-error', (message: string): void => {
        setMessage('error', message)
    });

    const setMessage = (variant: Variant, message: string): void => {
        toast.value = {
            variant: variant,
            message: message,
            visible: true,
        };

        setTimeout((): boolean => toast.value.visible = false, duration);
    };
</script>


<style scoped>
    .toast {
        @apply 
        absolute top-4 right-4 
        p-4 
        rounded 
        font-bold text-white
        transition
        opacity-0
        pointer-events-none
    }

    .toast--visible {
        @apply opacity-100
    }

    .toast--error {
        @apply bg-red-500 
    }

    .toast--success {
        @apply bg-green-500
    }
    
</style>