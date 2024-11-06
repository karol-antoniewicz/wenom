import { Floskel } from 'resources/js/Interfaces/Floskel'

export interface Floskelgruppe {
	kuerzel: string,
	floskeln: Floskel,
}

export const floskelgruppen: { [index: string]: any, asv: string, aue: string, zb: string, fb: string } = {
    asv: 'Arbeits- und Sozialverhalten',
    aue: 'Außerunterrichtliches Engagement',
    zb: 'Zeugnisbemerkung',
    fb: 'Fachbezogene Bemerkungen',
}
