<template>
  <div>
    <CompaniesComponent />
  </div>
</template>

<script>
import CompaniesComponent from '../../components/registration/companies/Companies.vue';

export default {
  name: "CompaniesView",

  components: {
    CompaniesComponent,
  }
}
</script>