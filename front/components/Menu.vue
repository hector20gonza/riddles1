<template>
    <div class="game-menu">
      <div class="game-menu__background"></div>
      <div class="game-menu__main">
        <div class="game-menu__title"></div>
        <div class="game-menu__toggle" :class="{ active: showMenu }" @click="toggleMenu">
          <div class="game-menu__toggle-bar"></div>
          <div class="game-menu__toggle-bar"></div>
          <div class="game-menu__toggle-bar"></div>
        </div>
        <div class="game-menu__buttons" v-show="showMenu">
          <div class="game-menu__button">Historial</div>
          <div class="game-menu__button">Reglas</div>
          <div class="game-menu__button">Salir</div>
        </div>
      </div>
    </div>
  </template>
<script>
import { gsap } from 'gsap';

export default {
  data() {
    return {
      showMenu: false
    };
  },
  methods: {
 
        


    toggleMenu() {
      if (!this.showMenu) {
        gsap.to('.game-menu__background', { opacity: 1, duration: 0.5 });
        gsap.to('.game-menu__buttons', { y: 0, opacity: 1, duration: 0.5, ease: 'power2.out', stagger: 0.15 });
        gsap.to('.game-menu__toggle-bar:first-child', { rotate: 45, y: 10, duration: 0.3 });
        gsap.to('.game-menu__toggle-bar:last-child', { rotate: -45, y: -10, duration: 0.3 });
      } else {
        gsap.to('.game-menu__background', { opacity: 0, duration: 0.5 });
        gsap.to('.game-menu__buttons', { y: -20, opacity: 0, duration: 0.5, ease: 'power2.out' });
        gsap.to('.game-menu__toggle-bar:first-child', { rotate: 0, y: 0, duration: 0.3 });
        gsap.to('.game-menu__toggle-bar:last-child', { rotate: 0, y: 0, duration: 0.3 });
      }
      this.showMenu = !this.showMenu;
    }
  }
};
</script>
<style>
.game-menu {
position: absolute;
  top: 6rem;
  left: 2rem;
  width: 200px;
  background-color: #fff;
  border-radius: 0.25rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.game-menu__background {
  
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  background-color: #000;
  opacity: 0.8;
  z-index: -1;
}

.game-menu__main {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  text-align: center;
  z-index: 1;
}

.game-menu__title {
  font-family: Arial, sans-serif;
  font-size: 2em;
  color: #fff;
  margin-bottom: 20px;
}

.game-menu__toggle {
  display: flex;
  flex-direction: column;
  justify-content: space-between;
  align-items: center;
  width: 40px;
  height: 30px;
  cursor: pointer;
}

.game-menu__toggle-bar {
  width: 100%;
  height: 5px;
  background-color: #fff;
  border-radius: 5px;
  transition: transform 0.2s ease-in-out;
}

.game-menu__toggle-bar:first-child {
  transform-origin: top;
}

.game-menu__toggle-bar:last-child {
  transform-origin: bottom;
}

.game-menu__toggle.active .game-menu__toggle-bar:first-child {
  transform: translateY(10px) rotate(45deg);
}

.game-menu__toggle.active .game-menu__toggle-bar:last-child {
  transform: translateY(-10px) rotate(-45deg);
}

.game-menu__buttons {
  display: flex;
  flex-direction: column;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.game-menu__button {
  font-family: Arial, sans-serif;
  font-size: 1.5em;
  color: #fff;
  margin-bottom: 10px;
  cursor: pointer;
  transition: transform 0.2s ease-in-out;
}

.game-menu__button:hover {
  transform: scale(1.1);
}
</style>