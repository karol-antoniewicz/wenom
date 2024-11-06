export interface LoginFormData {
	form: {
		email: string,
		password: string,
		remember: boolean,
	},
	processing: boolean,
	errors: {[key: string]: string},
}

export interface PasswordRequestFormData {
	form: {
		email: string,
		kuerzel: string,
		schulnummer: string,
	},
	processing: boolean,
	errors: {[key: string]: string},
	successMessage: boolean,
}

export interface MailSendCredentialsFormData {
	form: {
		mailer: number,
        host: string,
        port: string,
        username: string,
        password: string,
        encryption: string,
        from_address: string,
        from_name: string,
	},
	processing: boolean,
	errors: {[key: string]: string},
	successMessage: boolean,
}

export interface TwoFA {
	form: {
		code: string,
		email: string,
	},
	processing: boolean,
	successMessage: boolean,
	errors: {[key: string]: string},
}