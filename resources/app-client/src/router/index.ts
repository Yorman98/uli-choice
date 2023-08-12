import { createRouter, createWebHistory } from 'vue-router'
import { authAdmin, authClient } from '@/router/middlewares/auth'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/login' },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'typography',
          name: 'clientDashboard',
          component: () => import('../pages/typography.vue'),
          meta: {
            middleware: [authClient],
          },
        },
        {
          path: 'account-settings',
          component: () => import('../pages/account-settings.vue'),
        },
        {
          path: 'icons',
          component: () => import('../pages/icons.vue'),
        },
        {
          path: 'cards',
          component: () => import('../pages/cards.vue'),
        },
        {
          path: 'tables',
          component: () => import('../pages/tables.vue'),
        },
        {
          path: 'form-layouts',
          component: () => import('../pages/form-layouts.vue'),
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'dashboard',
          name: 'adminDashboard',
          component: () => import('@/pages/dashboard.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'attributes/groups',
          name: 'attributesGroups',
          component: () => import('@/pages/attributes/UCAttributeGroups .vue'),
        },
        {
          path: 'attributes/list/:id',
          name: 'attributesList',
          component: () => import('@/pages/attributes/UCAttributesList.vue'),
        },
        {
          path: 'categories',
          name: 'categories',
          component: () => import('@/pages/categories/UCCategories.vue'),
        },
        {
          path: 'category/:category',
          name: 'subCategories',
          component: () => import('@/pages/categories/UCCategories.vue'),
        },
        {
          path: 'product',
          children: [
            {
              name: 'product-list',
              path: '',
              component: () => import('@/pages/product/UCProductsList.vue'),
            },
            {
              path: 'new-product',
              name: 'formProduct',
              component: () => import('@/pages/product/UCFormProduct.vue'),
            },
            {
              path: 'edit-product/:id',
              name: 'editFormProduct',
              component: () => import('@/pages/product/UCFormProduct.vue'),
            },
          ],
        },
        {
          path: 'purchase',
          name: 'purchase',
          component: () => import('@/pages/purchases/UCPurchases.vue'),
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'login',
          component: () => import('@/pages/auth/UCLogin.vue'),
        },
        {
          path: 'register',
          component: () => import('@/pages/auth/UCRegister.vue'),
        },
        {
          path: '/:pathMatch(.*)*',
          component: () => import('../pages/[...all].vue'),
        },
      ],
    },
  ],
})

export default router
