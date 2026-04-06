<template>
  <div
    class="modal fade"
    :class="{ show: showModal }"
    tabindex="-1"
    :style="{ display: showModal ? 'block' : 'none'}"
  >
    <div class="modal-dialog modal-lg modal-dialog-centered">
      <div class="modal-content">
        <div class="modal-header bg-light">
          <h5 class="modal-title">{{ dialogTitle }}</h5>
          <button type="button" class="btn-close" @click="closeModal"></button>
        </div>

        <div class="modal-body">
          <div v-if="errorMessage" class="alert alert-warning">
            {{ errorMessage }}
          </div>

          <form @submit.prevent="submitForm">
            <div class="mb-3">
              <label class="form-label">Nome da Empresa</label>
              <input type="text" class="form-control" v-model="form.name" />
            </div>

            <div class="mb-3">
              <label class="form-label">CNPJ</label>
              <CnpjInput v-model="form.cnpj"/>
            </div>

            <div class="mb-3">
              <label class="form-label">Status</label>
              <select class="form-select" v-model="form.status">
                <option :value="1">Ativa</option>
                <option :value="0">Inativa</option>
              </select>
            </div>

            <div class="d-flex justify-content-end">
              <button type="button" class="btn btn-secondary me-2" @click="closeModal">Cancelar</button>
              <button type="submit" class="btn btn-success">Salvar</button>
            </div>
          </form>
        </div>

      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, watch, computed } from 'vue'
import { CompanyService } from '../../../services/CompanyService'
import Swal from 'sweetalert2' 
import CnpjInput from '../../CnpjInput.vue'

  const props = defineProps({
    modelValue: Boolean,
    companyId: [String, Number]
  })

  const emit = defineEmits(['update:modelValue', 'save'])
  const showModal = ref(false)
  const errorMessage = ref('')
  const form = ref({ id: null, name: '', cnpj: '', status: true })
  const loading = ref(false)

  watch(() => props.modelValue, (val) => {
    showModal.value = val
    if (val && props.companyId) loadCompany(props.companyId)
  })

  const dialogTitle = computed(() => (props.companyId ? 'Editar Empresa' : 'Nova Empresa'))

  function closeModal() {
    showModal.value = false
    emit('update:modelValue', false)
    resetForm()
  }

  function resetForm() {
    form.value = { id: null, name: '', cnpj: '', status: true }
    errorMessage.value = ''
  }

  async function loadCompany(id) {
    try {
      loading.value = true
      const res = await CompanyService.getCompanyById(id)
      form.value = { id: String(res.id), name: res.name, cnpj: res.cnpj, status: res.status }
    } catch (err) {
      console.error(err)
      errorMessage.value = 'Erro ao carregar empresa.'
    } finally {
      loading.value = false
    }
  }

  async function submitForm() {
    if (!form.value.name.trim() || !form.value.cnpj.trim()) {
      errorMessage.value = 'Os campos Nome e CNPJ são obrigatórios.'
      return
    }

    try {
      loading.value = true
      if (props.companyId) {
        await CompanyService.updateCompany(props.companyId, form.value)
      } else {
        await CompanyService.createCompany(form.value)
      }
      emit('save')

      Swal.fire({
        title: 'Salvo com sucesso!',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false,
      })

      closeModal()
    } catch (err) {
      Swal.fire({
        title: 'Erro ao salvar',
        text: 'Tente novamente.',
        icon: 'error',
        timer: 2500,
        showConfirmButton: false,
      })
    } finally {
      loading.value = false
    }
  }
</script>