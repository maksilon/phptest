import { createRouter, createWebHistory } from 'vue-router';
import RegistrationForm from '../components/RegistrationForm.vue';
import AdminDashboard from '../components/AdminDashboard.vue';
import AdminLogin from '../components/AdminLogin.vue';
import AdminReset from '../components/AdminReset.vue';
import AdminResetConfirm from '../components/AdminResetConfirm.vue';

const routes = [
  {
    path: '/',
    name: 'Home',
    component: RegistrationForm
  },
  {
    path: '/admin',
    name: 'AdminDashboard',
    component: AdminDashboard,
    meta: { requiresAuth: true }
  },
  {
    path: '/admin/login',
    name: 'AdminLogin',
    component: AdminLogin
  },
  {
    path: '/admin/reset',
    name: 'AdminReset',
    component: AdminReset
  },
  {
    path: '/admin/reset/confirm',
    name: 'AdminResetConfirm',
    component: AdminResetConfirm
  }
];

const router = createRouter({
  history: createWebHistory(),
  routes
});

// Simple route guard za admin rute
router.beforeEach((to, from, next) => {
  if (to.meta.requiresAuth) {
    const token = localStorage.getItem('adminToken');
    if (!token) {
      return next('/admin/login');
    }
  }
  next();
});

export default router;
