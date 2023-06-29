<template>
    <div>
      <h2>Usuarios</h2>
      
      <table class="table">
        <thead>
          <tr>
            <th>Nombre</th>
            <th>Email</th>
            <th>Password</th>
            <th>Rol</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for=" user in usuarios" :key="user.id">
            <td>{{ user.name }}</td>
            <td>{{ user.email }}</td>
            <td>{{ user.password }}</td>
            <td v-if="user.is_admin===1">Admininstrador </td>
            <td v-if="user.is_admin===0">Usuario </td>
            <td>
              <button class="btn btn-primary btn-sm" @click="editItem(user)">Editar</button>
              <button class="btn btn-danger btn-sm" @click="deleteItem(user)">Eliminar</button>
            </td>
          </tr>
        </tbody>
      </table>
  
      <div class="mt-3">
        <button class="btn btn-success" @click="showAddDialog">Agregar Usuario</button>
      </div>
  
      <!-- Modal para agregar/editar usuario -->
      <div class="modal" :class="{ 'd-block': dialog }">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title">{{formTitle}}</h5>
              <button type="button" class="close" @click="close">&times;</button>
            </div>
            <div class="modal-body">
              <div class="form-group">
                <label for="nameInput">Nombre</label>
                <input type="text" class="form-control" id="nameInput" v-model="editedItem.name">
              </div>
              <div class="form-group">
                <label for="emailInput">Email</label>
                <input type="email" class="form-control" id="emailInput" v-model="editedItem.email">
              </div>
              <div class="form-group">
                <label for="passwordInput">Password</label>
                <input type="password" class="form-control" id="passwordInput" v-model="editedItem.password">
              </div>
              <div class="form-group">
                <label for="rolInput">Rol</label>
                
                 <select  class="form-control" v-model="editedItem.is_admin">
                  <option :value="1">Administrador</option>
                  <option :value="0">Usuario</option>
                </select>
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
          name: '',
          email: '',
          password: '',
          rol: '',
        },
        defaultItem: {
          name: '',
          email: '',
          password: '',
          rol: '',
        },
        usuarios: [],
      };
    },
    created(){
      this.fetchuser();
    },
    computed: {
      formTitle() {
        return this.editedIndex === -1 ? 'Agregar Usuario' : 'Editar Usuario';
      },
      
    },
    methods: {
      close() {
        this.dialog = false;
        this.editedItem = Object.assign({}, this.defaultItem);
        this.editedIndex = -1;
      },
      showAddDialog() {
        this.formTitle = 'Agregar Usuario';
        this.editedItem = Object.assign({}, this.defaultItem);
        this.dialog = true;
      },
      save() {
        if (!this.editedItem.name || !this.editedItem.email || !this.editedItem.password) {
        this.$swal("Error", "Por favor completa todos los campos", "error");
          return; // Detener la ejecución si los campos están vacíos
        }
        if (this.editedIndex > -1) {
          const user= this.usuarios[this.editedIndex].id;
         this.$axios.$put('/api/users/${user}',{
             id:user,
             name:this.editedItem.name,
             email:this.editedItem.email,
             is_admin:this.editedItem.is_admin,
             password:this.editedItem.password
            })
            .then(() => {
              this.fetchuser();
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
          this.$axios.$post('/api/users', this.editedItem)
            .then(() => {
              this.fetchuser();
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
        this.close();
      },
      editItem(item) {
        this.editedIndex = this.usuarios.indexOf(item);
        this.editedItem = Object.assign({}, item);
        this.dialog = true;
      },
      deleteItem(item) {
        const user = item.id;
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
            this.$axios.$delete('/api/users/{user}', {params: {'id': user }})
            .then(() => {
              this.$swal.fire(
                'Elimado!',
                'El archivo ha sido eliminado.',
                'success'
              )
             this.fetchuser();
             })
            .catch(error => {
             console.error(error);
             this.$swal("Error", error);
            });
          
 
          }
        })
 },
      fetchuser() {
        this.$axios.$get('/api/users')
          .then(response => {
            this.usuarios = response;
          })
          .catch(error => {
            console.error(error);
          });
      },
    },
  };
  </script>
  