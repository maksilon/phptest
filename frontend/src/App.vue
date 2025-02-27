<template>
  <div id="app">
    <h1>Moja Aplikacija</h1>
    <registration-form />
    <hr />
    <admin-dashboard />
  </div>
</template>

<script>
import RegistrationForm from './components/RegistrationForm.vue';
import AdminDashboard from './components/AdminDashboard.vue';

export default {
  name: 'App',
  components: {
    RegistrationForm,
    AdminDashboard
  }
}
</script>
