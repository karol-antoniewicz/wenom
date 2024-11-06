import { Leistung } from '@/Interfaces/Leistung';
import { Schueler } from '@/Interfaces/Schueler';
import { Teilleistung } from '@/Interfaces/Teilleistung';

const emptyValueDescription: string = 'Leer';

const searchHelper = (model: Leistung|Teilleistung|Schueler, search: string[], searchFilter: string|null): boolean => {
    return search.some((searchString: string): boolean => model[searchString].toLocaleLowerCase().includes(
        searchFilter?.toLocaleLowerCase() ?? ''
    )
)};

const multiSelectHelper = (model: Leistung|Teilleistung|Schueler, column: string, filterItems: string[] = []): boolean => {
    return filterItems.length === 0 || filterItems.some((item: string|null): boolean => {
        return (item === 'Leer' ? null : item) === model[column];
    });
};


const selectHelper = (model: Leistung|Teilleistung|Schueler, column: string, fachItem: string): boolean => {
        return fachItem === "" || fachItem === model[column];
};


const mapFilterOptionsHelper = (rows: Leistung[]|Schueler[]|Teilleistung[], column: string): string[] => Array.from(
    new Set(rows.map((model: Leistung|Schueler|Teilleistung): string => model[column] ?? emptyValueDescription))
);

export {
    searchHelper,
    multiSelectHelper,
    selectHelper,
    mapFilterOptionsHelper,
};