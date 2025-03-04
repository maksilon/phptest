<template>
    <div id="wrapper" class="d-flex">
      <!-- Sidebar -->
      <div id="sidebar-wrapper" class="bg-dark border-right">
        <div class="sidebar-heading text-white">MKSpark Admin</div>
        <div class="list-group list-group-flush">
          <router-link to="/admin" class="list-group-item list-group-item-action bg-dark text-white">Dashboard</router-link>
          <router-link to="/admin/termini" class="list-group-item list-group-item-action bg-dark text-white">Termini</router-link>
          <router-link to="/admin/reset" class="list-group-item list-group-item-action bg-dark text-white">Reset Lozinke</router-link>
          <a href="#" @click.prevent="logout" class="list-group-item list-group-item-action bg-dark text-white">Logout</a>
        </div>
      </div>
      <!-- /#sidebar-wrapper -->
  
      <!-- Page Content -->
      <div id="page-content-wrapper" class="w-100">
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark border-bottom">
          <button class="btn btn-primary" @click="toggleSidebar">Toggle Menu</button>
          <div class="collapse navbar-collapse">
            <ol class="breadcrumb mb-0 ms-3">
              <li class="breadcrumb-item">
                <router-link to="/admin" class="text-white">Dashboard</router-link>
              </li>
              <li class="breadcrumb-item active" aria-current="page">Termini</li>
            </ol>
            <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
              <li class="nav-item">
                <a href="#" class="nav-link" @click.prevent="logout">Logout</a>
              </li>
            </ul>
          </div>
        </nav>
        <div class="container-fluid mt-4">
          <button class="btn btn-primary mb-3" @click="openModal">Kreiraj novi termin</button>
  
          <!-- Modal za kreiranje termina -->
          <div class="modal fade" id="terminModal" tabindex="-1" aria-labelledby="terminModalLabel" aria-hidden="true">
            <div class="modal-dialog">
              <div class="modal-content bg-dark text-white">
                <div class="modal-header">
                  <h5 class="modal-title" id="terminModalLabel">Kreiraj novi termin</h5>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                  <form @submit.prevent="createTermin">
                    <div class="mb-3">
                      <label class="form-label">Naziv termina*</label>
                      <input v-model="newTermin.naziv" type="text" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Datum*</label>
                      <input v-model="newTermin.datum" type="datetime-local" class="form-control" required>
                    </div>
                    <div class="mb-3">
                      <label class="form-label">Iznos (Cena)*</label>
                      <input v-model="newTermin.iznos" type="text" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Kreiraj</button>
                  </form>
                </div>
              </div>
            </div>
          </div>
  
          <!-- Lista termina -->
          <h2 class="mt-4">Lista termina</h2>
          <table class="table table-striped table-dark">
            <thead>
              <tr>
                <th>ID</th>
                <th>Naziv</th>
                <th>Datum</th>
                <th>Iznos</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="termin in sortedTermin" :key="termin.id">
                <td>{{ termin.id }}</td>
                <td>{{ termin.naziv }}</td>
                <td>{{ termin.datum }}</td>
                <td>{{ termin.iznos }}</td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </template>
  
  <script>
  import axios from 'axios';
  import { Modal } from 'bootstrap';
  
  export default {
    name: 'Termini',
    data() {
      return {
        termini: [],
        newTermin: {
          naziv: '',
          datum: '',
          iznos: ''
        },
        sortField: 'created_at',
        sortOrder: 'desc',
        sidebarVisible: true
      }
    },
    created() {
      this.fetchTermin();
    },
    computed: {
      sortedTermin() {
        return this.termini.sort((a, b) => {
          let fieldA = a[this.sortField] || '';
          let fieldB = b[this.sortField] || '';
          return this.sortOrder === 'asc' ? fieldA.localeCompare(fieldB) : fieldB.localeCompare(fieldA);
        });
      }
    },
    methods: {
      fetchTermin() {
        axios.get('http://localhost:8080/api/termini.php')
          .then(response => {
            this.termini = response.data;
          })
          .catch(error => {
            console.error("Greška pri učitavanju termina:", error);
          });
      },
      openModal() {
        const modalEl = document.getElementById('terminModal');
        const modal = new Modal(modalEl);
        modal.show();
      },
      createTermin() {
        axios.post('http://localhost:8080/api/termini.php', this.newTermin)
          .then(response => {
            console.log("Odgovor servera:", response.data);
            if (response.data.success) {
              alert("Termin kreiran!");
              this.newTermin = { naziv: '', datum: '', iznos: '' };
              this.fetchTermin();
              const modalEl = document.getElementById('terminModal');
              const modal = Modal.getInstance(modalEl);
              modal.hide();
            } else {
              alert("Greška: " + response.data.error);
            }
          })
          .catch(error => {
            console.error("Greška pri kreiranju termina:", error);
            alert("Greška pri kreiranju termina: " + error.message);
          });
      },
      toggleSidebar() {
        this.sidebarVisible = !this.sidebarVisible;
        const sidebar = document.getElementById('sidebar-wrapper');
        sidebar.style.display = this.sidebarVisible ? "block" : "none";
      },
      logout() {
        localStorage.removeItem('adminToken');
        this.$router.push('/admin/login');
      }
    }
  }
  </script>
  
  <style scoped lang="scss">
  @import 'bootstrap/scss/bootstrap';
  
  #wrapper {
    display: flex;
    width: 100%;
  }
  
  #sidebar-wrapper {
    min-width: 250px;
    max-width: 250px;
  }
  
  #page-content-wrapper {
    width: 100%;
    padding: 0;
  }
  
  .breadcrumb {
    background-color: transparent;
    margin-bottom: 0;
  }
  </style>
  