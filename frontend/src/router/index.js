import { createRouter, createWebHistory } from 'vue-router'
import CompaniesView from '../views/registration/CompaniesView.vue'
import CompanyDetailsView from '../views/registration/CompanyDetailsView.vue'


const routes = [
  {
    path: '/', 
    redirect: '/cadastro-empresa',
  },
  {
    path: '/cadastro-empresa', 
    component: CompaniesView,
  },
  {
    path: '/detalhe-empresa', 
    component: CompanyDetailsView,
  },
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router