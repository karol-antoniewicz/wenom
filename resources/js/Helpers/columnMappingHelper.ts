import { TableColumnToggle } from '@/Interfaces/Interface';

/**
 * Maps a toggle name to its corresponding database field.
 * @param toggleName - The name of the toggle.
 * @returns The corresponding database field name.
 */
export const mapToggleToDatabaseField = (toggleName: keyof TableColumnToggle): string => {
    // Define mappings between toggle names and database field names
    const mappings: Record<keyof TableColumnToggle, string> = {
        teilleistungen: "teilnoten",
        mahnungen: "istGemahnt",
        fachlehrer: "lehrer",
        bemerkungen: "fachbezogeneBemerkungen",
        fehlstunden: "fehlstunden",
        kurs: "kurs",
        note: "note",
        fach: "fach",
    };

    // Return the corresponding database field name or the original toggle name if not found
    return mappings[toggleName] || toggleName;
};