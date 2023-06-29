<template>
  <div class="container">
    <winner :ganador="playerwin.ganador" v-if="playerwin.ganador" />
    <div class="avatar-container">
      <img src="/avatar.jpg" alt="Avatar" class="avatar">
    </div>
    <div class="reloj">
      <div class="tiempo">{{ tiempoRestante }}</div>
    </div>
    <div class="card-container">
      <div class="card">
        <div class="card-header">
          <h2>{{ fin }}</h2>
        </div>
        <div class="card-body">
          <div v-if="currentRiddle">
            <h2>{{ currentRiddle.pregunta }}</h2>
          </div>
          <div v-for="resp in userrespuesta" :key="resp.id" :class="getMessageClass(resp.user)">
            <span>{{ resp.user }}</span>
            <p>{{ resp.resp }}</p>
          </div>
        </div>
        <div class="card-footer">
          <input type="text" v-model="newrespuesta" placeholder="Escribe tu respuesta">
          <button @click="answersubmit" :disabled="disableSubmit">Enviar</button>
        </div>
      </div>
    </div>
    
    <div>
      <h1>En línea:</h1>
      <ul class="user-list">
        <li v-for="use in usuarios" :key="use.id"><h1>{{ use.name }}</h1></li>
      </ul>
      <button class="unirse-sala" type="submit" @click="salirsala()">Salir</button>
    </div>
    <div class="chat-card">
      <div class="chat-messages">
        <div v-for="message in messages" :key="message.id" :class="getMessageClass(message.user)">
          <span class="message-sender">{{ message.user }}</span>
          <span class="message-text">{{ message.text }}</span>
        </div>
      </div>
      <div class="chat-input">
        <input v-model="newMessage" type="text" placeholder="Escribe tu mensaje" />
        <button @click="sendMessage">Enviar</button>
      </div>
    </div>
  </div>
</template>

<script>
  import { gsap } from 'gsap';
  import Pusher from 'pusher-js';
  import winner from '~/components/winner.vue';

  export default {
    
    data() {
      return {
        user: this.$auth.user.id,
        usuarios: [],
        channel: null,
        adivinanzas: [],
        currentIndex: 0,
        adivinanza: '',
        respuesta: [],
        mensaje: '',
        newMessage: '',
        messages: [],
        userrespuesta: [],
        newrespuesta: '',
        tiempoRestante:30,
        timer: '',
        salaId: this.$route.params.id,
        playertop:this.$route.params.cantidad_jugadores,
        playerjoinroom: '',
        respuestasRecibidas: 0,
        disableSubmit: true,
        currentRiddle: '',
        responseRiddle: '',
        tiempoEnvioRespuesta: 0,
        tiempoRespuesta: 0,
        valorTemporizador: '',
        fin: '',
        playerwin: '',
        usuarioActual: [],
      };
    }, 
    components: {
      winner
    },
    mounted() {
      this.$echo.channel(`WinnerRoom.${this.$route.params.id}`)
        .listen('.Winner.sent', (message) => {
          console.log(message);
          this.playerwin = message;
        });
    },
    created() {
      Pusher.logToConsole = true;
      this.listenForUsers();
    },
    methods: {
      listenForUsers() {
        this.channel = this.$echo.join(`online_users.${this.$route.params.id}`);
        this.channel.here(async (users) => {
          this.playerjoinroom = users.length;
          this.usuarios = this.usuarios.concat(users);
          if (this.usuarios.length === parseInt(this.playertop)) {
            console.log('La sala está llena. Cargar datos y realizar acciones...');
           
            
            this.$axios.$post('/api/salas/adivinanzas/game', {
              roomId: `${this.$route.params.id}`
            }).catch((err) => {
              console.log(err);
            });
          } else {
            this.$swal('La sala aún no está llena. Espera a que los demás usuarios se unan...');
            
          }
        }).joining((user) => {
          this.usuarios.push(user);
          console.log('Un nuevo usuario se ha unido al canal.');
        }).leaving((user) => {
          console.log('Usuario se desconectó.');
        });

        this.channel.error((error) => {
          console.log('ERROR INESPERADO', error);
        });

        this.$echo.channel(`chat.${this.$route.params.id}`)
          .listen('.message.sent', (message) => {
            this.messages.push(message);
          });

        this.$echo.channel(`AnswerSub.${this.$route.params.id}`)
          .listen('.response.sent', (respue) => {
            const respUser = respue;
            this.respuestasRecibidas++; 
            this.userrespuesta.push(respUser);
          });

        this.$echo.channel(`AdivinanzaXsala.${this.$route.params.id}`)
          .listen('.adivina.sent', (respue) => {
            this.adivinanzas = respue.adivinanza;
            if (this.adivinanzas.length > 0) {
              this.currentRiddle = this.adivinanzas[0];
              console.log('La respuesta es', this.responseRiddle);
              this.iniciarReloj();
              this.disableSubmit = false;
            }
          });
      },

      salirsala() {
        this.$echo.leave(`online_users.${this.$route.params.id}`, (user) => {
          this.usuarios = this.usuarios.filter(u => u.id !== user.id);
        });

        this.$router.push('/salas_game/sala');
         this.$nextTick(() => {
         setTimeout(() => {
         window.location.reload(); // Recargar la página después de un pequeño retraso
        }, 100); // Ajusta el tiempo de retraso según sea necesario
      });
      },

      comprobarRespuesta() {
        if (this.newrespuesta.toLowerCase() === this.currentRiddle.respuesta.toLowerCase()) {
          this.fin = '¡Correcto!';
          const usuarioActual = this.usuarios.find(usuario => usuario.id === this.user);
         
          if (usuarioActual) {
            // Si el usuario ya existe en el arreglo, actualizar sus respuestas
            usuarioActual.respuestas = usuarioActual.respuestas || [];
            usuarioActual.respuestas.push({
              respuesta: this.newrespuesta,
              puntos: 3,
              tiempoRespuesta: this.tiempoRespuesta
             
            });
            console.log(usuarioActual);
          } else {
            // Si el usuario no existe en el arreglo, agregarlo con las respuestas
            this.usuarios.push({
              nombre: this.$auth.user.name,
              id:this.user,
              respuestas: [{
                respuesta: this.newrespuesta,
                puntos: 3,
                tiempoRespuesta: this.tiempoRespuesta
              }]
            });
            this.winner(this.usuarios);
          }
        } else {
          this.fin = 'Incorrecto';
        }
      },
      
      answersubmit() {
        this.$axios.$post('/api/send-responseriddle', {
          respuesta: this.newrespuesta,
          user: this.$auth.user.name,
          roomId: `${this.$route.params.id}`
        });
        const timeestimed = 30;
        this.tiempoRespuesta = timeestimed - this.valorTemporizador;
        this.comprobarRespuesta(this.newrespuesta, this.tiempoRespuesta);
        this.newrespuesta = '';
        this.disableSubmit = true;
      },

      sendMessage() {
        this.$axios.$post('/api/send-message', {
          text: this.newMessage,
          user: this.$auth.user.name,
          roomId: `${this.$route.params.id}`
        });

        this.newMessage = '';
      },

      getMessageClass(user) {
        return user === this.user ? 'right-message' : 'left-message';
      },

      iniciarReloj() {
        const tl = gsap.timeline({ repeat: -1, yoyo: true });
        tl.to('.tiempo', {
          duration: 0.5,
          scale: 1.2,
          ease: 'power1.inOut',
        });
        this.timer = setInterval(() => {
          this.tiempoRestante--;
          if (this.tiempoRestante <= 0) {
            clearInterval(this.timer);
            this.siguientePregunta();
          }
          this.valorTemporizador = this.tiempoRestante;
        }, 1000);
      },

      siguientePregunta() {
        this.userrespuesta = [];
        const currentIndex = this.adivinanzas.indexOf(this.currentRiddle);
        const nextIndex = currentIndex + 1;
        console.log(nextIndex);
        if (nextIndex < this.adivinanzas.length) {
          this.currentRiddle = this.adivinanzas[nextIndex];
          this.fin = '';
          this.disableSubmit = false;
          this.respuestasRecibidas = 0;
          this.tiempoRestante = 30;
          this.iniciarReloj();
        } else {
          this.winner();
          this.disableSubmit = true;
          this.currentRiddle = '';
          this.fin = 'Se terminó. En breve conocerás al ganador.';
          this.cargarpuntos();
        }
      },

      winner() {
        const usuarioActual = this.usuarios.find(usuario => usuario.id=== this.user);
        const usuariosEnviar = [usuarioActual];
        this.$axios.$post('/api/winner', {
          usuarios: usuariosEnviar, 
          roomId: `${this.$route.params.id}`,
          totaluser: `${this.$route.params.cantidad_jugadores}`
     
        });
      },
      cargarpuntos(){
        this.$axios.get('api/puntajes',{
        params:{
            users_id:this.$auth.user.id
            }
         }).then((val)=>{
      
         console.log(val.data)
          }).catch(error=>{
            console.log(error)
          });
      }
    }
  }
</script>

 
  <style scoped>
.reloj {
  position: fixed;
  top: 20px;
  left: 50%;
  transform: translateX(-50%);
  background-color: rgba(0, 0, 0, 0.5);
  padding: 10px;
  border-radius: 10px;
}

.tiempo {
  font-size: 24px;
  color: white;
  font-weight: bold;
  text-align: center;
  width: 50px;
  height: 50px;
  line-height: 50px;
  background-color: #ff0000;
  border-radius: 50%;
}
  .right-message {
  text-align: right;
  background-color: lightblue;
  padding: 8px;
  border-radius: 8px;
  margin-bottom: 8px;
}

.left-message {
  text-align: left;
  background-color: lightgray;
  padding: 8px;
  border-radius: 8px;
  margin-bottom: 8px;
}
  .chat-card {
  position: fixed;
  bottom: 20px;
  right: 20px;
  width: 300px;
  background-color: #f5f5f5;
  border-radius: 10px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.chat-messages {
  max-height: 300px;
  overflow-y: auto;
  padding: 10px;
}

.message-sender {
  font-weight: bold;
  margin-bottom: 5px;
}

.message-text {
  margin-bottom: 10px;
}

.chat-input {
  display: flex;
  align-items: center;
  padding: 10px;
}

.chat-input input {
  flex: 1;
  padding: 5px;
  border: none;
  border-radius: 5px;
}

.chat-input button {
  margin-left: 10px;
  padding: 5px 10px;
  background-color: #ccc;
  border: none;
  border-radius: 5px;
  color: #fff;
}

   .message-sender {
    font-weight: bold;
    color: blue;
  }
  .game-score-card {
  position: fixed;
  bottom: 2rem;
  right: 2rem;
  width: 200px;
  background-color: #9b9292;
  border-radius: 0.25rem;
  box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
  text-align: center;
}
.unirse-sala {
    padding: 1rem 3rem;
    font-size: 1rem;
    background-color: #2b6cb0;
    color: #fff;
    border: none;
    border-radius: 0.25rem;
    cursor: pointer;
    transition: transform 0.5s ease;
    transform: translateY(-5px);
  }
.score {
  font-size: 2rem;
  font-weight: bold;
  margin: 0;
  color: #2b6cb0;
}
  .container {
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    background-color: #1c1c1c;
  }
  
  .card-container {
    width: 80%;
    height: 80%;
    display: flex;
    justify-content: center;
    align-items: center;
  }
  
  .card {
    width: 90%;
    height: 90%;
    background-color: #2c2c2c;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
    display: flex;
    flex-direction: column;
 justify-content: space-between;
  }
  
  .card-header {
    background-color: #ff6600;
    color: #fff;
    padding: 20px;
  border-top-left-radius: 20px;
    border-top-right-radius: 20px;
  }
  
  h2 {
    margin: 0;
    font-size: 36px;
  }
  
  .card-body {
    flex-grow: 1;
    padding: 20px;
    display: flex;
    flex-direction: column;
    
    background-color: #42d074;
  }
  
  .adivinanza {
    margin-bottom: 20px;
  }
  
  h3 {
    font-size:28px;
    margin: 0;
    color: #fff;
  }
  h1 {
    font-size:28px;
    margin: 0;
    color: #fff;
  }
  .respuesta {
  opacity: 0;
  color: #fff;
  font-size: 30px;
}
  .p{
    font-size:28px;
    margin: 0;
    color: #fff;
  }
  .card-footer {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 20px;
    border-bottom-left-radius: 20px;
    border-bottom-right-radius: 20px;
    background-color: #1c1c1c;
  }
  
  form {
    display: flex;
    justify-content: center;
    align-items: center;
    width: 100%;
  }
  
  input {
    padding: 10px;
    margin-right: 10px;
    width: 70%;
    border-radius: 5px;
    border: none;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.2);
    background-color: #fff;
   color: #1c1c1c;
  }
  
  button {
    padding: 10px;
    width: 30%;
    background-color: #ff6600;
    border: none;
    color: #fff;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease;
  }
  
  button:hover {
    background-color: #e65c00;
  }
  
  /* Animaciones */
  .card {
    transform: scale(0.9);
    
  }
  
  
  
  
  
  
  
  
  
  @keyframes avatar-bounce {
  0% {
    transform: translateY(0);
  }
  50% {
    transform: translateY(-10px);
  }
  100% {
    transform: translateY(0);
  }
}

@keyframes avatar-shake {
  0% {
    transform: rotate(0deg);
  }
  25% {
    transform: rotate(-10deg);
  }
  50% {
    transform: rotate(10deg);
  }
  75% {
    transform: rotate(-10deg);
  }
  100% {
    transform: rotate(0deg);
  }
}
  .avatar-container {
  position: absolute;
  top: 0;
  left: 0;
  width: 100px;
  height: 100px;
  display: flex;
  justify-content: center;
  align-items: center;
}

.avatar {
  width: 80%;
  height: 80%;
  border-radius: 50%;
  animation: avatar-bounce 0.5s ease-in-out infinite;
}

.animate {
  animation: avatar-shake 0.5s ease-in-out 3;
}
  </style>