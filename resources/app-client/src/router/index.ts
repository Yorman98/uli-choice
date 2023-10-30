import { createRouter, createWebHistory } from 'vue-router'
import { authAdmin, authClient, authPublic } from '@/router/middlewares/auth'

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
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'attributes/list/:id',
          name: 'attributesList',
          component: () => import('@/pages/attributes/UCAttributesList.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'categories',
          name: 'categories',
          component: () => import('@/pages/categories/UCCategories.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'users',
          name: 'users',
          meta: {
            middleware: [authAdmin],
          },
          component: () => import('@/pages/users/UCUsers.vue'),
        },
        {
          path: 'category/:category',
          name: 'subCategories',
          component: () => import('@/pages/categories/UCCategories.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'product-list',
          children: [
            {
              name: 'product-list',
              path: '',
              component: () => import('@/pages/product/UCProductsList.vue'),
              meta: {
                middleware: [authAdmin],
              },
            },
            {
              path: 'new-product',
              name: 'formProduct',
              component: () => import('@/pages/product/UCFormProduct.vue'),
              meta: {
                middleware: [authAdmin],
              },
            },
            {
              path: 'edit-product/:id',
              name: 'editFormProduct',
              component: () => import('@/pages/product/UCFormProduct.vue'),
              meta: {
                middleware: [authAdmin],
              },
            },
          ],
        },
        {
          path: 'purchase',
          name: 'purchase',
          component: () => import('@/pages/purchases/UCPurchases.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'providers',
          name: 'providers',
          component: () => import('@/pages/providers/UCProviders.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'budgets',
          children: [
            {
              path: '',
              name: 'budgets',
              meta: {
                middleware: [authAdmin],
              },
              component: () => import('@/pages/budgets/UCBudgets.vue'),
            },
            {
              path: ':id',
              name: 'budgetDetail',
              meta: {
                middleware: [authAdmin],
              },
              component: () => import('@/pages/budgets/UCBudgetDetail.vue'),
            },
          ],
        },
        {
          path: 'payment-methods',
          name: 'paymentMethods',
          component: () => import('@/pages/paymentMethods/UCPaymentMethods.vue'),
          meta: {
            middleware: [authAdmin],
          },
        },
        {
          path: 'orders',
          children: [
            {
              path: '',
              name: 'ordersList',
              component: () => import('@/pages/orders/UCAdminOrdersList.vue'),
              meta: {
                middleware: [authAdmin],
              },
            },
            {
              path: 'add-order',
              name: 'addOrderForm',
              component: () => import('@/pages/orders/UCOrderForm.vue'),
              meta: {
                middleware: [authAdmin],
              },
            },
            {
              path: 'edit-order/:id',
              name: 'editOrderForm',
              component: () => import('@/pages/orders/UCOrderEdit.vue'),
              meta: {
                middleware: [authAdmin],
              },
            },
          ],
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank.vue'),
      children: [
        {
          path: 'products',
          children: [
            {
              name: 'products',
              path: '',
              component: () => import('@/pages/product/UCProductsCard.vue'),
              meta: {
                middleware: [authClient],
              },
            },
            {
              path: ':id',
              name: 'product',
              component: () => import('@/pages/product/UCSingleProduct.vue'),
              meta: {
                middleware: [authClient],
              },
            },
          ],
        },
        {
          path: 'cart',
          name: 'cartPage',
          component: () => import('@/pages/cart/UCCartPage.vue'),
          meta: {
            middleware: [authClient],
          },
        },
        {
          path: 'my-budgets',
          children: [
            {
              path: '',
              name: 'budgets2',
              meta: {
                middleware: [authClient],
              },
              component: () => import('@/pages/budgets/UCBudgetsByClient.vue'),
            },
            {
              path: ':id',
              name: 'budgetDetail2',
              meta: {
                middleware: [authClient],
              },
              component: () => import('@/pages/budgets/UCBudgetDetailByClient.vue'),
            },
          ],
        },
        {
          path: 'my-orders',
          children: [
            {
              path: '',
              name: 'orders2',
              meta: {
                middleware: [authClient],
              },
              component: () => import('@/pages/orders/UCOrderListyClient.vue'),
            },
            {
              path: ':id',
              name: 'orderDetail2',
              meta: {
                middleware: [authClient],
              },
              component: () => import('@/pages/orders/UCOrderDetailByClient.vue'),
            },
          ],
        },
      ],
    },
    {
      path: '/',
      component: () => import('../layouts/blank2.vue'),
      children: [
        {
          path: 'login',
          name: 'login',
          component: () => import('@/pages/auth/UCLogin.vue'),
          meta: {
            middleware: [authPublic],
          },
        },
        {
          path: 'register',
          component: () => import('@/pages/auth/UCRegister.vue'),
          meta: {
            middleware: [authPublic],
          },
        },
        {
          path: '/:pathMatch(.*)*',
          name: 'error',
          component: () => import('../pages/[...all].vue'),
          meta: {
            middleware: [authPublic],
          },
        },
      ],
    },
  ],
})

function nextFactory(context: any, middleware: any, index: any) {
  const subsequentMiddleware = middleware[index]
  if (!subsequentMiddleware)
    return context.next

  return (...parameters: any) => {
    context.next(...parameters)

    const nextMiddleware = nextFactory(context, middleware, index + 1)

    subsequentMiddleware({ ...context, next: nextMiddleware })
  }
}
router.beforeEach((to: any, from: any, next: any) => {
  if (to.meta.middleware) {
    const middleware = Array.isArray(to.meta.middleware)
      ? to.meta.middleware
      : [to.meta.middleware]

    const context = {
      from,
      next,
      router,
      to,
    }

    const nextMiddleware = nextFactory(context, middleware, 1)

    return middleware[0]({ ...context, next: nextMiddleware })
  }
})

export default router
