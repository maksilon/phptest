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
      <table v-if="registrations.length">
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
            <td>{{ reg.potvrdeno == 1 ? 'DA' : 'NE' }}</td>
            <td>
              <button
                @click="confirmPayment(reg.id)"
                :disabled="reg.potvrdeno == 1"
              >
                Potvrdi uplatu
              </button>
            </td>
          </tr>
        </tbody>
      </table>
      <div v-else>
        <p>Nema prijava.</p>
      </div>
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
            // Resetuj formu
            this.termin = { naziv: '', opis: '', datum: '' };
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
            this.fetchRegistrations();
          })
          .catch(error => {
            console.error('Greška prilikom potvrde uplate:', error);
          });
      }
    }
  };
  </script>
  