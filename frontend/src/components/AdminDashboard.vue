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
            <li class="breadcrumb-item active" aria-current="page">Registracije</li>
          </ol>
          <ul class="navbar-nav ms-auto mt-2 mt-lg-0">
            <li class="nav-item">
              <a href="#" class="nav-link" @click.prevent="logout">Logout</a>
            </li>
          </ul>
        </div>
      </nav>

      <div class="container-fluid mt-4">
        <!-- Search input -->
        <div class="mb-3">
          <input type="text" v-model="searchQuery" class="form-control" placeholder="Pretraži po broju vozačke dozvole...">
        </div>
        <!-- Registrations table -->
        <table class="table table-striped table-dark">
          <thead>
            <tr>
              <th>ID</th>
              <th>Ime i prezime</th>
              <th>Email</th>
              <th @click="toggleSort('broj_vozacke_dozvole')" style="cursor: pointer;">
                Broj dozvole
                <span v-if="sortField==='broj_vozacke_dozvole' && sortOrder==='asc'">&#9650;</span>
                <span v-else-if="sortField==='broj_vozacke_dozvole' && sortOrder==='desc'">&#9660;</span>
              </th>
              <th @click="toggleSort('created_at')" style="cursor: pointer;">
                Datum prijave
                <span v-if="sortField==='created_at' && sortOrder==='asc'">&#9650;</span>
                <span v-else-if="sortField==='created_at' && sortOrder==='desc'">&#9660;</span>
              </th>
              <th>Potvrđeno</th>
              <th>Akcija</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="reg in sortedRegistrations" :key="reg.id">
              <td>{{ reg.id }}</td>
              <td>{{ reg.full_name }}</td>
              <td>{{ reg.email }}</td>
              <td>{{ reg.broj_vozacke_dozvole }}</td>
              <td>{{ reg.created_at }}</td>
              <td>{{ reg.potvrdeno == 1 ? 'DA' : 'NE' }}</td>
              <td>
                <button class="btn btn-sm btn-success me-1" @click="confirmPayment(reg.id)" :disabled="reg.potvrdeno == 1">Potvrdi</button>
                <button class="btn btn-sm btn-warning me-1" @click="openEditModal(reg)">Edit</button>
                <button class="btn btn-sm btn-info" @click="openViewModal(reg)">Prikaži</button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>

    <!-- Edit Modal -->
    <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title" id="editModalLabel">Izmeni registraciju</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="updateRegistration">
              <div class="mb-3">
                <label class="form-label">Ime i prezime*</label>
                <input v-model="editForm.full_name" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Datum rođenja*</label>
                <input v-model="editForm.datum_rodjenja" type="date" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Kontakt telefon*</label>
                <input v-model="editForm.kontakt_telefon" type="text" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">E-mail adresa*</label>
                <input v-model="editForm.email" type="email" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Motocikl i zapremina*</label>
                <input v-model="editForm.motocikl_i_zapremina" type="text" class="form-control" required />
              </div>
              <!-- Broj vozačke dozvole se NE menja -->
              <div class="mb-3">
                <label class="form-label">Vozačka dozvola važi do*</label>
                <input v-model="editForm.vozacka_dozvola_vazi_do" type="date" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Startni broj*</label>
                <input v-model="editForm.startni_broj" type="number" class="form-control" required />
              </div>
              <div class="mb-3">
                <label class="form-label">Posedujem takmičarsku licencu*</label>
                <select v-model="editForm.takmicarska_licenca" class="form-select" required>
                  <option :value="1">Da</option>
                  <option :value="0">Ne</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label">Grupa u kojoj ću voziti*</label>
                <input v-model="editForm.grupa" type="text" class="form-control" required />
              </div>
              <button type="submit" class="btn btn-primary">Sačuvaj izmene</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- View Modal -->
    <div class="modal fade" id="viewModal" tabindex="-1" aria-labelledby="viewModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content bg-dark text-white">
          <div class="modal-header">
            <h5 class="modal-title" id="viewModalLabel">Detalji registracije</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <p><strong>Ime i prezime:</strong> {{ viewData.full_name }}</p>
            <p><strong>Datum rođenja:</strong> {{ viewData.datum_rodjenja }}</p>
            <p><strong>Kontakt telefon:</strong> {{ viewData.kontakt_telefon }}</p>
            <p><strong>E-mail:</strong> {{ viewData.email }}</p>
            <p><strong>Motocikl i zapremina:</strong> {{ viewData.motocikl_i_zapremina }}</p>
            <p><strong>Broj vozačke dozvole:</strong> {{ viewData.broj_vozacke_dozvole }}</p>
            <p><strong>Vozačka dozvola važi do:</strong> {{ viewData.vozacka_dozvola_vazi_do }}</p>
            <p><strong>Startni broj:</strong> {{ viewData.startni_broj }}</p>
            <p><strong>Takmičarska licenca:</strong> {{ viewData.takmicarska_licenca == 1 ? 'Da' : 'Ne' }}</p>
            <p><strong>Grupa:</strong> {{ viewData.grupa }}</p>
            <p><strong>Datum prijave:</strong> {{ viewData.created_at }}</p>
            <p><strong>Potvrđeno:</strong> {{ viewData.potvrdeno == 1 ? 'DA' : 'NE' }}</p>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
import axios from 'axios';
import { Modal } from 'bootstrap';

export default {
  name: 'AdminDashboard',
  data() {
    return {
      registrations: [],
      searchQuery: "",
      sortField: "created_at",
      sortOrder: "desc",
      sidebarVisible: true,
      editForm: {},
      viewData: {}
    };
  },
  created() {
    this.fetchRegistrations();
  },
  computed: {
    filteredRegistrations() {
      if (!Array.isArray(this.registrations)) return [];
      return this.registrations.filter(reg =>
        (reg.broj_vozacke_dozvole ?? '').toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
    sortedRegistrations() {
      return this.filteredRegistrations.sort((a, b) => {
        let fieldA = a[this.sortField] || '';
        let fieldB = b[this.sortField] || '';
        if (this.sortField === "created_at") {
          return this.sortOrder === "asc" ? fieldA.localeCompare(fieldB) : fieldB.localeCompare(fieldA);
        } else {
          return this.sortOrder === "asc" ? fieldA.toString().localeCompare(fieldB.toString(), undefined, { numeric: true }) : fieldB.toString().localeCompare(fieldA.toString(), undefined, { numeric: true });
        }
      });
    }
  },
  methods: {
    fetchRegistrations() {
      axios.get('http://localhost:8080/api/registrations.php')
        .then(response => {
          console.log("Registrations response:", response.data);
          this.registrations = Array.isArray(response.data) ? response.data : [];
        })
        .catch(error => {
          console.error('Greška pri učitavanju registracija:', error);
          this.registrations = [];
        });
    },
    confirmPayment(id) {
      axios.post('http://localhost:8080/api/potvrdi.php', { id })
        .then(response => {
          console.log('Odgovor servera:', response.data);
          this.fetchRegistrations();
        })
        .catch(error => {
          console.error('Greška pri potvrdi uplate:', error);
        });
    },
    toggleSort(field) {
      if (this.sortField === field) {
        this.sortOrder = this.sortOrder === "asc" ? "desc" : "asc";
      } else {
        this.sortField = field;
        this.sortOrder = "asc";
      }
    },
    logout() {
      localStorage.removeItem('adminToken');
      this.$router.push('/admin/login');
    },
    toggleSidebar() {
      this.sidebarVisible = !this.sidebarVisible;
      const sidebar = document.getElementById('sidebar-wrapper');
      sidebar.style.display = this.sidebarVisible ? "block" : "none";
    },
    openEditModal(registration) {
      // Kopiraj podatke u editForm (bez broja vozačke dozvole)
      this.editForm = { ...registration };
      delete this.editForm.broj_vozacke_dozvole;
      const modalEl = document.getElementById('editModal');
      const modal = new Modal(modalEl);
      modal.show();
    },
    updateRegistration() {
      axios.post('http://localhost:8080/api/updateRegistration.php', this.editForm)
        .then(response => {
          console.log("Update response:", response.data);
          if (response.data.success) {
            alert("Podaci ažurirani.");
            this.fetchRegistrations();
            const modalEl = document.getElementById('editModal');
            const modal = Modal.getInstance(modalEl);
            modal.hide();
          } else {
            alert("Greška: " + response.data.error);
          }
        })
        .catch(error => {
          console.error("Greška pri ažuriranju registracije:", error);
        });
    },
    openViewModal(registration) {
      this.viewData = { ...registration };
      const modalEl = document.getElementById('viewModal');
      const modal = new Modal(modalEl);
      modal.show();
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
