export interface Teilleistung {
	[index: string]: any,
	id: number,
    leistung_id: number,
    teilleistungsart_id: number
	datum: string|null,
    bemerkung: number|null,
    note_id: number|null,
    tsArtID: number|null,
    tsDatum: string|null,
    tsBemerkung: string|null,
    tsNote: number|null,
}
