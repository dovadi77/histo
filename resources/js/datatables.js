import { DataTable } from "simple-datatables";

const initTable = (componentID) => {
    return new DataTable(`#${componentID}`);
};

export { initTable };
