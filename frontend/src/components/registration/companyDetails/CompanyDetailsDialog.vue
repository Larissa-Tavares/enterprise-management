<template>
  <div class="modal fade" :class="{ show: showModal }" tabindex="-1" :style="{ display: showModal ? 'block' : 'none' }">
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title">Detalhes da Empresa</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>

        <div class="modal-body">
          <div class="row mb-2">
            <div class="col-md-6">
              <p><strong>Nome: </strong> {{ company.name }}</p>
            </div>
            <div class="col-md-4">
              <p><strong>CNPJ: </strong> {{ company.cnpj }}</p>
            </div>
            <div class="col-md-2 text-end">
              <p>
                <span class="badge" :class="company.status ? 'bg-success' : 'bg-danger'">
                  {{ company.status ? 'Ativa' : 'Inativa' }}
                </span>
              </p>
            </div>
          </div>

          <div class="text-end my-2 pt-2">
            <button class="btn btn-outline-primary btn-sm" @click="createEmployee">
              Adicionar Funcionário<i class="fas fa-plus ms-1"></i>
            </button>
          </div>

          <DataTable :columns="header" :data="employees" :hideSearch="true" :hidePerPage="true" :hidePagination="true" :showSortIcon="false">
            <template #[`item.actions`]="{ item }">
              <div class="d-flex justify-content-end gap-1">
                <button class="btn btn-sm" @click="editEmployees(item)">
                  <i class="fa-solid fa-pen-to-square text-primary"></i>
                </button>
                <button class="btn btn-sm" @click="deleteEmployees(item.id)">
                  <i class="fa-solid fa-trash-can text-danger"></i>
                </button>
              </div>
            </template>
          </DataTable>

        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" @click="closeModal">Fechar</button>
        </div>
      </div>
    </div>
  </div>

  <Employee
    v-model="EmployeeDialogState.showModal"
    :employee-id="EmployeeDialogState.id"
    :company-id="EmployeeDialogState.companyId"
    @save="loadEmployee"
  />
</template>

<script setup>
import { ref, watch } from 'vue'
import { CompanyService } from '../../../services/CompanyService'
import { EmployeeService } from '../../../services/EmployeeService'
import DataTable from '../../DataTable.vue'
import Employee from './EmployeeDialog.vue'
import Swal from 'sweetalert2' 


  const props = defineProps({
    modelValue: Boolean,
    companyId: Number
  })

  const emit = defineEmits(['update:modelValue', 'save'])

  const showModal = ref(false)
  const EmployeeDialogState = ref({
    showModal: false,
    id: null,
  })
  const company = ref({ name: '', cnpj: '', status: true })
  const employees = ref([])

  const header = [
    { key: 'name', label: 'Nome', sortable: true },
    { key: 'email', label: 'Email', sortable: true },
    { key: 'position', label: 'Cargo', sortable: true },
    { key: 'actions', label: 'Ações', align: 'text-end' },
  ]

  watch(() => props.modelValue, (val) => {
    showModal.value = val
    if (val && props.companyId) loadCompany(props.companyId)
  })

  function closeModal() {
    showModal.value = false
    emit('update:modelValue', false)
  }

  async function loadCompany(id) {
    try {
      const res = await CompanyService.getCompanyById(id)
      company.value = { name: res.name, cnpj: res.cnpj, status: res.status }

      employees.value = res.employees || []
    } catch (err) {
      console.error(err)
    }
  }

  async function loadEmployee() {
    try {
      await loadCompany(props.companyId)
    } catch (err) {
      console.error(err)
    }
  }

  const createEmployee = () => {    
    EmployeeDialogState.value.showModal = true
    EmployeeDialogState.value.id = null
    EmployeeDialogState.value.companyId = props.companyId
  }

  const editEmployees = (item) => {
    EmployeeDialogState.value.showModal = true
    EmployeeDialogState.value.id = item.id
    EmployeeDialogState.value.companyId = props.companyId
  }

  const deleteEmployees = async (id) => {
    try {
      const result = await Swal.fire({
        title: 'Atenção!',
        text: 'Deseja realmente excluir este funcionário?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, excluir',
        cancelButtonText: 'Cancelar'
      });

      if (!result.isConfirmed) {
        return;
      }

      await EmployeeService.deleteEmployee(id);

      loadEmployee();

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
        text: "Não foi possível remover este funcionário.",
        icon: "error",
        backdrop: true,
        confirmButtonText: "OK",
      });

      console.error("Erro ao deletar:", error);
    }
  }
</script>