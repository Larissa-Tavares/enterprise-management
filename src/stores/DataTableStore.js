import { defineStore } from "pinia";

export const useDataTableStore = defineStore("dataTable", {
  state: () => ({
    perPage: 10,
  }),
  actions: {
    setPerPage(values) {
      this.perPage = values;
    },
    reset() {
      this.perPage = 10;
    }
  }
});