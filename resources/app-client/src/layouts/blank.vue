<script setup lang="ts">
import UserProfile from '@/layouts/components/UserProfile.vue'
import logo from "@images/logo-uli.png";
</script>

<template>
  <div class="layout-wrapper layout-blank">
    <VContainer class="header" :fluid="true">
      <VContainer>
        <VRow>
          <header class="inner-header">
            <div class="header-left">
              <RouterLink
                to="/products"
              >
                <VImg :src="logo" alt="Logo" width="100px" />
              </RouterLink>
            </div>
            <div class="header-right">
              <UserProfile />
            </div>
          </header>
        </VRow>
      </VContainer>
    </VContainer>

    <RouterView />
  </div>
</template>

<style>
.layout-wrapper.layout-blank {
  flex-direction: column;
}

.header {
  width: 100%;
  background-color: #fff; /* Cambia el color de fondo según tus preferencias */
  box-shadow: 0px 2px 4px rgba(0, 0, 0, 0.1); /* Agrega una sombra */
}

.inner-header {
  display: flex;
  justify-content: space-between;
  align-items: center;
  width: 100%;
}

.header-left {
  flex: 1; /* Permite que la imagen ocupe el espacio restante en la izquierda */
}

.header-left img {
  max-width: 120px; /* Establece el ancho máximo de tu logo */
}

.header-right {
  display: flex;
  align-items: center;
}
</style>
