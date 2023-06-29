
<template>
    <div class="winner-container">
      <h2>¡Felicidades, {{ ganador }}!</h2>
      <p>Eres el ganador</p>
    </div>
  </template>
  
  <script>
  import gsap from 'gsap';
  
  export default {
    props: {
      ganador: {
        type: String,
        required: true
      }
    },
    mounted() {
      this.animateWinner();
    },
    methods: {
      animateWinner() {
        const tl = gsap.timeline();
        tl.from('.winner-container', { opacity: 0, duration: 1, scale: 0.5 })
          .to('.winner-container', { opacity: 1, duration: 1, scale: 1.2, ease: 'back.out(1.7)' })
          .to('.winner-container', { scale: 1, duration: 0.5 });
      }
    },
    beforeDestroy() {
      gsap.killTweensOf('.winner-container');
    }
  };
  </script>
  <style scoped>
.winner-container {
  background-color: #f9f9f9;
  padding: 20px;
  border-radius: 8px;
  text-align: center;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.winner-container h2 {
  font-size: 24px;
  margin-bottom: 10px;
}

.winner-container p {
  font-size: 18px;
  color: #888;
}
</style>
  