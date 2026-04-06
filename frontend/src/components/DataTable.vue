<template>
  <div>
    <div class="d-flex justify-content-between align-items-center">
      <div v-if="!hidePerPage">
        <select v-model="perPage" class="form-select form-select-sm d-inline w-auto" id="perPageSelect">
          <option v-for="option in perPageOptions" :key="option" :value="option">
            {{ option }}
          </option>
        </select>
        <span class="ms-2">Resultados por página</span>
      </div>

      <div class="col-3 ms-auto" v-if="!hideSearch">
        <input
          v-model="search"
          type="text"
          class="form-control form-control-sm"
          placeholder="Buscar..."
          autocomplete="off"
        />
      </div>
    </div>

    <table :class="['table', 'table-sm', 'table-striped', showMarginTop ? 'mt-4' : '']">
      <thead>
        <tr>
          <th
            v-for="col in columns"
            :key="col.key"
            @click="col.sortable !== false && sortBy(col.key)"
            :class="[ getStickyClass(col), col.align ? col.align : '', col.thClass || '' ]"
            :style="{ cursor: col.sortable === false ? 'default' : 'pointer' }"
          >
            {{ col.label }}
            <template v-if="col.sortable !== false && showSortIcon">
              <span v-if="sortColumn == col.key">
                <span v-if="sortAsc === true">
                  <i class="fas fa-caret-up"></i>
                </span>

                <span v-else>
                  <i class="fas fa-caret-down"></i>
                </span>
              </span>

              <span v-else>
                <i class="fas fa-sort text-muted ms-2"></i>
              </span>
            </template>
          </th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="(row, index) in paginatedData" :key="row.id" :class="row.is_selected ? 'linha-selecionada' : ''">
          <td v-for="col in columns" :key="col.key" :class="[ col.align || '', col.tdClass || '' ]">
            <slot :name="`item.${col.key}`" :item="row">
              {{ row[col.key] }}
            </slot>
          </td>
        </tr>
        <tr v-if="loading">
          <td :colspan="columns.length" class="text-center">
            <div class="d-flex justify-content-center align-items-center">
              <div class="spinner-border spinner-border-sm text-primary me-2" role="status">
                <span class="visually-hidden">Carregando...</span>
              </div>
              <span>Carregando dados...</span>
            </div>
          </td>
        </tr>
        <tr v-else-if="paginatedData.length === 0">
          <td :colspan="columns.length" class="text-center">Sem resultados</td>
        </tr>
      </tbody>
    </table>

    <div class="d-flex justify-content-between align-items-center">
      <div class="mt-2">
        <small class="text-muted" v-if="!hidePerPage"> 
          Mostrando de {{ startItem }} até {{ endItem }} de {{ totalItems }} registros
        </small>
      </div>

      <nav v-if="!hidePagination">
        <ul class="pagination pagination-sm mb-0 small">
          <li class="page-item" :class="{ disabled: currentPage === 1 }">
            <button class="page-link" @click="changePage(currentPage - 1)">Anterior</button>
          </li>

          <li
            v-for="page in visiblePages"
            :key="page"
            class="page-item"
            :class="{ active: currentPage === page, disabled: page === '...' }"
          >
            <button
              class="page-link"
              @click="page !== '...' && changePage(page)"
              :disabled="page === '...'"
            >
              {{ page }}
            </button>
          </li>

          <li class="page-item" :class="{ disabled: currentPage === totalPages }">
            <button class="page-link" @click="changePage(currentPage + 1)">Próxima</button>
          </li>
        </ul>
      </nav>
    </div>

  </div>
</template>

<script>
import { useDataTableStore } from "../stores/DataTableStore";

export default {
  name: 'DataTable',

  props: {
    columns: {
      type: Array,
      required: true
    },

    data: {
      type: Array,
      required: true
    },

    loading: {
      type: Boolean,
      default: false
    },

    hideSearch: {
      type: Boolean,
      default: false
    },

    hidePerPage: {
      type: Boolean,
      default: false
    },

    hidePagination: {
      type: Boolean,
      default: false,
    },

    showSortIcon: {
      type: Boolean,
      default: false
    },
  },

  setup() {
    const tableStore = useDataTableStore();
    return { tableStore };
  },

  data() {
    return {
      search: '',
      perPageOptions: [10, 15, 20, 50, 100],
      sortColumn: null,
      sortAsc: true,
      currentPage: 1,
    }
  },

  computed: {
    visiblePages() {
      const pages = [];
      const total = this.totalPages;
      const current = this.currentPage;

      if (total <= 7) {
        for (let i = 1; i <= total; i++) {
          pages.push(i);
        }
      } else {
        pages.push(1);

        if (current > 3) {
          pages.push('...');
        }

        const start = Math.max(2, current - 2);
        const end = Math.min(total - 1, current + 2);

        for (let i = start; i <= end; i++) {
          pages.push(i);
        }

        if (current + 2 < total - 1) {
          pages.push('...');
        }

        pages.push(total);
      }

      return pages;
    },
    filteredData() {
      if (!this.data) return [];
      if (!this.search) return this.data

      const term = this.search.toLowerCase()

      return this.data.filter((row) =>
        this.columns.some((col) =>
          String(row[col.key]).toLowerCase().includes(term)
        )
      )
    },

    sortedData() {
      if (!this.filteredData) return [];
      if (!this.sortColumn) return this.filteredData

      return [...this.filteredData].sort((a, b) => {
        const valA = a[this.sortColumn]
        const valB = b[this.sortColumn]

        if (valA == null) return 1
        if (valB == null) return -1

        const isNumeric = !isNaN(valA) && !isNaN(valB)

        if (isNumeric) {
          return this.sortAsc ? valA - valB : valB - valA
        }

        return this.sortAsc
          ? String(valA).localeCompare(String(valB))
          : String(valB).localeCompare(String(valA))
      })
    },

    paginatedData() {
      if (!this.sortedData) return [];
      if (this.hidePerPage) return this.sortedData;
      
      const start = (this.currentPage - 1) * this.perPage
      return this.sortedData.slice(start, start + this.perPage)
    },

    totalPages() {
      return Math.ceil(this.sortedData.length / this.perPage)
    },

    totalItems() {
      return this.sortedData.length
    },

    startItem() {
      if (this.totalItems === 0) return 0
      return (this.currentPage - 1) * this.perPage + 1
    },

    endItem() {
      return Math.min(this.startItem + this.paginatedData.length - 1, this.totalItems)
    },

    showMarginTop() {
      return !this.hideSearch && !this.hidePerPage;
    },

    perPage: {
      get() {
        return this.tableStore.perPage;
      },
      set(val) {
        this.tableStore.setPerPage(val);
        this.currentPage = 1;
      }
    },
  },

  watch: {
    search() {
      this.currentPage = 1
    }
  },

  methods: {
    getStickyClass(col) {
      if (col.sticky === 'right') return 'sticky-col sticky-col-right'
      if (col.sticky === 'left') return 'sticky-col sticky-col-left'
      return ''
    },

    changePage(page) {
      if (page >= 1 && page <= this.totalPages) {
        this.currentPage = page
      }
    },

    sortBy(columnKey) {
      if (this.sortColumn === columnKey) {
        this.sortAsc = !this.sortAsc;
      } else {
        this.sortColumn = columnKey
        this.sortAsc = true;
      }
    },
  }
}
</script>