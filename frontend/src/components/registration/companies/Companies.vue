<template>
  <div class="container mt-5">
    <div class="card shadow-sm">
      <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Cadatro de Empresas</h5>
        <button class="btn btn-outline-primary btn-sm" @click="createCompanie">
          Adicionar <i class="fas fa-plus ms-1"></i>
        </button>
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
              <button class="btn btn-sm" @click="editCompanie(item)">
                <i class="fa-solid fa-pen-to-square text-primary"></i>
              </button>
              <button class="btn btn-sm" @click="deleteCompanie(item.id)">
                <i class="fa-solid fa-trash-can text-danger"></i>
              </button>
            </div>
          </template>
        </DataTable>
      </div>
    </div>

    <CompanyDialog 
      v-model="companyDialogState.showModal"
      :company-id="companyDialogState.id"
      @save="loadCompanies"
    />
  </div>
</template>

<script>
import { ref, onMounted } from 'vue'
import { CompanyService } from "../../../services/CompanyService"
import { EmployeeService } from '../../../services/EmployeeService'
import DataTable from '../../DataTable.vue'
import CompanyDialog from './CompanyDialog.vue';
import Swal from 'sweetalert2' 

export default {
  name: 'CompaniesView',

  components: { 
    DataTable,
    CompanyDialog,
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
    const companyDialogState = ref({
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

    const createCompanie = () => {
      companyDialogState.value.showModal = true
      companyDialogState.value.id = null
    }

    const editCompanie = (item) => {
      companyDialogState.value.showModal = true
      companyDialogState.value.id = String(item.id)
    }

    const deleteCompanie = async (id) => {
      try {
        const employees = await EmployeeService.getEmployees();

        const linkedEmployees = employees.filter(e => String(e.company_id) === String(id));

        let message = 'Deseja realmente excluir esta empresa?';
        if (linkedEmployees.length > 0) {
          message = `Essa empresa possui ${linkedEmployees.length} funcionário(s) vinculado(s). Deseja realmente excluir?`;
        }

        const result = await Swal.fire({
          title: 'Atenção!',
          text: message,
          icon: 'warning',
          showCancelButton: true,
          confirmButtonText: 'Sim, excluir',
          cancelButtonText: 'Cancelar'
        });

        if (!result.isConfirmed) {
          return;
        }


        await CompanyService.deleteCompany(id);

        loadCompanies();

        Swal.fire({
          title: "Excluído com sucesso!",
          icon: "success",
          backdrop: true,
          showConfirmButton: false,
          timer: 1500,
        });
      } catch (error) {
        Swal.fire({
          title: "Erro ao deletar!",
          text: "Não foi possível remover esta empresa.",
          icon: "error",
          backdrop: true,
          confirmButtonText: "OK",
        });

        console.error("Erro ao deletar:", error);
      }
    }

    onMounted(() => {
      loadCompanies()
    })

    return { header, companies, loading, companyDialogState, loadCompanies, createCompanie, editCompanie, deleteCompanie }
  },
}
</script>

<style scoped>
  .card {
    font-family: "Helvetica Neue", Arial, sans-serif;
  }
</style>