<template>
    <div class="registration-container">
      <h1>Registro</h1>
      <form  @submit.prevent="register">
        <div class="form-group">
          <label for="nombre" >Nombre:</label>
          <input type="text" id="nombre"  v-model="form.name" required>
          <span class="text-red-200 italic"  v-if="errors.name">{{ errors.name[0] }}</span>
        </div>
        
        <div class="form-group">
          <label for="email">Email:</label>
          <input type="email" id="email" v-model="form.email"  required>
          <span class="text-red-200 italic"  v-if="errors.email">Error de Correo </span>
        </div>
        <div class="form-group">
          <label for="contraseña">Contraseña:</label>
          <input type="password" id="contraseña" v-model="form.password"  required>
          <span class="text-red-200 italic" v-if="errors.password">Verifique que coincidan las claves y que sean mayor de 8 caracteres</span>
        </div>
        <div class="form-group">
          <label for="contraseña">Confirmar Contraseña:</label>
          <input type="password" v-model="form.password_confirmation"  required>
        </div>
        <div class="form-group">
          <button type="submit">Registrarse</button>
        </div>
      </form>
    </div>
  </template>
  
  <script>
  import { gsap } from 'gsap'
  import swal from 'sweetalert';
  export default {
 
    data: ()=> ({
             form: {
                 name: '',
                 email: '',
                 password: '',
                 password_confirmation: '',
             },
             errors: [],
         }),
    methods: {
            
            async register() {
                try {
                    let errors = []
                    await this.$axios.$get('sanctum/csrf-cookie')
                    await this.$axios.$post('/register', this.form)
                        .then((resp) => {
                          this.$swal.fire(
                                         'Buen trabajo!',
                                          'Registro Exitoso!',
                                          'success'
                                           )
                             })
                        .catch((err) => {
                            if (err.response.status = 422) {
                                errors = err.response.data.errors
                                this.$swal('Error','error',  errors);
                                return
                            }
                        })
                        this.errors = errors
                        console.log(this.errors)
                    await this.$auth.loginWith('laravelSanctum', {data: this.form})
                } catch (error) {
                    
                }
            }
         },

    mounted() {
      // Animación de entrada de registro
      const registrationContainer = document.querySelector('.registration-container')
      const formGroups = document.querySelectorAll('.form-group')
      registrationContainer.style.opacity = 0
      gsap.to(registrationContainer, { opacity: 1, duration: 2 })
      gsap.from(formGroups, { opacity: 0, y: 50, stagger: 0.2 })
    }
  }
  </script>
  
  <style scoped>
  .registration-container {
    max-width: 500px;
    margin: 0 auto;
    padding: 2rem;
    background-color: #fff;
    border-radius: 0.25rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  
  h1 {
    font-size: 2rem;
    margin-bottom: 2rem;
  }
  
  form {
    display: flex;
    flex-direction: column;
  }
  
  .form-group {
    display: flex;
    flex-direction: column;
    margin-bottom: 1rem;
  }
  
  label {
    font-size: 1.25rem;
    margin-bottom: 0.5rem;
  }
  
  input[type="text"],
  input[type="email"],
  input[type="password"] {
    padding: 0.5rem;
    font-size: 1rem;
    border-radius: 0.25rem;
    border: 1px solid #ccc;
    outline: none;
  }
  
  button[type="submit"] {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    background-color: #2b6cb0;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
  }
  
  button[type="submit"]:hover {
    background-color: #2c5282;
  }
  </style>