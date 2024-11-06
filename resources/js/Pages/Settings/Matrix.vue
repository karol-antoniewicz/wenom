<template>
    <AppLayout>
        <template #main>
            <SvwsUiHeader>
                {{ title }}
            </SvwsUiHeader>
            <div class="content">
                <section class="flex flex-col w-full gap-4">
                    <div class="content-area">
                        <!-- INFO: collapsible is not a valid attribute as it was for SvwsUiTable -->
                        <SvwsUiTable v-if="Object.entries(jahrgaenge).length || klassen.length" :columns="columns" :noData="false" :filterOpen="true" :allowArrowKeySelection="true">
                            <template #body>
                                <!-- Klassen ohne Jahrgaenge -->
                                <span v-if="klassen.length">
                                    <div class="svws-ui-tr" role="row">
                                        <div class="svws-ui-td" role="cell">
                                            <div class="flex items-center gap-1">
                                                <SvwsUiButton type="icon" size="small" @click="klassenCollapsed = !klassenCollapsed">
                                                <ri-arrow-down-s-line v-if="klassenCollapsed" />
                                                <ri-arrow-right-s-line v-else />
                                                </SvwsUiButton>
                                                Klassen
                                                <SvwsUiCheck v-model="klassenGlobalToggle" @update:modelValue="toggleAllKlassen()" :value="true"/>
                                            </div>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="klassenColumnsToggle['edit_overrideable']"
                                                @update:modelValue="toggleKlassenColumn('edit_overrideable')"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_teilnoten']" @update:modelValue="toggleKlassenColumn('editable_teilnoten')" :value="true"/>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_noten']" @update:modelValue="toggleKlassenColumn('editable_noten')" :value="true"/>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_mahnungen']" @update:modelValue="toggleKlassenColumn('editable_mahnungen')" :value="true"/>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox v-model="klassenColumnsToggle['editable_fehlstunden']" @update:modelValue="toggleKlassenColumn('editable_fehlstunden')" :value="true"/>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiRadioOption
                                                v-model="klassenColumnsToggle['toggleable_fehlstunden']" @update:modelValue="toggleKlassenColumn('toggleable_fehlstunden')" :value="true">
                                                FS
                                            </SvwsUiRadioOption>
                                            <SvwsUiRadioOption v-model="klassenColumnsToggle['toggleable_fehlstunden']" @update:modelValue="toggleKlassenColumn('toggleable_fehlstunden')" :value="false">
                                                GFS
                                            </SvwsUiRadioOption>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="klassenColumnsToggle['editable_fb']"
                                                @update:modelValue="toggleKlassenColumn('editable_fb')"
                                                :value="true"/>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="klassenColumnsToggle['editable_asv']"
                                                @update:modelValue="toggleKlassenColumn('editable_asv')"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="klassenColumnsToggle['editable_aue']"
                                                @update:modelValue="toggleKlassenColumn('editable_aue')"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="klassenColumnsToggle['editable_zb']"
                                                @update:modelValue="toggleKlassenColumn('editable_zb')"
                                                :value="true"
                                            />
                                        </div>
                                    </div>
                                    <div class="svws-ui-tr" role="row"
                                        :depth="2"
                                        v-for="klasse in klassen"
                                    >
                                        <div class="svws-ui-td" role="cell">
                                            {{ klasse.kuerzel }}
                                            <SvwsUiCheckbox
                                                v-model="klassenToggle[klasse.id]"
                                                @update:modelValue="toggleKlasse(klasse)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.edit_overrideable" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.editable_teilnoten" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.editable_noten" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.editable_mahnungen" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.editable_fehlstunden" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiRadioOption
                                                v-model="klasse.toggleable_fehlstunden"
                                                :value="true"
                                            >FS</SvwsUiRadioOption>
                                            <SvwsUiRadioOption
                                                v-model="klasse.toggleable_fehlstunden"
                                                :value="false"
                                            >GFS</SvwsUiRadioOption>
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.editable_fb" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip" >
                                            <SvwsUiCheckbox v-model="klasse.editable_asv" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip"  >
                                            <SvwsUiCheckbox v-model="klasse.editable_aue" />
                                        </div>
                                        <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                            <SvwsUiCheckbox v-model="klasse.editable_zb" />
                                        </div>
                                    </div>
                                </span>

                                <!-- Klassen mit Jahrgaenge -->
                                <span
                                    v-for="(groupedJahrgaenge, key) in jahrgaenge"
                                    v-if="Object.entries(jahrgaenge).length"
                                >
                                    <div class="svws-ui-tr" role="row" v-show="false">
                                        <div class="svws-ui-td" role="cell">
                                            <div class="flex items-center gap-y-4">
                                                <SvwsUiButton
                                                    type="icon"
                                                    size="small"
                                                    @click="jahgraengeCollapsed[key][0] = !jahgraengeCollapsed[key][0]"
                                                >
                                                    <ri-arrow-down-s-line v-if="jahgraengeCollapsed[key][0]" />
                                                    <ri-arrow-right-s-line v-else />
                                                </SvwsUiButton>
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangGroupToggle[key]"
                                                    @update:modelValue="toggleJahrgangGroup(groupedJahrgaenge, key)"
                                                    :value="true"
                                                />
                                                {{ key }}
                                            </div>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['edit_overrideable']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'edit_overrideable', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_teilnoten']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_teilnoten', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_noten']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_noten', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_mahnungen']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_mahnungen', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_fehlstunden']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_fehlstunden', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiRadioOption
                                                v-model="jahrgangsGroupsColumnsToggle[key]['toggleable_fehlstunden']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'toggleable_fehlstunden', key)"
                                                :value="true"
                                            >FS</SvwsUiRadioOption>
                                            <SvwsUiRadioOption
                                                v-model="jahrgangsGroupsColumnsToggle[key]['toggleable_fehlstunden']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'toggleable_fehlstunden', key)"
                                                :value="false"
                                            >GFS</SvwsUiRadioOption>
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_fb']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_fb', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_asv']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_asv', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_aue']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_aue', key)"
                                                :value="true"
                                            />
                                        </div>
                                        <div class="svws-ui-td" role="cell">
                                            <SvwsUiCheckbox
                                                v-model="jahrgangsGroupsColumnsToggle[key]['editable_zb']"
                                                @update:modelValue="toggleGroupColumn(groupedJahrgaenge, 'editable_zb', key)"
                                                :value="true"
                                            />
                                        </div>
                                    </div>

                                    <span v-for="(jahrgang, index) in groupedJahrgaenge">
                                        <div class="svws-ui-tr" role="row"
                                            :depth="1"
                                            :collapsed="jahgraengeCollapsed[key][0]"
                                            :expanded="!jahgraengeCollapsed[key][0]"
                                        >
                                            <div class="svws-ui-td" role="cell">
                                                <div class="flex items-center gap-1 justify-between">
                                                    <SvwsUiButton
                                                        type="icon"
                                                        size="small"
                                                        @click="jahgraengeCollapsed[key][1][index] = !jahgraengeCollapsed[key][1][index]"
                                                    >
                                                        <ri-arrow-down-s-line v-if="jahgraengeCollapsed[key][1][index]" />
                                                        <ri-arrow-right-s-line v-else />
                                                    </SvwsUiButton>
                                                    <SvwsUiCheckbox
                                                        v-model="jahrgangToggle[jahrgang.id]"
                                                        @update:modelValue="toggleJahrgang(jahrgang)"
                                                        :value="jahrgangToggle[jahrgang.id]"
                                                        :indeterminate="jahrgangToggle[jahrgang.id] === 'indeterminate'"
                                                    />
                                                    {{ jahrgang.kuerzel }}
                                                </div>
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['edit_overrideable']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'edit_overrideable')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['edit_overrideable']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['edit_overrideable'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_teilnoten']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_teilnoten')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_teilnoten']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_teilnoten'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_noten']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_noten')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_noten']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_noten'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_mahnungen']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_mahnungen')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_mahnungen']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_mahnungen'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fehlstunden']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_fehlstunden')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fehlstunden']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fehlstunden'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiRadioOption
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['toggleable_fehlstunden']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'toggleable_fehlstunden')"
                                                    :value="true"
                                                >FS</SvwsUiRadioOption>
                                                <SvwsUiRadioOption
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['toggleable_fehlstunden']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'toggleable_fehlstunden')"
                                                    :value="false"
                                                >GFS</SvwsUiRadioOption>
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fb']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_fb')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fb']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_fb'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_asv']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_asv')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_asv']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_asv'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell">
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_aue']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_aue')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_aue']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_aue'] === 'indeterminate'"
                                                />
                                            </div>
                                            <div class="svws-ui-td" role="cell" >
                                                <SvwsUiCheckbox
                                                    v-model="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_zb']"
                                                    @update:modelValue="toggleJahrgangsColumn(jahrgang, 'editable_zb')"
                                                    :value="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_zb']"
                                                    :indeterminate="jahrgangsKlassenColumnsToggle[jahrgang.id]['editable_zb'] === 'indeterminate'"
                                                />
                                            </div>
                                        </div>
                                        <div class="svws-ui-tr" role="row"
                                            :depth="2"
                                            :collapsed="jahgraengeCollapsed[key][0] || jahgraengeCollapsed[key][1][index]"
                                            :expanded="!jahgraengeCollapsed[key][1][index]"
                                            v-for="klasse in jahrgang.klassen" v-show="jahgraengeCollapsed[key][1][index]"
                                        >
                                            <div class="svws-ui-td" role="cell">
                                                <div class="flex items-center gap-1 ml-8">
                                                    <SvwsUiCheckbox
                                                        v-model="jahrgangKlassenToggle[klasse.id]"
                                                        @update:modelValue="toggleJahrgangsKlassenRow(klasse)"
                                                        :value="jahrgangKlassenToggle[klasse.id]"
                                                        :indeterminate="jahrgangKlassenToggle[klasse.id] === 'indeterminate'"
                                                    />
                                                    {{ klasse.kuerzel }}
                                                </div>
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.edit_overrideable" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_teilnoten" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_noten" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_mahnungen" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_fehlstunden" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiRadioOption
                                                    v-model="klasse.toggleable_fehlstunden"
                                                    :value="true"
                                                >FS</SvwsUiRadioOption>
                                                <SvwsUiRadioOption
                                                    v-model="klasse.toggleable_fehlstunden"
                                                    :value="false"
                                                >GFS</SvwsUiRadioOption>
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_fb" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_asv" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_aue" />
                                            </div>
                                            <div class="svws-ui-td" role="cell" :tooltip="cellTooltip">
                                                <SvwsUiCheckbox v-model="klasse.editable_zb" />
                                            </div>
                                        </div>
                                    </span>
                                </span>
                            </template>
                        </SvwsUiTable>
                    </div>
                    <SvwsUiButton @click="save()" :disabled="!isDirty">Speichern</SvwsUiButton>
                </section>
            </div>
        </template>
        <template #secondaryMenu>
            <SettingsMenu></SettingsMenu>
        </template>
    </AppLayout>
</template>


<script setup lang="ts">
    import { ref, Ref, onBeforeMount, watch } from 'vue';
    import axios, { AxiosResponse } from 'axios';
    import AppLayout from '@/Layouts/AppLayout.vue';
    import SettingsMenu from '@/Components/SettingsMenu.vue';
    import { apiSuccess, apiError } from '@/Helpers/api.helper';
    import { SvwsUiHeader, SvwsUiButton, SvwsUiCheckbox, SvwsUiTooltip, SvwsUiRadioOption, SvwsUiTable, DataTableColumn } from '@svws-nrw/svws-ui';

    //TODO: check if still needed, seems to have no effect so far
    const defaultCollapsed: boolean = true;

    const cellTooltip: string = `
        Durch Setzen des Hakens wird für diese Gruppe der zugehörige Bereich in
        den Leistungsdaten für die einzelne Lehrerkraft beschreibbar geschaltet.
    `;

    type CollapseReference = {
        [key: string]: [boolean, boolean[]],
    };

    type ToggleColumnType = boolean | 'indeterminate';

    interface ToggleColumns {
        edit_overrideable: ToggleColumnType,
        editable_teilnoten: ToggleColumnType,
        editable_noten: ToggleColumnType,
        editable_mahnungen: ToggleColumnType,
        editable_fehlstunden: ToggleColumnType,
        toggleable_fehlstunden: ToggleColumnType,
        editable_fb: ToggleColumnType,
        editable_asv: ToggleColumnType,
        editable_aue: ToggleColumnType,
        editable_zb: ToggleColumnType,
    }

    interface Klasse {
        id: number,
        kuerzel: string,
        sortierung: number,
        edit_overrideable: boolean
        editable_teilnoten: boolean,
        editable_noten: boolean,
        editable_mahnungen: boolean,
        editable_fehlstunden: boolean,
        toggleable_fehlstunden: boolean,
        editable_fb: boolean,
        editable_asv: boolean,
        editable_aue: boolean,
        editable_zb: boolean,
    }

    interface Jahrgang {
        id: number,
        kuerzel: string,
        stufe: string,
        klassen: Klasse[],
    }

    type JahrgangStructure = {
        [key: string]: Jahrgang[],
    };

    type Settings = {
        lehrer_can_override_fachlehrer: boolean,
    };

    type ToggleableKeys = {
        [K in keyof Klasse]: Klasse[K] extends boolean ? K : never;
    }[keyof Klasse];

    const settings: Ref<Settings> = ref({} as Settings);

    const columns = ref([
        { key: 'gruppierung', label: 'Gruppierung'},
        {
            key: 'edit_override',
            label: 'Die Klassenleitung darf alle Leistungsdaten bearbeiten.',
            tooltip: `
                Die Klassenleitung darf als Vertretung einer Fachlehrkraft auch die Noten, Teilnoten,
                usw. der Fachlehrkraft editieren. Der Button zum Editieren damit für alle Klassenleitungen sichtbar.
            `,
        },
        { key: 'teilnoten', label: 'Teilnoten'},
        { key: 'noten', label: 'Noten'},
        { key: 'mahnung', label: 'M', tooltip: "Mahnung"},
        { key: 'klasse', label: 'FS', tooltip: "Fehlstunden"},
        { key: 'klasse', label: 'FS/GFS', tooltip: "Fehlstunden/Gesamtfehlstunden"},
        { key: 'klasse', label: 'FB',  tooltip: "Fachbezogene Bemerkungen"},
        { key: 'klasse', label: 'ASV', tooltip: "Arbeits- und Sozialverhalten"},
        { key: 'klasse', label: 'AUE', tooltip: "Außerunterrichtliches Engagement"},
        { key: 'klasse', label: 'ZB', tooltip: "Zeugnisbemerkung"},
    ]) as Ref<DataTableColumn[]>;

    const title = 'Schreibrechte';

    const jahrgaenge: Ref<JahrgangStructure> = ref({});
    const klassen: Ref<Klasse[]> = ref([]);
    const jahgraengeCollapsed: Ref<CollapseReference> = ref({});
    const klassenCollapsed: Ref<boolean> = ref(defaultCollapsed);
    const storedSettings: Ref<String> = ref('');
    const storedKlassen: Ref<String> = ref('');
    const storedJahrgaenge: Ref<String> = ref('');
    const isDirty: Ref<boolean> = ref(false);

    onBeforeMount((): void => {
        axios.get(route('api.settings.matrix.index'))
            .then((response: AxiosResponse): void => {
                jahrgaenge.value = response.data.jahrgaenge;
                storedJahrgaenge.value = JSON.stringify(jahrgaenge.value);
                klassen.value = response.data.klassen;
                storedKlassen.value = JSON.stringify(klassen.value);
                setTableCollapseValues(response.data.jahrgaenge);
            })
            .catch((error: any): void => apiError(error));

        axios.get(route('api.settings.index', 'matrix'))
            .then((response: AxiosResponse): void => {
                settings.value = response.data;
                storedSettings.value = JSON.stringify(settings.value);
            })
            .catch((error: any): void => apiError(error));
    })

    // Creates the collapsed boolean table to switch the table toggles
    // with false, it is collapsed
    const setTableCollapseValues = (obj: Record<string, any[]>): void => Object.keys(obj).forEach((key: string) =>
        jahgraengeCollapsed.value[key] = [defaultCollapsed, Array(obj[key].length).fill(false)]
    )

    const save = (): void => {
        const klassenArray = Object.values(jahrgaenge.value)
            .flat()
            .map((item: Jahrgang): Klasse[] => item.klassen)
            .flat()
            .concat(klassen.value);

        axios.put(route('api.settings.matrix.update'), {klassen: klassenArray})
            .then((): void => apiSuccess())
            .then((): void  => {
                isDirty.value = false;
                storedKlassen.value = JSON.stringify(klassen.value);
                storedJahrgaenge.value = JSON.stringify(jahrgaenge.value);
            })
            .catch((error: any): void => apiError(
                error,
                'Ein Problem ist aufgetreten bei Speichern von der Matrix',
            ));

        axios.put(route('api.settings.bulk_update', {group: 'matrix'}), {settings: settings.value})
            .then((): void => apiSuccess())
            .then((): void  => {
                isDirty.value = false;
                storedSettings.value = JSON.stringify(settings.value);
            })
            .catch((error: any): void => apiError(
                error,
                'Ein Problem ist aufgetreten bei Speichern von "Die Klassenleitung darf alle Leistungsdaten bearbeiten."'
            ));
    }

    let toggleable: ToggleableKeys[] = [
        'edit_overrideable',
        'editable_teilnoten',
        'editable_noten',
        'editable_mahnungen',
        'editable_fehlstunden',
        'toggleable_fehlstunden',
        'editable_fb',
        'editable_asv',
        'editable_aue',
        'editable_zb',
    ];

    const klassenGlobalToggle = ref<ToggleColumnType>(true);
    const klassenToggle: Ref<{[key: number]: ToggleColumnType}> = ref({});
    const klassenColumnsToggle = ref<ToggleColumns>({
        edit_overrideable: false,
        editable_teilnoten: false,
        editable_noten: false,
        editable_mahnungen: false,
        editable_fehlstunden: false,
        toggleable_fehlstunden: false,
        editable_fb: false,
        editable_asv: false,
        editable_aue: false,
        editable_zb: false,
    });

    const jahrgangKlassenToggle: Ref<{[key: number]: ToggleColumnType}> = ref({});
    const jahrgangsKlassenColumnsToggle: Ref<{[key: number]: {[key: string]: ToggleColumnType}}> = ref({});
    const jahrgangsGroupsColumnsToggle: Ref<{[key: string]: {[key: string]: ToggleColumnType}}> = ref({});
    const jahrgangToggle: Ref<{[key: number]:  ToggleColumnType}> = ref({});
    const jahrgangGroupToggle: Ref<{[key: string]: ToggleColumnType}> = ref({});

    watch(klassen, (): void => {
        updateKlassenToggleState(klassen.value, klassenToggle);

        toggleable.forEach((column: ToggleableKeys): ToggleColumnType =>
            updateColumnToggleState(klassenColumnsToggle.value, klassen.value, column)
        );

        klassenGlobalToggle.value = checkState(
            Object.values(klassen.value).reduce((count: number, klasse: Klasse): number =>
                 count + checkboxes().filter((column: ToggleableKeys): boolean => klasse[column]).length
            , 0),
            checkboxes().length * klassen.value.length,
        );
    }, { deep: true });

    watch(jahrgaenge, (): void => {
        Object.entries(jahrgaenge.value).forEach(([key, jahrgangGroup]: [string, Jahrgang[]]): void => {
            jahrgangsGroupsColumnsToggle.value[key] = {};
            toggleable.forEach((column: ToggleableKeys): any =>
                jahrgangsGroupsColumnsToggle.value[key][column] = checkState(
                    jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                        count + jahrgang.klassen.filter((klasse: Klasse): boolean => klasse[column]).length,
                    0),
                    jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                        count + jahrgang.klassen.length,
                    0),
                )
            );

            jahrgangGroupToggle.value[key] = checkState(
                jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                    count + jahrgang.klassen.reduce((count: number, klasse: Klasse): number =>
                        count + checkboxes().filter((column: ToggleableKeys): boolean => klasse[column]).length
                    , 0)
                , 0),
                jahrgangGroup.reduce((count: number, jahrgang: Jahrgang): number =>
                    count + jahrgang.klassen.length
                , 0) * checkboxes().length
            );

            jahrgangGroup.forEach((jahrgang: Jahrgang): void => {
                jahrgangToggle.value[jahrgang.id] = checkState(
                    jahrgang.klassen.reduce((count: number, klasse: Klasse): number => {
                        return count + checkboxes().filter((column: ToggleableKeys): boolean => klasse[column]).length
                    }, 0),
                jahrgang.klassen.length * checkboxes().length,
                );

                jahrgangsKlassenColumnsToggle.value[jahrgang.id] = {};
                toggleable.forEach((column: ToggleableKeys): ToggleColumnType =>
                    updateColumnToggleState(jahrgangsKlassenColumnsToggle.value[jahrgang.id], jahrgang.klassen, column)
                );

                updateKlassenToggleState(jahrgang.klassen, jahrgangKlassenToggle);
            })
        })
        if (JSON.stringify(settings.value) === storedSettings.value) {
            isDirty.value = JSON.stringify(jahrgaenge.value) !== storedJahrgaenge.value;
        }
    }, { deep: true });

    watch((): boolean => settings.value.lehrer_can_override_fachlehrer, (): void => {
        if (JSON.stringify(jahrgaenge.value) === storedJahrgaenge.value) {
            isDirty.value = JSON.stringify(settings.value) !== storedSettings.value;
        }
    });

    const toggleAllKlassen = (): void => klassen.value.forEach((klasse: Klasse): void =>
        checkboxes().forEach((column: ToggleableKeys): boolean => klasse[column] = klassenGlobalToggle.value === true)
    );

    const toggleKlasse = (klasse: Klasse): void => {
        checkboxes().forEach((column: ToggleableKeys): boolean =>
            klasse[column] = klassenToggle.value[klasse.id] === true
        );
    };

    const toggleKlassenColumn = (column: ToggleableKeys): void => {
        klassen.value.forEach((klasse: Klasse): boolean =>
            klasse[column] = klassenColumnsToggle.value[column] === true
        );
    };

    const toggleJahrgangsColumn = (jahrgang: Jahrgang, column: ToggleableKeys): void => {
        jahrgang.klassen.forEach((klasse: Klasse): boolean =>
             klasse[column] = jahrgangsKlassenColumnsToggle.value[jahrgang.id][column] === true
        );
    };

    const toggleJahrgangsKlassenRow = (klasse: Klasse): void => {
        checkboxes().forEach((column: ToggleableKeys): boolean =>
            klasse[column] = jahrgangKlassenToggle.value[klasse.id] === true
        );
    };

    const toggleGroupColumn = (groupedJahrgaenge: Jahrgang[], column: ToggleableKeys, key: string) => {
        groupedJahrgaenge.forEach((jahrgang: Jahrgang): void =>
            jahrgang.klassen.forEach((klasse: Klasse): boolean =>
                klasse[column] = jahrgangsGroupsColumnsToggle.value[key][column] === true
            )
        );
    };

    const toggleJahrgangGroup = (jahrgaenge: Jahrgang[], key: string): void => {
        jahrgaenge.forEach((jahrgang: Jahrgang): void =>
            jahrgang.klassen.forEach((klasse: Klasse): void => checkboxes().forEach((column: ToggleableKeys) =>
                klasse[column] = jahrgangGroupToggle.value[key] === true)
            )
        );
    };

    const toggleJahrgang = (jahrgang: Jahrgang): void => {
        jahrgang.klassen.forEach((klasse: Klasse) => checkboxes().forEach((column: ToggleableKeys): boolean | string =>
            klasse[column] = jahrgangToggle.value[jahrgang.id] === true
        ));
    };

    const updateKlassenToggleState = (klassen: Klasse[], toggle: any): void => klassen.forEach((klasse: Klasse): ToggleColumnType =>
        toggle.value[klasse.id] = checkState(
            checkboxes().filter((item: ToggleableKeys): boolean => klasse[item]).length,
            checkboxes().length,
        )
    );

    const updateColumnToggleState = (toggleObject: any, items: Klasse[], column: ToggleableKeys): ToggleColumnType =>
        toggleObject[column] = checkState(items.filter((item: Klasse): boolean => item[column]).length, items.length);

    const checkState = (count: number, total: number): ToggleColumnType => {
        if (count == total) return true;
        if (count == 0) return false;
        return 'indeterminate';
    }

    const checkboxes = (): ToggleableKeys[] => toggleable.filter((column: ToggleableKeys): boolean => {
        return column !== 'toggleable_fehlstunden';
    });
</script>


<style scoped>
    header {
        @apply flex flex-col gap-4 p-6
    }

/* TODO: check if redundant */
    header #headline {
        @apply flex items-center justify-start gap-6 ml-6
    }

    table {
        @apply border
    }

    table td, table th {
        @apply border p-4
    }

    .content {
        @apply px-6 flex flex-col gap-12 items-start overflow-auto ml-6
    }

    .button {
        @apply self-start
    }
</style>
