<!-- frontend/src/App.vue -->
<template>
  <div id="app">
    <RegistrationForm />
  </div>
</template>

<script>
import RegistrationForm from './components/RegistrationForm.vue'

export default {
  name: 'App',
  components: {
    RegistrationForm
  }
}
</script>
