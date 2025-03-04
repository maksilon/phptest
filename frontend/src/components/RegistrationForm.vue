<template>
  <form @submit.prevent="submitForm" class="container mt-4">
    <div class="mb-3">
      <label class="form-label">Ime i prezime*</label>
      <input v-model="form.full_name" type="text" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Datum rođenja*</label>
      <input v-model="form.datum_rodjenja" type="date" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Kontakt telefon*</label>
      <input v-model="form.kontakt_telefon" type="text" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">E-mail adresa*</label>
      <input v-model="form.email" type="email" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Motocikl i zapremina*</label>
      <input v-model="form.motocikl_i_zapremina" type="text" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Broj vozačke dozvole*</label>
      <input v-model="form.broj_vozacke_dozvole" type="text" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Vozačka dozvola važi do*</label>
      <input v-model="form.vozacka_dozvola_vazi_do" type="date" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Izaberite termin*</label>
      <select v-model="form.termin_id" class="form-select" required>
        <option disabled value="">Izaberi termin</option>
        <option v-for="termin in termini" :key="termin.id" :value="termin.id">
          {{ termin.naziv }} ({{ termin.iznos }})
        </option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Startni broj*</label>
      <input v-model="form.startni_broj" type="number" class="form-control" required />
    </div>
    <div class="mb-3">
      <label class="form-label">Posedujem takmičarsku licencu*</label>
      <select v-model="form.takmicarska_licenca" class="form-select" required>
        <option :value="1">Da</option>
        <option :value="0">Ne</option>
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Grupa u kojoj ću voziti*</label>
      <input v-model="form.grupa" type="text" class="form-control" required />
    </div>
    <!-- Dodavanje obaveznih checkboxova -->
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" v-model="form.confirmHealth" id="confirmHealth" required>
      <label class="form-check-label" for="confirmHealth">
        Potvrđujem da kao vozač ne bolujem od dijabetesa, epilepsije i sličnih problema koji mogu da ugroze druge učesnike*
      </label>
    </div>
    <div class="form-check mb-3">
      <input class="form-check-input" type="checkbox" v-model="form.confirmResponsibility" id="confirmResponsibility" required>
      <label class="form-check-label" for="confirmResponsibility">
        Potvrđujem da vozim na sopstvenu odgovornost kako materijalnu tako i zdravstvenu i u slučaju nezgode neću utuživati MSS, organizatora ili direktora takmičenja*
      </label>
    </div>
    <button type="submit" class="btn btn-primary">Pošalji prijavu</button>
  </form>
</template>

<script>
import axios from 'axios';

export default {
  name: 'RegistrationForm',
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
        confirmHealth: false,
        confirmResponsibility: false
      },
      termini: []
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
          console.log("Odgovor servera:", response.data);
          if (response.data.success) {
            alert("Uspešno ste se prijavili! Proverite email za uputstva o plaćanju.");
            this.form = {
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
              confirmHealth: false,
              confirmResponsibility: false
            };
          } else {
            alert("Greška: " + response.data.error);
          }
        })
        .catch(error => {
          console.error("Greška pri slanju prijave:", error);
        });
    }
  }
}
</script>

<style scoped lang="scss">
@import 'bootstrap/scss/bootstrap';

.container {
  max-width: 600px;
}
</style>
