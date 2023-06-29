<template>
  <div class="main-screen">
    <h1 class="title">Adivinanzas</h1>
    <div class="neon-border">
      <div class="buttons">
        <nuxt-link to="/auth/register" class="register-button">Registro</nuxt-link>
        <nuxt-link to="/auth/login" class="login-button">Inicio de sesión</nuxt-link>
      </div>
    </div>
  </div>
</template>

<script>
import { gsap } from 'gsap'

export default {
  mounted() {
    // Animación de entrada de pantalla principal
    const mainScreen = document.querySelector('.main-screen')
    mainScreen.style.opacity = 0
    mainScreen.style.transform = 'translateY(50px)'
    gsap.to(mainScreen, { opacity: 1, translateY: 0, duration: 1 })
  }
}
</script>

<style scoped>
.main-screen {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  font-size: 2rem;
  background-color: #000;
}

.title {
  margin-bottom: 2rem;
  color: #fff;
  text-shadow: 0 0 10px #fff;
}

.neon-border {
  border: 2px solid #0f0;
  padding: 2rem;
  border-radius: 0.5rem;
  box-shadow: 0 0 20px #0f0;
}

.buttons {
  display: flex;
  justify-content: center;
  align-items: center;
  gap: 2rem;
}

.register-button,
.login-button {
  padding: 1rem 2rem;
  background-color: #2b6cb0;
  color: #fff;
  font-size: 1.5rem;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
  text-shadow: 0 0 10px #0f0;
}

.register-button:hover,
.login-button:hover {
  background-color: #2c5282;
}
</style>
