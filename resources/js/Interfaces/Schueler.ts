export interface Schueler {
//this interface also accepts other properties (eg. ZB uppercase on BemerkungEditor)
	[index: string]: any,
	id: number,
	vorname: string,
	nachname: string,
	name: string,
	geschlecht: string,
	klasse: string,
	bemerkung: object,
	fachbezogeneBemerkungen: string,
	asv: string,
	aue: string | null,
	zb: string | null,
	gfs: Number,
	gfsu: Number,
	editable: {
		[index: string]: boolean,
		fehlstunden: boolean,
		asv: boolean,
		aue: boolean,
		zb: boolean,
	},
}