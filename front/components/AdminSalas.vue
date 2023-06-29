<template>
    <div>
      <h2>Salas</h2>
      
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Descripción</th>
            <th>Cantidad de Jugadores</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="sala in salas " :key="sala.id">
            <td>{{ sala.nombre_sala }}</td>
            <td>{{ sala.descripcion }}</td>
            <td>{{ sala.cantidad_jugadores }}</td>
            <td>
              <button class="btn btn-primary btn-sm" @click="editItem(sala)">Editar</button>
              <button class="btn btn-danger btn-sm" @click="deleteItem(sala)">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <div class="mt-3">
        <button class="btn btn-success" @click="showAddDialog">Agregar Sala</button>
      </div>
  
      <!-- Modal para agregar/editar sala -->
      <div class="modal" :class="{ 'd-block': dialog }">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{ formTitle }}</h5>
              <button type="button" class="close" @click="close">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="nombreSalaInput">Nombre</label>
                <input type="text" class="form-control" id="nombreSalaInput" v-model="editedItem.nombre_sala">
              </div>
              <div class="form-group">
                <label for="descripcionInput">Descripción</label>
                <input type="text" class="form-control" id="descripcionInput" v-model="editedItem.descripcion">
              </div>
              <div class="form-group">
                <label for="cantidadJugadoresInput">Cantidad de Jugadores</label>
                <input type="number" class="form-control" id="cantidadJugadoresInput" v-model="editedItem.cantidad_jugadores">
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
  
  import swal from 'sweetalert';
  export default {
    data() {
      return {
        dialog: false,
        formTitle: '',
        editedIndex: -1,
        editedItem: {
          nombre_sala: '',
          descripcion: '',
          cantidad_jugadores: '',
        },
        defaultItem: {
          nombre_sala: '',
          descripcion: '',
          cantidad_jugadores: '',
        },
        salas: [],
      };
    },
    created() {
      this.fetchSalas();
    },
    methods: {
      close() {
        this.dialog = false;
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      },
      showAddDialog() {
        this.formTitle = 'Agregar Sala';
        this.editedItem = Object.assign({}, this.defaultItem);
        this.dialog = true;
      },
      save() {
        if (!this.editedItem.nombre_sala || !this.editedItem.descripcion || !this.editedItem.cantidad_jugadores) {
        this.$swal("Error", "Por favor completa todos los campos", "error");
          return; 
        }
        if (this.editedIndex > -1) {
          // Editar sala existente
          const salaId= this.salas[this.editedIndex].id;
         this.$axios.$put('/api/salas/${sala}',{
             id:salaId,
             nombre_sala:this.editedItem.nombre_sala,
             descripcion:this.editedItem.descripcion,
             cantidad_jugadores:this.editedItem.cantidad_jugadores
            })
            .then(() => {
              this.fetchSalas();
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
          // Agregar nueva sala
          this.$axios.$post('/api/salas', this.editedItem)
            .then(() => {
              this.fetchSalas();
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
        this.editedIndex = this.salas.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true;
      },
      deleteItem(item) {
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
          this.$axios.$delete('/api/salas/{sala}', {params: {'id': salaId }})
            .then(() => {
              this.$swal.fire(
                'Elimado!',
                'El archivo ha sido eliminado.',
                'success'
              )
              this.fetchSalas();
             })
            .catch(error => {
             console.error(error);
             this.$swal("Error", error);
            });
           }
          })
      
      },
      fetchSalas() {
        this.$axios.$get('/api/salas')
          .then(response => {
            this.salas = response;
          })
          .catch(error => {
            console.error(error);
          });
      },
    },
  };
  </script>
  