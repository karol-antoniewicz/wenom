import { DataTableColumn } from "@svws-nrw/svws-ui";

/**
 * Exports tabledata as a CSV file.
 * 
 * @param cols - The visible columns of the table.
 * @param hiddenColumns - The columns that are hidden.
 * @param rowsFiltered - The filtered data for export.
 * @param fileName - The name of the file to be downloaded.
 */
export const exportDataToCSV = <T extends Record<string, any>>(
    cols: DataTableColumn[], 
    hiddenColumns: Set<string> | null,
    rowsFiltered: T[], 
    fileName: string
): void => {
    const visibleColumns = cols.filter(col => !hiddenColumns || !hiddenColumns.has(col.key));
    const keyAndLabel = visibleColumns.map(col => ({ key: col.key, label: col.label || col.key }));

    // Add separate columns for first name and last name
    const extendedKeyAndLabel = keyAndLabel.reduce((acc, col) => {
        if (col.key === 'name') {
            acc.push({ key: 'nachname', label: 'Nachname' });
            acc.push({ key: 'vorname', label: 'Vorname' });
        } else {
            acc.push(col);
        }
        return acc;
    }, [] as { key: string; label: string }[]);

    const exportData: Record<string, string>[] = rowsFiltered.map(row =>
        extendedKeyAndLabel.reduce((filteredRow: Record<string, string>, col) => {
            let value = row[col.key];
            if (col.key === 'name') {
                const [nachname, vorname] = splitName(value);
                filteredRow['nachname'] = nachname;
                filteredRow['vorname'] = vorname;
            } else {
                if (value === true) {
                    value = 'ja';
                } else if (value === false) {
                    value = 'nein';
                } else if (value === undefined || value === null) {
                    value = '';
                } else {
                    value = String(value);
                }
                filteredRow[col.key] = value;
            }
            return filteredRow;
        }, {})
    );

    downloadCSV(arrayToCSV(exportData, extendedKeyAndLabel), fileName);
};


/**
 * Splits a full name into last name and first name.
 * 
 * @param fullName - The full name to be split.
 * @returns An array with last name and first name.
 */
const splitName = (fullName: string): [string, string] => {
    const [nachname, vorname] = fullName.split(',').map(part => part.trim());
    return [nachname || '', vorname || ''];
};


/**
 * Converts array data to a CSV-formatted string.
 * 
 * @param data - The data to be converted.
 * @param columns - The columns to be included in the CSV.
 * @returns A CSV-formatted string.
 */
const arrayToCSV = (
    data: Record<string, any>[], 
    columns: { key: string; label: string }[]
): string => {
    const headers = columns.map(col => `"${col.label}"`).join(';');

    const rows = data.map(row =>
        columns.map(col => {
            let value = row[col.key].replace(/"/g, '""');
            return `"${value}"`;
        }).join(';')
    );

    return [headers, ...rows].join('\n');
};


/**
 * Downloads CSV data as a file.
 * 
 * @param csvData - The CSV-formatted data.
 * @param title - The title to be used for the downloaded file.
 */
const downloadCSV = (
    csvData: string, 
    title: string
): void => {
    const blob = new Blob([csvData], { type: 'text/csv;charset=utf-8' });
    const url = window.URL.createObjectURL(blob);

    const a = document.createElement('a');
    a.href = url;
    a.download = `${title}.csv`;

    document.body.appendChild(a);
    a.click();
    document.body.removeChild(a);

    window.URL.revokeObjectURL(url);
};
