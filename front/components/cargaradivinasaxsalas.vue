<template>
  <div class="container">
    <h2>Cargar Adivinanzas a Salas</h2>

    <div class="form-group">
      <label for="salaSelect">Seleccionar Sala:</label>
      <select id="salaSelect" class="form-control" v-model="selectedSala" @change="cargarAdivinanzasAsignadas">
        <option value="">Seleccione una sala</option>
        <option v-for="sala in salas" :key="sala.id" :value="sala.id">{{ sala.nombre_sala }}</option>
      </select>
    </div>

    <h3>Adivinanzas Asignadas:</h3>
    <table class="table">
      <thead>
        <tr>
          <th>ID</th>
          <th>Adivinanza</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="adivinanza in adivinanzasAsignada" :key="adivinanza.id">
          <td>{{ adivinanza.id }}</td>
          <td>{{ adivinanza.pregunta }}</td>
          <td>
            <button class="btn btn-danger btn-sm" @click="deleteItem(adivinanza)">Eliminar</button>
          </td>
        </tr>
      </tbody>
    </table>
   
    <h3>Adivinanzas Disponibles:</h3>
    <div v-if="selectedSala">
      <div v-for="adivinanza in adivinanzas" :key="adivinanza.id">
        <input type="checkbox" :id="adivinanza.id" :value="adivinanza.id" v-model="adivinanzasSeleccionadas" />
        <label :for="adivinanza.id">{{ adivinanza.pregunta }}</label>
      </div>
    </div>

    <div class="mt-3">
      <button class="btn btn-success" @click="guardarAdivinanzas">Agregar Adivinanza</button>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      salas: [],
      adivinanzas: [],
      selectedSala: "",
      adivinanzasSeleccionadas: [],
      adivinanzasAsignada: [],
      dialog: false,
      formTitle: "",
      editedIndex: -1,
      editedItem: {
        pregunta: "",
        respuesta: "",
      },
      defaultItem: {
        pregunta: "",
        respuesta: "",
      },
    };
  },
  created() {
    this.fetchAdivinanzas();
    this.fetchSalas();
  },
  computed: {
    adivinanzasDisponibles() {
      const sala = this.salas.find((s) => s.id === this.selectedSala);
      if (sala) {
        return this.adivinanzas.filter((a) => !sala.adivinanzas.includes(a.id));
      }
      return [];
    },
    
  },
  methods: {
    cargarAdivinanzasAsignadas() {
      this.adivinanzasSeleccionadas = [];
      this.adivinanzasAsignada = "";

      this.$axios
        .get("/api/salasxadvinanzas", { params: { roomId: this.selectedSala } })
        .then((response) => {
          this.adivinanzasAsignada = response.data;
        })
        .catch((error) => {
          if (error.response.status === 404) {
            this.$swal("Los Siento", "De Momento la sala no posee adivinanzas", "error");
          }
        });
    },
    guardarAdivinanzas() {
      let adivinanzasNuevas = [];
      const adivinanzasSeleccionadas = this.adivinanzasSeleccionadas;
      if (this.adivinanzasAsignada.length < 1) {
        adivinanzasNuevas = adivinanzasSeleccionadas;
      } else {
        adivinanzasNuevas = adivinanzasSeleccionadas.filter(
          (a) => !this.adivinanzasAsignada.map((aa) => aa.id).includes(a)
        );
      }
      this.$axios
        .post("/api/salas/adivinanzas", {
          salaId: this.selectedSala,
          adivinanzasSeleccionadas: adivinanzasNuevas,
        })
        .then(() => {
          this.cargarAdivinanzasAsignadas();
          this.$swal.fire(
                            'Buen trabajo!',
                            'Registro Exitoso!',
                            'success'
                             )
          this.close();
        })
        .catch((error) => {
          console.error(error);
        });
    },

    fetchAdivinanzas() {
      this.$axios
        .get("/api/adivinanzas")
        .then((response) => {
          this.adivinanzas = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    fetchSalas() {
      this.$axios
        .get("/api/salas")
        .then((response) => {
          this.salas = response.data;
        })
        .catch((error) => {
          console.error(error);
        });
    },
    close() {
      this.dialog = false;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
    },

    editItem(item) {
      this.editedIndex = this.adivinanzas.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
    // Realizar solicitud DELETE al servidor para eliminar la adivinanza
      const salaId = item.id;
        this.$swal.fire({
          title: 'Estas Seguro?',
          text: "No podras revertir esto!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Si, Eliminalo!'
        }).then((result) => {
        if (result.isConfirmed) {
          this.$axios
        .$delete("/api/salas/adivinanzas/{adivinanza}", {
          params: { id: adivinanzaId, sala_id: this.selectedSala },
        })
            .then(() => {
              this.$swal.fire(
                'Elimado!',
                'El archivo ha sido eliminado.',
                'success'
              )
              this.cargarAdivinanzasAsignadas();
             })
            .catch(error => {
             console.error(error);
             this.$swal("Error", error);
            });
           }
          }) 
    },
    
  },
};
</script>

<style scoped>


.form-group {
  margin-bottom: 1rem;
}

.table {
  width: 100%;
  margin-bottom: 1rem;
  color: #212529;
}

.btn {
  display: inline-block;
  font-weight: 400;
  color: #212529;
  text-align: center;
  vertical-align: middle;
  user-select: none;
  background-color: transparent;
  border: 1px solid transparent;
  padding: 0.375rem 0.75rem;
  font-size: 1rem;
  line-height: 1.5;
  border-radius: 0.25rem;
}

.btn-success {
  color: #fff;
  background-color: #28a745;
  border-color: #28a745;
}

.btn-danger {
  color: #fff;
  background-color: #dc3545;
  border-color: #dc3545;
}

.mt-3 {
  margin-top: 1rem;
}

.text-center {
  text-align: center;
}

</style>
