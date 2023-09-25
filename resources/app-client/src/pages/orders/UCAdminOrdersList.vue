<script setup lang="ts">
import OrderService from "@/services/OrderService";

onMounted(async () => {
  await OrderService.getOrders()
})
</script>

<template>
<div>
  test
</div>
</template>

<style scoped lang="scss">

</style>
