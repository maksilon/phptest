<template>
    <div>
      <h1>Reset lozinke</h1>
      <form @submit.prevent="resetPassword">
        <label for="email">Unesite email:</label>
        <input id="email" type="email" v-model="email" required />
        <button type="submit">Pošalji link za reset lozinke</button>
      </form>
      <p v-if="message">{{ message }}</p>
      <p v-if="errorMessage" style="color:red;">{{ errorMessage }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'AdminReset',
    data() {
      return {
        email: '',
        message: '',
        errorMessage: ''
      }
    },
    methods: {
      resetPassword() {
        axios.post('http://localhost:8080/api/adminReset.php', { email: this.email })
          .then(response => {
            if (response.data.success) {
              this.message = response.data.message;
            } else {
              this.errorMessage = response.data.error;
            }
          })
          .catch(error => {
            console.error("Greška pri resetovanju lozinke:", error);
            this.errorMessage = "Došlo je do greške.";
          });
      }
    }
  }
  </script>
  