<template>
  <div class="login-container">
    <div class="flex flex-col mt-3" v-if="errors">
      <span class="text-red-200 italic">{{ errors }}</span>
    </div>
    <h1 class="neon-text">Iniciar sesión</h1>
    <form @submit.prevent="login">
      <div class="form-group">
        <label for="email">Email:</label>
        <input type="email" id="email" v-model="form.email" required>
      </div>
      <div class="form-group">
        <label for="contraseña">Contraseña:</label>
        <input type="password" id="contraseña" v-model="form.password" required>
      </div>
      <div class="form-group">
        <button type="submit">Iniciar sesión</button>
      </div>
    </form>
  </div>
</template>

<script>
import { gsap } from 'gsap'
import swal from 'sweetalert';

export default {
  data: () => ({
    form: {
      email: '',
      password: ''
    },
    errors: ''
  }),
  methods: {
    async login() {
      try {
        await this.$auth.loginWith('laravelSanctum', { data: this.form })
        swal("Bienvenido", this.$auth.user.name)
      } catch (err) {
        if (err.response.status === 422) {
          this.$swal('Error', 'No puede iniciar con esas credenciales', 'error');
        }
      }
    }
  },
  mounted() {
    // Animación de entrada de inicio de sesión
    const loginContainer = document.querySelector('.login-container')
    const formGroups = document.querySelectorAll('.form-group')
    loginContainer.style.opacity = 0
    gsap.to(loginContainer, { opacity: 1, duration: 1 })
    gsap.from(formGroups, { opacity: 0, y: 50, stagger: 0.2 })
  }
}
</script>

<style scoped>
.login-container {
  max-width: 500px;
  top: 50%;
  margin: 0 auto;
  padding: 2rem;
  background-color: rgb(245, 236, 236)d2d;
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

.neon-text {
  color: #fff;
  text-shadow: 0 0 10px #2b6cb0;
}
</style>
