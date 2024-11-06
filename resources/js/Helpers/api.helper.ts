import eventBus from '@/event-bus';

const apiSuccess = (message: string = 'Erfolgreich gespeichert'): void => {
    eventBus.$emit('toast-success', message);
};

const apiError = (error: any, message: string = 'Es sind Fehler aufgetreten'): void => {
    eventBus.$emit('toast-error', message);
    console.log(`Es sind Fehler aufgetreten: ${error}`)  ;
}

export {
	apiSuccess,
	apiError,
}