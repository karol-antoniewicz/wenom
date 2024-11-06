//IDE may warn there are type errors here but interface Klasse is to be found on file Matrix
//klassenToggle and klassenGlobalToggle are also declared on Matrix
type ToggleableKeys = {
    [K in keyof Klasse]: Klasse[K] extends boolean ? K : never;
}[keyof Klasse];

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

const toggleKlasse = (klasse: Klasse) => toggleable.forEach(
    (column: ToggleableKeys): boolean => klasse[column] = klassenToggle.value[klasse.id] === true
);

const toggleAllKlassen = (): void => klassen.value.forEach((klasse: Klasse): void =>
    toggleable.forEach((column: ToggleableKeys): boolean =>
        klasse[column] = klassenGlobalToggle.value === true
    )
);

export {
    toggleable,
    toggleKlasse,
    toggleAllKlassen,
};
