<template>
  <div 
    class="modal fade"
    :class="{ show: modelValue }" 
    tabindex="-1" 
    :style="{ display: modelValue ? 'block' : 'none' }"
  >
    <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title">{{ dialogTitle }}</h5>
          <button type="button" class="btn-close" @click="close"></button>
        </div>

        <div class="modal-body">
          <div v-if="errorMessage" class="alert alert-warning">
            {{ errorMessage }}
          </div>

          <form @submit.prevent="submitEmployee">
            <div class="mb-3">
              <label class="form-label">Nome</label>
              <input type="text" class="form-control" v-model="form.name" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Email</label>
              <input type="email" class="form-control" v-model="form.email" required />
            </div>
            <div class="mb-3">
              <label class="form-label">Cargo</label>
              <input type="text" class="form-control" v-model="form.position" required />
            </div>
            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-secondary me-2" @click="close">Cancelar</button>
              <button type="submit" class="btn btn-success">Salvar</button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, watch } from 'vue'
import Swal from 'sweetalert2'
import { EmployeeService } from '../../../services/EmployeeService'

  const props = defineProps({ 
    modelValue: Boolean,
    companyId: [String, Number],
    employeeId: [String, Number]
  })

  const emit = defineEmits(['update:modelValue', 'save'])

  const form = ref({ id: null, name: '', email: '', position: '', companyId: null })
  const loading = ref(false)
  const errorMessage = ref('')

  const dialogTitle = computed(() => (props.employeeId ? 'Editar Funcionário' : 'Novo Funcionário'))

  watch(
    [() => props.modelValue, () => props.employeeId, () => props.companyId],
    async ([open, employeeId, companyId]) => {
      if (!open) return

      if (employeeId) {
        await loadEmployee(employeeId)
      } else {
        resetForm()
        form.value.companyId = companyId
      }
    },
    { immediate: true }
  )

  function close() {
    emit('update:modelValue', false)
    resetForm()
  }

  function resetForm() {
    form.value = { id: null, name: '', email: '', position: '', companyId: null }
    errorMessage.value = ''
  }

  async function loadEmployee(id) {
    try {
      loading.value = true
      const res = await EmployeeService.getEmployeeById(id)

      form.value = { id: res.id, name: res.name, email: res.email, position: res.position, companyId: res.company_id }
    } catch (err) {
      console.error(err)
      errorMessage.value = 'Erro ao carregar funcionários.'
    } finally {
      loading.value = false
    }
  }

  async function submitEmployee() {
    if (!props.companyId) {
      console.error('companyId não chegou ainda')
      return
    }
    loading.value = true

    try {
      let response
      const payload = { ...form.value, company_id: props.companyId }

      if (form.value.id) {
        response = await EmployeeService.updateEmployee(form.value.id, payload)
      } else {
        response = await EmployeeService.createEmployee(payload)
      }

      emit('save', response)
      
      Swal.fire({
        title: 'Funcionário salvo!',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
      })

      close()
    } catch (err) {
      Swal.fire({
        title: 'Erro ao salvar',
        text: 'Tente novamente.',
        icon: 'error'
      })
      console.error(err)
    } finally {
      loading.value = false
    }
  }
</script>