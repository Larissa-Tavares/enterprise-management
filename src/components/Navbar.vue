<template>
    <nav class="navbar navbar-light bg-white px-3 py-2 border-bottom w-100">
      <div class="d-flex justify-content-between w-100 align-items-center">
            
        <div class="text-center">
          <p class="mb-0">Enterprise Management</p>
        </div>

        <div class="d-flex gap-3">
          <router-link class="btn nav-btn text-black" to="/cadastro-empresa">
            Cadastro Empresa
          </router-link>

          <router-link class="btn nav-btn text-black" to="/detalhe-empresa">
            Detalhes da Empresa
          </router-link>
        </div>

      </div>
    </nav>
</template>