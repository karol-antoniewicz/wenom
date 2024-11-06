<template>
    <div v-if="state.leistung" class="wrapped">
        <div class="flex gap-6 items-center">
            <h1 class="svws-ui-headline-1">{{ leistung.schueler }}</h1>
            <svws-ui-badge variant="highlight" size="big" class="px-6">{{ leistung.fach }}</svws-ui-badge>
        </div>

        <div class="relative">
			<button type="button" @click="close" class="w-8 h-8 absolute top-0 -left-10 rounded-full svws-ui-bg-dark text-white flex items-center justify-center">
				<span class="icon">
                    <span class="sr-only">Schließen</span>
                    <svg viewBox="0 0 24 24" class="text-white h-6 w-6" fill="currentColor">
                        <path fill="none" d="M0 0h24v24H0z"/>
                        <path d="M12 10.586l4.95-4.95 1.414 1.414-4.95 4.95 4.95 4.95-1.414 1.414-4.95-4.95-4.95 4.95-1.414-1.414 4.95-4.95-4.95-4.95L7.05 5.636z"/>
                    </svg>
                </span>
			</button>
            <!-- deprecated; recommended: multiselect -->
            <svws-ui-tab-bar>
                <svws-ui-tab title="Bemerkungen">
                    <div class="flex flex-col h-full">
                        <div class="flex gap-6 justify-between">
                            <h2 class="svws-ui-headline-4">Floskeln</h2>
                            <div class="flex gap-3">
                                <!-- TODO: Q5, dropdown not working properly on framework -->
                                <button v-for="(floskelgruppe, index) in state.floskelgruppen" :key="floskelgruppe.id" @click="setFloskelgruppe(index)">{{ floskelgruppe.kuerzel }}</button>
                            </div>
                        </div>

                        <hr class="mt-6 border-gray-300" v-if="state.floskeln">

                        <div class="max-h-96 overflow-y-scroll" v-if="state.floskeln">
                            <svws-ui-table :cols="state.floskelnColumns" :rows="state.floskeln"></svws-ui-table>
                        </div>
                    </div>
                </svws-ui-tab>
                <svws-ui-tab title="Fördermaßnahmen">
                    Lorem ipsum dolor sit amet consectetur adipisicing elit. Iusto ipsa molestias corporis deleniti sapiente dolores praesentium aliquid est aperiam. Voluptatum dolore eius veniam commodi. Blanditiis, atque eum! Aliquam, hic. Deserunt.
                </svws-ui-tab>ç
            </svws-ui-Tab-bar>
        </div>
    </div>
</template>


<script setup lang="ts">
    // TODO: TBR
    import { onMounted, reactive, watch} from 'vue';
    import axios, {AxiosPromise, AxiosResponse} from 'axios';

    import { Leistung, Floskel } from '../Interfaces/Interface';

    let props = defineProps<{
        leistung: Leistung
    }>();

    const state = reactive({
        leistung: <Leistung | null> null,
        floskelgruppen: [],
        floskeln: <Floskel[]> [],
        floskelnColumns: [
            {id: 'gruppe', title: 'Gruppe'},
            {id: 'kuerzel', title: 'Kuerzel', sortable: true},
            {id: 'text', title: 'Text', sortable: true},
        ],
    });

    const setFloskelgruppe = (id: number): Number => state.floskeln = state.floskelgruppen[id].floskeln;
    const close = (): null => state.leistung = null;

    onMounted((): AxiosPromise => axios
        .get("/api/getFloskeln")
        .then((response: AxiosResponse): AxiosResponse => state.floskelgruppen = response.data)
    );

    watch(() => props.leistung, (leistung: Leistung): Leistung => state.leistung = leistung);
    watch(() => state.floskelgruppen, (): Number => setFloskelgruppe(0));
</script>


<style scoped>
/**/
</style>