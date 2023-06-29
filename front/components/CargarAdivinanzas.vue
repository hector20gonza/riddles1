<template>
  <div>
    <h2>Adivinanzas</h2>

    <table class="table">
      <thead>
        <tr>
          <th>Pregunta</th>
          <th>Respuesta</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody>
        <tr v-for="adivinanza in displayedAdivinanzas" :key="adivinanza.id">
          <td>{{ adivinanza.pregunta}}</td>
          <td>{{adivinanza.respuesta}}</td>
          <td>
            <button class="btn btn-primary btn-sm" @click="editItem(adivinanza)">Editar</button>
            <button class="btn btn-danger btn-sm" @click="deleteItem(adivinanza)">Eliminar</button>
         </td>
        </tr>
      </tbody>
    </table>

    <nav v-if="totalPages > 1">
      <ul class="pagination">
        <li class="page-item" :class="{ 'disabled': currentPage === 1 }">
          <a class="page-link" href="#" @click="prevPage">&laquo;</a>
        </li>
        <li class="page-item" v-for="page in totalPages" :key="page" :class="{ 'active': currentPage === page }">
          <a class="page-link" href="#" @click="gotoPage(page)">{{ page }}</a>
        </li>
        <li class="page-item" :class="{ 'disabled': currentPage === totalPages }">
          <a class="page-link" href="#" @click="nextPage">&raquo;</a>
        </li>
      </ul>
    </nav>

    <div class="mt-3">
      <button class="btn btn-success" @click="showAddDialog">Agregar Adivinanza</button>
    </div>
   
 <!--   modal para agregar/editar adivinanza -->
     <div class="modal" :class="{ 'd-block': dialog }">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">{{ formTitle }}</h5>
            <button type="button" class="close" @click="close">&times;</button>
          </div>
          <div class="modal-body">
            <div class="form-group">
              <label for="preguntaInput">Pregunta</label>
              <input type="text" class="form-control" id="preguntaInput" v-model="editedItem.pregunta">
            </div>
            <div class="form-group">
              <label for="respuestaInput">Respuesta</label>
              <input type="text" class="form-control" id="respuestaInput" v-model="editedItem.respuesta">
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" @click="close">Cancelar</button>
            <button type="button" class="btn btn-primary" @click="save">Guardar</button>
          </div>
        </div>
      </div>
    </div>

  </div>
</template>

<script>
export default {
  data() {
    return {
      adivinanzas: [],
      currentPage: 1,
      perPage:0,
      dialog: false,
      formTitle: '',
      editedIndex: -1,
      editedItem: {
        pregunta: '',
        respuesta: '',
      },
      defaultItem: {
        pregunta: '',
        respuesta: '',
      },

    };
  },
  created() {
    this.fetchAdivinanzas();
  },
  computed: {
   
    totalPages() {
      return Math.ceil(this.adivinanzas.length / this.perPage);
    },
    displayedAdivinanzas() {
      const startIndex = (this.currentPage - 1) * this.perPage;
      const endIndex = startIndex + this.perPage;
      return this.adivinanzas.slice(startIndex, endIndex);
    },
   
  },
 
  methods: {
    fetchAdivinanzas() {
      // Realizar solicitud GET al servidor para obtener las adivinanzas
      this.$axios
        .get('/api/adivinanzas')
        .then(response => {
          this.adivinanzas = response.data;
          this.perPage = 5;
        })
        .catch(error => {
          console.error(error);
        });
    },
    prevPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
        
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
       
       
      }
    },
    gotoPage(page) {
      this.currentPage = page;
      
    },
    
    close() {
      this.dialog = false;
      this.editedItem = Object.assign({}, this.defaultItem);
      this.editedIndex = -1;
    },
    showAddDialog() {
      this.formTitle = 'Agregar Adivinanza';
      this.editedItem = Object.assign({}, this.defaultItem);
      this.dialog = true;
    },
    save() {
      if (!this.editedItem.pregunta || !this.editedItem.respuesta) {
        this.$swal("Error", "Por favor completa todos los campos", "error");
          return; 
        }

      if (this.editedIndex > -1) {
        // Editar adivinanza existente
        const adivinanzaId = this.adivinanzas[this.editedIndex].id;
        // Realizar solicitud PUT al servidor para actualizar la adivinanza
        this.$axios
          .put(`/api/adivinanzas/${adivinanzaId}`, this.editedItem)
          .then(() => {
            this.fetchAdivinanzas();
            this.$swal.fire(
                   'Bien hecho!',
                   'Registro Actualizado!',
                   'success'     
                   )
            this.close();
          })
          .catch(error => {
            console.error(error);
          });
      } else {
        // Agregar nueva adivinanza
        // Realizar solicitud POST al servidor para agregar la adivinanza
        this.$axios
          .post('/api/adivinanzas', this.editedItem)
          .then(() => {
            this.fetchAdivinanzas();
            this.$swal.fire(
                            'Buen trabajo!',
                            'Registro Exitoso!',
                            'success'
                             )
            this.close();
          })
          .catch(error => {
            console.error(error);
          });
      }
    },
    editItem(item) {
      this.editedIndex = this.adivinanzas.indexOf(item);
      this.editedItem = Object.assign({}, item);
      this.dialog = true;
    },
    deleteItem(item) {
      // Realizar solicitud DELETE al servidor para eliminar la adivinanza
      const adivinanzaId = item.id;
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
            this.$axios.$delete('/api/adivinanzas/{adivinanza}', {params: {'id':adivinanzaId}})
            .then(() => {
              this.$swal.fire(
                'Elimado!',
                'El archivo ha sido eliminado.',
                'success'
              )
              this.fetchAdivinanzas();
             })
            .catch(error => {
             console.error(error);
             this.$swal("Error", error);
            });
          
 
          }
        }) 
        
    },
    
  }
};
</script>
