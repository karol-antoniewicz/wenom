export interface TableColumn {
	key: string,
	label: string,
	sortable: boolean,
	span?: string | Number,
}

export interface FachbezogeneFloskel {
	[index: string]: string,
	kuerzel: string,
	text: string,
	niveau: string
	jahrgang: string
}

export interface Floskel {
	id: number,
	gruppe: string,
	kuerzel: string,
	text: string
}

export interface Schueler {
	id: Number,
	vorname: string,
	nachname: string,
	name: string,
	geschlecht: string,
	bemerkung: object,
	fachbezogeneBemerkungen: string,
	asv: string | null,
	aue: string | null,
	zb: string | null,
	gfs: Number,
	gfsu: Number,
}

export interface Leistung {
	id: number,
	klasse: string|Number|null,
	name: string,
	vorname: string,
	nachname: string,
	geschlecht: string,
	fach: string|null,
	fach_id: number|null,
	lehrer: string,
	jahrgang: string,
	kurs: string|null,
	note: string|null,
	fachbezogeneBemerkungen: string|null,
	fehlstundenGesamt: Number,
	fehlstundenUnentschuldigt: Number,
	fs: number|null,
	fsu: number|null,
	istGemahnt: boolean,
	mahndatum: string,
	schueler?: Schueler,
	matrix: {
		editable_teilnoten: boolean,
		editable_noten: boolean,
		editable_mahnungen: boolean,
		editable_fehlstunden: boolean,
		editable_fb: boolean,
		editable_asv: boolean,
		editable_aue: boolean,
		editable_zb: boolean,
	},
}

interface Occurrence {
	'$vorname$ $nachname$': string,
	'$vorname$': string,
	'$nachname$': string | null
}

interface Pronoun {
	m: string,
	w: string,
}