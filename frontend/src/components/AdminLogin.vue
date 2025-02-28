<template>
    <div>
      <h1>Admin Login</h1>
      <form @submit.prevent="login">
        <label for="username">Korisničko ime</label>
        <input id="username" v-model="username" required />
        
        <label for="password">Lozinka</label>
        <input id="password" type="password" v-model="password" required />
  
        <button type="submit">Prijavi se</button>
      </form>
      <p v-if="errorMessage" style="color:red;">{{ errorMessage }}</p>
      <router-link to="/admin/reset">Resetuj lozinku</router-link>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'AdminLogin',
    data() {
      return {
        username: '',
        password: '',
        errorMessage: ''
      }
    },
    methods: {
      login() {
        axios.post('http://localhost:8080/api/adminLogin.php', {
          username: this.username,
          password: this.password
        })
        .then(response => {
          if (response.data.success) {
            localStorage.setItem('adminToken', response.data.token);
            this.$router.push('/admin');
          } else if (response.data.error) {
            this.errorMessage = response.data.error;
          }
        })
        .catch(error => {
          console.error("Greška pri logovanju admina:", error);
          this.errorMessage = "Došlo je do greške pri logovanju.";
        });
      }
    }
  }
  </script>
  