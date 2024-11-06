<template>
    <div>
        <AppLayout title="Einstellungen">
            <template #main></template>
            <template #secondaryMenu>
                <SettingsMenu></SettingsMenu>
            </template>
        </AppLayout>
    </div>
</template>


<script setup lang="ts">
    import AppLayout from '../../Layouts/AppLayout.vue';
    import SettingsMenu from '../../Components/SettingsMenu.vue';
</script>


<style scoped>
</style>