import { Ref } from 'vue';
import axios, { AxiosError } from 'axios';

import { FachbezogeneFloskel, Schueler, Leistung } from '../types';

import { Occurrence } from "@/Interfaces/Occurrence"; // TODO: Move these 3 to types
import { Pronoun } from "@/Interfaces/Pronoun";
import { Floskel } from "@/Interfaces/Floskel";

const pasteShortcut = (event: KeyboardEvent, bemerkung: Ref<string | null>, floskeln: Ref<any[]>): void => {
    if (event.key === 'Enter' && bemerkung.value) {
        let replacedBemerkung: string = bemerkung.value;
        event.preventDefault();

        let matches: RegExpMatchArray | null = bemerkung.value.match(/#[^\s]+/g);

        let matchedFloskeln: Floskel[] = floskeln.value.filter(
            (item: Floskel): boolean | null => matches && matches.includes(item.kuerzel)
        );

        matchedFloskeln.forEach((floskel: Floskel): string =>
            replacedBemerkung = replacedBemerkung.replace(new RegExp(floskel.kuerzel, 'g'), floskel.text)
        );

        bemerkung.value = replacedBemerkung;
    }
};

const search = (searchFilter: Ref<string>, search: string): boolean => search.toLocaleLowerCase().includes(
	searchFilter.value?.toLocaleLowerCase() ?? ''
);

const multiselect = (filter: Ref<string[]>, column: string): boolean => {
    return filter.value.length > 0 ? filter.value.includes(column) : true;
};

const formatBasedOnGender = (text: string | null, model: Schueler | Leistung): string => {
	if (!text) {
		return '';
	}
	
	const namePattern: RegExp = /\$Name\$|\$Vorname\$|\$Nachname\$/;
    const genderPattern: RegExp = /&([^%]*)%([^&]*)&/g;

	const pronouns: Pronoun = {
		m: 'Er',
		w: 'Sie',
	};

	const pronoun: string | null = pronouns[model.geschlecht] !== undefined ? pronouns[model.geschlecht] : null;

	const initialOccurrence: Occurrence = {
		"$name$": [model.vorname, model.nachname].join(' '),
		"$vorname$": model.vorname,
		"$nachname$": model.nachname,
	};
	const succeedingOccurrences: Occurrence = {
		"$name$": pronoun ?? model.vorname,
		"$vorname$": pronoun ?? model.vorname,
		"$nachname$": null
	};

	return text
        // Replace the first occurrence of the namePattern with the name of the "Schueler".
        .replace(new RegExp(namePattern, "i"), (matched: string): string => {
            return initialOccurrence[matched.toLowerCase()];
        })
        // Replace any following occurrence of the namePattern with the correct pronoun and capitalised/small depending on sentence position
        //TODO: dativ case is not foreseen but is present in some floskeln (no specific pattern applied in floskeln, though)
		.replaceAll(new RegExp(namePattern, "gi"), ((matched, offset, fullString) => {
			let formatedReturnValue: string = succeedingOccurrences[matched.toLowerCase()];
			return fullString.charAt(offset - 2) ===  "." ? formatedReturnValue : formatedReturnValue.toLowerCase();
        })
	)
        // Replace any other word with the specific pattern selecting the results by gender.
        .replace(genderPattern, (matched: string, maleText, femaleText): string => {
            switch (model?.geschlecht) {
                case 'm':
					return maleText
                case 'w':
					return femaleText;
                default:
                    return matched + " !!!!!! D/X Geschlecht !!!!!!";
            };
        });
}

//TODO: check if this function is actually not needed anymore (seems to be the case)
const tableFilter = (
	floskel: FachbezogeneFloskel,
	column: string,
	value: Ref<Number>,
	containsOnlyEmptyOption: boolean = false,
): boolean => {
	if (containsOnlyEmptyOption && value.value == null) {
		return floskel[column] == null;
	}

	if (value.value == 0) {
		return true;
	}

	return floskel[column as keyof Floskel] == value.value["index"];
};

const closeEditor = (isDirty: Ref<boolean>, callback: any): void => {
	const changesNotSavedWarning: string =
        'Achtung die Änderungen sind noch nicht gespeichert! Diese gehen verloren, wenn Sie fortfahren.';

	if (isDirty.value ? confirm(changesNotSavedWarning) : true) {
		if (typeof callback === 'function') {
			callback();
		}
	}
};

//previously with possibly several checkboxes: Ref<Floskel[]|FachbezogeneFloskel[] and selectedFloskeln.value.map, and so on
//TODO: correct type issue
const addSelectedToBemerkung = (
	bemerkung: Ref<string|null>,
	selectedFloskel: Floskel|FachbezogeneFloskel,
): void => {
	bemerkung.value = bemerkung.value ? bemerkung.value + " " + selectedFloskel.text : selectedFloskel.text;
}

const saveBemerkung = (
	routeName: string,
	id: number,
	data: Object,
	bemerkung: Ref<string|null>,
	storedBemerkung: Ref<string|null>,
	isDirty: Ref<boolean>,
	callback: any,
) => axios
	.post(route(routeName, id), data)
	.then((): void => {
		storedBemerkung.value = bemerkung.value;
		isDirty.value = false;
		if (typeof callback === 'function') {
			callback();
		}
	})
	.catch((error: AxiosError): void => {
		alert('Speichern nicht möglich!');
		console.log(error);
	});

export {
    search,
	tableFilter,
    formatBasedOnGender,
	closeEditor,
    addSelectedToBemerkung,
	saveBemerkung,
    pasteShortcut,
    multiselect,
}
