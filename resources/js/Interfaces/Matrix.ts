export interface Matrix {
    edit_overrideable: boolean;
    editable_teilnoten: boolean;
    editable_noten: boolean;
    editable_mahnungen: boolean;
    editable_fehlstunden: boolean;
    toggleable_fehlstunden: boolean;
    editable_fb: boolean;
    editable_asv: boolean;
    editable_aue: boolean;
    editable_zb: boolean;
    // Define the index signature to tell TypeScript
    // that we can index this object with any string that returns a boolean.
    [key: string]: boolean;
}
