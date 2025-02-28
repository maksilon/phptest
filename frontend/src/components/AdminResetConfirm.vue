<template>
    <div>
      <h1>Potvrdi reset lozinke</h1>
      <form @submit.prevent="confirmReset">
        <label for="newPassword">Nova lozinka:</label>
        <input id="newPassword" type="password" v-model="newPassword" required />
        <button type="submit">Resetuj lozinku</button>
      </form>
      <p v-if="message">{{ message }}</p>
      <p v-if="errorMessage" style="color:red;">{{ errorMessage }}</p>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'AdminResetConfirm',
    data() {
      return {
        newPassword: '',
        message: '',
        errorMessage: ''
      }
    },
    created() {
      // Token se može dobiti iz query parametara
      this.token = this.$route.query.token;
    },
    methods: {
      confirmReset() {
        axios.post('http://localhost:8080/api/adminResetConfirm.php', {
          token: this.token,
          new_password: this.newPassword
        })
        .then(response => {
          if (response.data.success) {
            this.message = response.data.message;
          } else {
            this.errorMessage = response.data.error;
          }
        })
        .catch(error => {
          console.error("Greška pri potvrdi resetovanja:", error);
          this.errorMessage = "Došlo je do greške.";
        });
      }
    }
  }
  </script>
  