<template>
    <div>
      <h1>Admin Dashboard - Dodaj Termin</h1>
      <form @submit.prevent="addTermin">
        <input v-model="termin.naziv" placeholder="Naziv termina" required />
        <input v-model="termin.opis" placeholder="Opis" />
        <input v-model="termin.datum" type="datetime-local" required />
        <button type="submit">Dodaj Termin</button>
      </form>
  
      <h1>Lista Prijava</h1>
      <table>
        <thead>
          <tr>
            <th>ID</th>
            <th>Ime i prezime</th>
            <th>Email</th>
            <th>Potvrđeno</th>
            <th>Akcija</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="reg in registrations" :key="reg.id">
            <td>{{ reg.id }}</td>
            <td>{{ reg.full_name }}</td>
            <td>{{ reg.email }}</td>
            <td>{{ reg.potvrdeno === '1' || reg.potvrdeno === 1 ? 'DA' : 'NE' }}</td>
            <td>
              <button
                @click="confirmPayment(reg.id)"
                :disabled="reg.potvrdeno === '1' || reg.potvrdeno === 1"
              >
                Potvrdi uplatu
              </button>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    name: 'AdminDashboard',
    data() {
      return {
        registrations: [],
        termin: {
          naziv: '',
          opis: '',
          datum: ''
        }
      };
    },
    created() {
      this.fetchRegistrations();
    },
    methods: {
      fetchRegistrations() {
        axios
          .get('http://localhost:8080/api/registrations.php')
          .then(response => {
            this.registrations = response.data;
          })
          .catch(error => {
            console.error('Greška pri učitavanju prijava:', error);
          });
      },
      addTermin() {
        axios
          .post('http://localhost:8080/api/dodajTermin.php', this.termin)
          .then(response => {
            console.log('Termin dodat:', response.data);
            // Resetuj polja forme za termin
            this.termin = { naziv: '', opis: '', datum: '' };
            // Opcionalno: obavesti korisnika ili osveži podatke ukoliko ih koristiš drugde
          })
          .catch(error => {
            console.error('Greška prilikom dodavanja termina:', error);
          });
      },
      confirmPayment(id) {
        axios
          .post('http://localhost:8080/api/potvrdi.php', { id })
          .then(response => {
            console.log('Odgovor servera:', response.data);
            // Nakon potvrde, osveži listu prijava
            this.fetchRegistrations();
          })
          .catch(error => {
            console.error('Greška prilikom potvrde uplate:', error);
          });
      }
    }
  };
  </script>
  
  <style scoped lang="scss">
  table {
    border-collapse: collapse;
    width: 100%;
    margin-top: 20px;
  
    th, td {
      border: 1px solid #ccc;
      padding: 8px;
      text-align: left;
    }
  }
  
  form {
    margin-bottom: 20px;
  
    input {
      margin-right: 10px;
      padding: 5px;
    }
  
    button {
      padding: 5px 10px;
    }
  }
  </style>
  