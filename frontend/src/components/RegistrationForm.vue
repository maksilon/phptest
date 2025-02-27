<template>
    <form @submit.prevent="submitForm">
      <input v-model="form.full_name" placeholder="Ime i prezime*" required />
      <input type="date" v-model="form.datum_rodjenja" placeholder="Datum rođenja*" required />
      <input v-model="form.kontakt_telefon" placeholder="Kontakt telefon*" required />
      <input type="email" v-model="form.email" placeholder="E-mail adresa*" required />
      <input v-model="form.motocikl_i_zapremina" placeholder="Motocikl i zapremina*" required />
      <input v-model="form.broj_vozacke_dozvole" placeholder="Broj vozačke dozvole*" required />
      <input type="date" v-model="form.vozacka_dozvola_vazi_do" placeholder="Vozačka dozvola važi do*" required />
      <!-- Pretpostavljamo da ćemo učitavati termine iz API-ja -->
      <select v-model="form.termin_id" required>
        <option v-for="termin in termini" :key="termin.id" :value="termin.id">
          {{ termin.naziv }}
        </option>
      </select>
      <input type="number" v-model="form.startni_broj" placeholder="Startni broj*" required />
      <select v-model="form.takmicarska_licenca" required>
        <option :value="1">Da</option>
        <option :value="0">Ne</option>
      </select>
      <input v-model="form.grupa" placeholder="Grupa u kojoj ću voziti*" required />
      <button type="submit">Pošalji prijavu</button>
    </form>
  </template>
  
  <script>
  import axios from 'axios';
  
  export default {
    data() {
      return {
        form: {
          full_name: '',
          datum_rodjenja: '',
          kontakt_telefon: '',
          email: '',
          motocikl_i_zapremina: '',
          broj_vozacke_dozvole: '',
          vozacka_dozvola_vazi_do: '',
          termin_id: '',
          startni_broj: '',
          takmicarska_licenca: 0,
          grupa: '',
        },
        termini: [] // Očekujemo da učitamo dostupne termine
      }
    },
    created() {
      axios.get('http://localhost:8080/api/termini.php')
        .then(response => {
          this.termini = response.data;
        })
        .catch(error => {
          console.error("Greška pri učitavanju termina:", error);
        });
    },
    methods: {
      submitForm() {
        axios.post('http://localhost:8080/api/prijava.php', this.form)
          .then(response => {
            console.log('Odgovor sa servera:', response.data);
            // Prikazi obaveštenje korisniku, očisti formu, itd.
          })
          .catch(error => {
            console.error('Greška prilikom slanja forme:', error);
          });
      }
    }
  }
  </script>
  
  <style lang="scss">
  form {
    display: flex;
    flex-direction: column;
    gap: 1rem;
  }
  </style>
  