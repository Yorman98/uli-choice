<script setup lang="ts">
import avatar1 from "@images/avatars/avatar-1.png";
import { useRouter } from "vue-router";
import { useUserStore } from "@/store/user";

const userStore = useUserStore();
const router = useRouter();

function goToBudgets(budget: BudgetInterface) {
  router.push({
    name: "budgets2",
  });
}

function goToOrders(budget: BudgetInterface) {
  router.push({
    name: "orders2",
  });
}

async function logout() {
  try {
    await userStore.logout();

    router.push({
      name: "login",
    });
  } catch (error) {
    console.error(error);
  }
}
</script>

<template>
  <VBadge dot location="bottom right" offset-x="3" offset-y="3" color="success" bordered>
    <VAvatar class="cursor-pointer" color="primary" variant="tonal">
      <VImg :src="avatar1" />

      <!-- SECTION Menu -->
      <VMenu activator="parent" width="230" location="bottom end" offset="14px">
        <VList>
          <!-- 👉 User Avatar & Name -->
          <VListItem>
            <template #prepend>
              <VListItemAction start>
                <VBadge
                  dot
                  location="bottom right"
                  offset-x="3"
                  offset-y="3"
                  color="success"
                >
                  <VAvatar color="primary" variant="tonal">
                    <VImg :src="avatar1" />
                  </VAvatar>
                </VBadge>
              </VListItemAction>
            </template>

            <VListItemTitle class="font-weight-semibold"> {{ userStore.userInfo.firstName }} </VListItemTitle>
            <VListItemSubtitle>{{ userStore.userInfo.role }}</VListItemSubtitle>
          </VListItem>
          <VDivider class="my-2" />

          <!-- 👉 Profile -->
          <VListItem link class="d-none">
            <template #prepend>
              <VIcon class="me-2" icon="bx-user" size="22" />
            </template>

            <VListItemTitle>Profile</VListItemTitle>
          </VListItem>

          <!-- 👉 Settings -->
          <VListItem link class="d-none">
            <template #prepend>
              <VIcon class="me-2" icon="bx-cog" size="22" />
            </template>

            <VListItemTitle>Settings</VListItemTitle>
          </VListItem>

          <!-- 👉 Cotizaciones -->
          <VListItem link @click="goToBudgets" class="">
            <template #prepend>
              <VIcon class="me-2" icon="bx-dollar" size="22" />
            </template>

            <VListItemTitle>Cotizaciones</VListItemTitle>
          </VListItem>

          <!-- 👉 Ordenes -->
          <VListItem link @click="goToOrders" class="">
            <template #prepend>
              <VIcon class="me-2" icon="bx-package" size="22" />
            </template>

            <VListItemTitle>Ordenes</VListItemTitle>
          </VListItem>

          <!-- 👉 FAQ -->
          <VListItem link class="d-none">
            <template #prepend>
              <VIcon class="me-2" icon="bx-help-circle" size="22" />
            </template>

            <VListItemTitle>FAQ</VListItemTitle>
          </VListItem>

          <!-- Divider -->
          <VDivider class="my-2" />

          <!-- 👉 Logout -->
          <VListItem @click="logout">
            <template #prepend>
              <VIcon class="me-2" icon="bx-log-out" size="22" />
            </template>

            <VListItemTitle>Logout</VListItemTitle>
          </VListItem>
        </VList>
      </VMenu>
      <!-- !SECTION -->
    </VAvatar>
  </VBadge>
</template>
