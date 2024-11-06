import { VNodeRef, nextTick, ref, Ref } from 'vue';

export type CellRef = VNodeRef | undefined;
export type CellRefValue = Record<number, CellRef[]>;

const cellRefs: Ref<CellRefValue> = ref({});

const setCellRefs = (element: CellRef, rowIndex: number): void => {
  if (!cellRefs.value[rowIndex]) {
    cellRefs.value[rowIndex] = [];
  }

  cellRefs.value[rowIndex].push(element);
}

const navigateTable = async (direction: string, rowIndex: number, element: any): Promise<void> => {
  let cellIndex: number = cellRefs.value[rowIndex].indexOf(element);

  if (direction === 'up') {
    rowIndex = rowIndex > 0 ? rowIndex - 1 : rowIndex;
  }

  if (direction === 'down') {
    rowIndex = rowIndex < Object.keys(cellRefs.value).length - 1 ? rowIndex + 1 : rowIndex;
  }

  if (direction === 'left') {
    cellIndex = cellIndex > 0 ? cellIndex - 1 : cellIndex;
  }

  if (direction === 'right') {
    cellIndex = Object.keys(cellRefs.value[rowIndex]).length > cellIndex ? cellIndex + 1 : cellIndex;
  }

  await nextTick()     ;

  let target: any = cellRefs.value[rowIndex][cellIndex];

  if (target) {
    if (target.input) {
      target.input.focus();
    } else {
      target.focus();
    }  
  }
}

const selectItem = (event: MouseEvent): void => (event.target as HTMLInputElement).select();

export {
  navigateTable,
  setCellRefs,
  selectItem,
};