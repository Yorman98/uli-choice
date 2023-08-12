import { createRouter, createWebHistory } from 'vue-router'

const router = createRouter({
  history: createWebHistory(import.meta.env.BASE_URL),
  routes: [
    { path: '/', redirect: '/dashboard' },
    {
      path: '/',
      component: () => import('../layouts/default.vue'),
      children: [
        {
          path: 'dashboard',
          name: 'adminDashboard',
          component: () => import('../pages/dashboard.vue'),
        },
        {
          path: 'account-settings',
          component: () => import('../pages/account-settings.vue'),
        },
        {
          path: 'typography',
          component: () => import('../pages/typography.vue'),
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
          path: 'admin/users',
          name: 'users',
          component: () => import('@/pages/admin/UCUsers.vue'),
        },
        {
          path: 'admin/users/create',
          name: 'createUsers',
          component: () => import('@/pages/admin/UCCreateUser.vue'),
        },
        {
          path: 'admin/users/edit/:id',
          name: 'editUsers',
          component: () => import('@/pages/admin/UCCreateUser.vue'),
        }
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'login',
          component: () => import('../pages/login.vue'),
        },
        {
          path: 'register',
          component: () => import('../pages/register.vue'),
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
