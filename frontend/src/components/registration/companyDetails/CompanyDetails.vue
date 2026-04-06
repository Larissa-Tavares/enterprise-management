<template>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Detalhes da Empresas</h5>
      </div>

      <div class="card-body">
        <DataTable :columns="header" :data="companies" :loading="loading">
          <template #item.status="{ item }">
            <span 
              class="badge rounded-pill" 
              :class="item.status ? 'bg-success' : 'bg-danger'"
            >
              {{ item.status ? 'Ativa' : 'Inativa' }}
            </span>
          </template>
          
          <template #[`item.actions`]="{ item }">
            <div class="d-flex justify-content-end gap-1">
              <button class="btn btn-sm" @click="viewCompanie(item)">
                <i class="fa-solid fa-file-pen text-primary"></i>
              </button>
            </div>
          </template>
        </DataTable>
      </div>
    </div>

    <CompanyDetailsDialog 
      v-model="CompanyDetailsDialog.showModal"
      :company-id="CompanyDetailsDialog.id"
      @save="loadCompanies"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { CompanyService } from "../../../services/CompanyService"
import DataTable from '../../DataTable.vue'
import CompanyDetailsDialog from './CompanyDetailsDialog.vue';

export default {
  name: 'CompanyDetails',

  components: { 
    DataTable,
    CompanyDetailsDialog,
  },

  setup() {
    const header = ref([
      { key: 'name', label: 'Nome', sortable: true },
      { key: 'cnpj', label: 'CNPJ', sortable: true },
      { key: 'status', label: 'Status', sortable: true },
      { key: 'actions', label: 'Ações', align: 'text-end' },
    ])

    const companies = ref([])
    const loading = ref(false)
    const CompanyDetailsDialog = ref({
      showModal: false,
      id: null
    })

    const loadCompanies = async () => {
      try {
        loading.value = true
        const response = await CompanyService.getCompanies();
        companies.value = response;
      } catch (error) {
        console.error('Erro ao buscar empresas', error)
      } finally {
        loading.value = false
      }
    }

    const viewCompanie = (item) => {
      CompanyDetailsDialog.value.showModal = true
      // CompanyDetailsDialog.value.id = String(item.id)
      CompanyDetailsDialog.value.id = item.id
    }

    onMounted(() => {
      loadCompanies()
    })

    return { header, companies, loading, CompanyDetailsDialog, loadCompanies, viewCompanie }
  },
}
</script>

<style scoped>
  .card {
    font-family: "Helvetica Neue", Arial, sans-serif;
  }
</style>