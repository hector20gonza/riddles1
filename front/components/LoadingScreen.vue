<template>
  <div class="loading-screen">
    <h1 class="neon-text">Cargando...</h1>
    <div class="loading-bar">
      <div class="symbol"></div>
    </div>
  </div>
</template>

<script lang="ts">
import { gsap } from 'gsap'

export default {
  mounted() {
    // Animación de la barra de carga
    const loadingBar = document.querySelector('.loading-bar')
    const symbol = document.querySelector('.symbol')

    gsap.to(loadingBar, {
      width: '100%',
      duration: 4,
      onComplete: () => {
        this.$router.push('/Mainstart') // Redireccionar a la ruta '/'
      }
    })

    gsap.fromTo(
      symbol,
      { y: '100%' },
      { y: '-50%', duration: 2, repeat: -1, yoyo: true, ease: 'elastic.out(1, 0.3)' }
    )
  }
}
</script>

<style scoped>
.loading-screen {
  height: 100vh;
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  font-size: 4rem;
  background-color: #000;
}

.loading-bar {
  width: 0;
  height: 4rem;
  background-color: #2b6cb0;
  position: relative;
}

.symbol {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  width: 6rem;
  height: 6rem;
  background-image: url('/question.png'); /* Ruta relativa desde la carpeta static */
  background-size: cover; /* Ajusta el tamaño de la imagen para cubrir el contenedor */
  background-repeat: no-repeat; /* Evita que la imagen se repita */
  animation: bounce 2s infinite;
  transform-origin: bottom;
}

.neon-text {
  color: #fff;
  text-shadow: 0 0 10px #fff, 0 0 20px #fff, 0 0 30px #fff, 0 0 40px #2b6cb0, 0 0 70px #2b6cb0, 0 0 80px #2b6cb0, 0 0 100px #2b6cb0, 0 0 150px #2b6cb0;
  animation: flicker 1s infinite alternate;
}

@keyframes flicker {
  0% {
    opacity: 1;
  }
  50% {
    opacity: 0.5;
  }
  100% {
    opacity: 1;
  }
}
</style>
