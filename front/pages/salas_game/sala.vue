<template >
<div> 
  <div v-if="!this.$auth.user.is_admin"> 
    <div class="user-card">
     
     <h3>{{this.$auth.user.name }}</h3> 
   
     
    </div>

    <div class="game-menu-card">
      <button class="menu-button" @click="toggleMenu">Menú</button>
      <ul class="menu-list hidden">
        <li><a href="#">Historial</a></li>
        <li><a href=" <LoadingScreen/>">Reglas</a></li>
        <li><a href="#"  @click="logout()">Cerrar sesion</a></li>
      </ul>
    </div>
   <div class="neon-border">
    <div class="salas-container">
    <div class="sala" v-for="sala in salas" :key="sala.id">
     
      <div class="sala-header">
        <h1 class="sala-nombre">{{ sala.nombre_sala }}</h1>
      
      </div>
      <div class="sala-body">
        <img src="/riddles1.png" alt="">
        <h3 class="sala-text">{{ sala.descripcion }}</h3>
        <h3 class="sala-text">Maximo Jugadores: {{ sala.cantidad_jugadores }}</h3>
      </div>
      <div class="sala-footer">
        <button class="unirse-sala" @click="unirseASala(sala.id, sala.cantidad_jugadores)" :disabled="salaCargando !== null && salaCargando !== sala.id">
          {{ salaCargando === sala.id ? 'Uniéndose...' : 'Unirse' }}
        </button>
      </div>
    </div>
 
  </div>
</div>
    <div class="game-score-card">
      <puntajes/>
    </div>
  </div>
    <div class="admin-panel" v-if="this.$auth.user.is_admin">
    <div class="sidebar">
      <div class="logo">
        <img src="/logoadmin.jpg" alt="Logo">
      </div>
      <div class="menu">
        
        <div class="menu-item" @click="setActiveMenu('salas')">Administrar Salas</div>
        <div class="menu-item" @click="setActiveMenu('usuarios')">Administrar Usuarios</div>
        <div class="menu-item" @click="setActiveMenu('cargar-adivinanzas')">Adivinanzas</div>
        <div class="menu-item" @click="setActiveMenu('cargar-salas')">Cargar Adivinanzas a Salas</div>
        <div class="menu-item" @click="logout()">Salir</div>
      </div>
    </div>
    <div class="content">
      <!-- Contenido de la página actual -->
      <component :is="activeComponent"></component>
    </div>
  </div>
 </div>

  

</template>

 <script>

  import { gsap } from 'gsap'
  import LoadingCircle from '~/components/loadingroom.vue';
  import AdminSalas from '~/components/AdminSalas.vue';
  import AdminUsuarios from '~/components/AdminUsuarios.vue';
  import CargarAdivinanzas from '~/components/CargarAdivinanzas.vue';
  import cargaradivinasaxsalas from '~/components/cargaradivinasaxsalas.vue';
  export default {
  
 
    data:() => ({
           salas:[],
           salaCargando: null,
           activeMenu: 'salas'
           
        }),
    components: {
          LoadingCircle,
           AdminSalas,
           AdminUsuarios,
          CargarAdivinanzas,
          cargaradivinasaxsalas
   },
   computed: {
    botonDeshabilitado() {
      return salaId => this.salaCargando !== null && this.salaCargando !== salaId;
    },
    activeComponent() {
        switch (this.activeMenu) {
          case 'salas':
            return 'AdminSalas';
          case 'usuarios':
            return 'AdminUsuarios';
          case 'cargar-salas':
            return 'cargaradivinasaxsalas';
          case 'cargar-adivinanzas':
            return 'CargarAdivinanzas';
          default:
            return '';
        }
      }
    
  },
    
    mounted() {
      if (!this.$auth.loggedIn) {
      this.$router.push('/auth/login'); // Redireccionar a la página de inicio de sesión si el usuario no está autenticado
    }
      gsap.from('.sidebar', { x: '-100%', duration: 0.5, ease: 'power2.out' });
      const gameRoomContainer = document.querySelector('.game-room-container');
    if (gameRoomContainer) {
      gameRoomContainer.style.opacity = 0;
      gsap.to(gameRoomContainer, { opacity: 1, duration: 1 });
    }

    // Animación de entrada de las tarjetas de las salas
    const salas = document.querySelectorAll('.sala');
    gsap.from(salas, { opacity: 0, y: 50, stagger: 0.2 });

    this.getSala();
    },

    methods: {
      
    getSala() {
       this.$axios.$get('/api/salas')
                    .then((resp)=>{
                    this.salas = resp
                    console.log(this.salas)
                     })
                    .catch((err)=>{console.log(err)})
               },
    toggleMenu() {
      const menuList = document.querySelector('.menu-list')
      menuList.classList.toggle('hidden')
    },
    async unirseASala(idSala,cantidad_jugadores) {
      try {
        // Actualizar el estado de la sala cargando
        this.salaCargando = idSala;
      
        // Simulación de espera de 2 segundos
        await new Promise(resolve => setTimeout(resolve, 2000));

        // Redireccionar a la sala interna
        this.$router.push(`/salas_game/${idSala}/sala-interna/${cantidad_jugadores}`);
      } catch (error) {
        console.log('No se pudo procesar la operacion', error);
      } finally {
        // Restaurar el estado de la sala cargando
        this.salaCargando = null;
      }
    },
    logout() {
            this.$auth.logout();
     },
   setActiveMenu(menu) {
        this.activeMenu = menu;
      },
  }
  

  }
  </script>
  
  <style scoped>
.salas-container {
  
  grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
  gap:4rem;
  padding: 10rem;
  display: flex;
  flex-wrap: wrap;
  gap: 30px;
  }
  .neon-border {
  
  background: linear-gradient(45deg, #00ff00, #ff00ff);
  background-size: 400% 400%;
  animation: gradientAnimation 3s ease infinite;
  border-radius: 15px;
}
  .sala {
  background-color: #1a1a1a;
  border: 2px solid #333;
  border-radius: 10px;
  padding: 100px;
  width: 450px;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.3);
  transition: transform 0.3s ease;
  cursor: pointer;


  }
  .sala-body {
  color: #ccc;
  margin-bottom: 10px;
}
  .sala-header {
    margin-bottom: 1rem;
    display: flex;
  justify-content: space-between;
  align-items: center;
  }

  .sala-nombre {
    color: #48ee0c;
  font-size: 40px;
  margin-bottom: 10px;
  }
  .sala-text {
    color: #f3f5f2;
  font-size: 40px;
  margin-bottom: 10px;
  }
  .sala-descripcion {
    font-size: 1rem;
    color: #333;
    margin: 0;
  }

  .sala-footer {
    margin-top: auto;
    display: flex;
  justify-content: center;
  }

  .unirse-sala {
    padding: 3rem 6rem;
    font-size: 4rem;
    background-color: #2b6cb0;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: transform 0.5s ease;
    transform: translateY(-5px);
  }

  .unirse-sala:hover {
    
    transform: scale(1.05);
    background-color: #ff8533;
  }

  
.game-menu-card {
  position: absolute;
  top: 2rem;
  left: 2rem;
  width: 200px;
  background-color: #fff;
  border-radius: 0.25rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.menu-button {
  padding: 0.5rem 1rem;
  font-size: 1rem;
  background-color: #2b6cb0;
  color: #fff;
  border: none;
  border-radius: 0.25rem;
  cursor: pointer;
}

.menu-button:hover {
  background-color: #2c5282;
}

.menu-list {
  list-style: none;
  padding: 0;
  margin: 0;
}

.menu-list.hidden {
  display: none;
}

.menu-list li {
  margin: 0.5rem 0;
}

.menu-list a {
  display: block;
  padding: 0.5rem;
  color: #333;
  text-decoration: none;
}

.menu-list a:hover {
  background-color: #f0f0f0;
}
/* Estilos para la tarjeta del puntaje */
.game-score-card {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 200px;
  background-color: #fff;
  border-radius: 0.25rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}

.score {
  font-size: 2rem;
  font-weight: bold;
  margin: 0;
  color: #2b6cb0;
}
.menu-list a:hover {
  background-color: #f0f0f0;
}
.welcome-message {
  font-size: 1.5rem;
  color: #f80d0d;
  text-align: center;
  text-shadow: 0 0 10px rgba(255, 255, 255, 0.5);
}
  .game-room-background {
    background-color: #23458a;
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100vh;
  }
  
  .game-room-container {
    width: 30%;
    margin: 0 auto;
    padding: 2rem;
    background-color: #e91111;
    border-radius: 0.25rem;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  }
  
  h1 {
    font-size: 3rem;
    margin-bottom: 2rem;
    color: #080808;
    text-align: center;
  }
  
  .game-room-content {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 1rem;
    color: #f8f4f4;
    text-align: center;
  }
  
  img {
    width: 300px;
    height: 300px;
  }
  
  button.join-button {
    padding: 0.5rem 1rem;
    font-size: 1rem;
    background-color: #2b6cb0;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
  }
  
  button.join-button:hover {
    background-color: #2c5282;
  }
  /* Estilos para la tarjeta del usuario */
.user-card {
  position: absolute;
  top: 1rem;
  right: 2rem;
  display: flex;
  align-items: center;
  color: #47f009;
}

.user-avatar {
  height: 3rem;
  width: 3rem;
  background-color: #5f6560;
  color: #0b0a0a;
  border-radius: 50%;
  display: flex;
  align-items: center;
  justify-content: center;
}

.user-avatar i {
  font-size: 4rem;
}

.user-name {
  margin-left: 0.5rem;
  font-size: 0.875rem;
  font-weight: bold;
  color: #1f0202;
}
body {
    background-color: #614e7a;
  }
  
  .admin-panel {
  width: 2000px;
  height: 4000px; /* Aumenta la altura según tus necesidades */
  margin:0 auto;
  display: flex;
  background-color: #ffffff;
  box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
}
  
  .sidebar {
   
    background-color: #f2f2f2;
    padding: 20px;
  }
  
  .logo img {
    max-width: 100%;
  }
  
  .menu {
    margin-top: 20px;
  }
  
  .menu-item {
    cursor: pointer;
    padding: 10px 0;
  }
  
  .content {
    flex: 1;
    padding: 20px;
  }
  
  .component-container {
    border: 1px solid #ccc;
    border-radius: 5px;
    padding: 20px;
  }
  
  @media (max-width: 1200px) {
    .admin-panel {
      width: 100%;
    }
  }
  </style>