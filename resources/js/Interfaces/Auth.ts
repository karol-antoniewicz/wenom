export interface Auth {
	user: {
		'id': Number,
		'vorname': string,
		'nachname': string,
		'email': string,
		'klassen': Array<Object>,
		'lerngruppen': Array<Object>,
	},
	administrator: boolean,
	schoolName: string,
	lastLogin: string,
}