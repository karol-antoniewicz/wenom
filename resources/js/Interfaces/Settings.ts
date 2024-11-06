export interface Settings {
	general: {
		name: string,
		address: string,
		email: string,

		hosting_provider_address: string,
		hosting_provider_name: string,

		board_address: string,
		board_contact: string,
		board_name: string,

		gdpr_address: string,
		gdpr_email: string,

		management_email: string,
		management_name: string,
		management_telephone: string,
	},
	matrix: {
		lehrer_can_override_note: boolean,
	},
	filters: {
		mein_unterricht_teilleistungen: boolean,
		mein_unterricht_mahnungen: boolean,
		mein_unterricht_fehlstunden: boolean,
		mein_unterricht_bemerkungen: boolean,
		mein_unterricht_kurs: boolean,
		mein_unterricht_note: boolean, 
		mein_unterricht_fach: boolean,

		leistungsdatenuebersicht_teilleistungen: boolean,
		leistungsdatenuebersicht_fachlehrer: boolean,
		leistungsdatenuebersicht_mahnungen: boolean,
		leistungsdatenuebersicht_fehlstunden: boolean,
		leistungsdatenuebersicht_bemerkungen: boolean,
		leistungsdatenuebersicht_kurs: boolean,
		leistungsdatenuebersicht_note: boolean, 
		leistungsdatenuebersicht_fach: boolean,
	},
	gdpr: {
		domain_url: string,
		domain_owner: string,

		creation_date: string,
		last_update: string,

		report_contact_person: string,
		feedback_form_url: string,
	},
}