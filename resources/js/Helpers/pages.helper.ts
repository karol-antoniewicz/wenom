import { ComputedRef } from 'vue';
import { Leistung } from '@/Interfaces/Leistung';

const tableCellEditable = (condition: boolean, administrator: boolean, editMode: boolean = true): boolean => {
	return editMode && (administrator || condition);
}

const tableCellDisabled = (condition: boolean, administrator: boolean, editMode: boolean = true): boolean => {
	return !(editMode && (administrator || condition));
}

const nextNote = (rowId: number, leistungen: Leistung[]): void => {
	let indexCount: number = leistungen.length;
	let currentIndex: number = leistungen.findIndex((leistung: Leistung): boolean => leistung.id == rowId);

	if (++currentIndex < indexCount) {
		let input = document.getElementsByClassName('data-table__tbody')[0]
			.children[currentIndex]
			.getElementsByClassName('data-table__td__note')[0]
			.getElementsByTagName('input')[0];

		input.focus();
		input.select();
	}
}

export {
	tableCellEditable,
	tableCellDisabled,
	nextNote,
};