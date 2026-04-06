<template>
  <input
    :value="modelValue"
    @input="onInput"
    @keypress="onKeyPress"
    type="text"
    class="form-control form-control-sm"
    maxlength="18"
  />
</template>

<script setup>
  import { defineProps, defineEmits } from 'vue'

  const props = defineProps({
    modelValue: String
  })

  const emit = defineEmits(['update:modelValue'])

  function formatCnpj(value) {
    value = value.replace(/\D/g, '')
    value = value.slice(0, 14)

    value = value.replace(/^(\d{2})(\d)/, '$1.$2')
    value = value.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3')
    value = value.replace(/\.(\d{3})(\d)/, '.$1/$2')
    value = value.replace(/(\d{4})(\d{1,2})$/, '$1-$2')

    return value
  }

  function onInput(event) {
    const formatted = formatCnpj(event.target.value)
    emit('update:modelValue', formatted)
  }

  function onKeyPress(event) {
    const allowed = /[0-9]/
    if (!allowed.test(event.key)) {
      event.preventDefault()
    }
  }
</script>