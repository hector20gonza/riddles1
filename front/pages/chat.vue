<template>
  <div class="chat-container">
    <div class="chat-messages">
      <div v-for="message in messages" :key="message.id" :class="getMessageClass(message.user)">
        <span class="message-sender">{{ message.user }}</span>
         
       
           
          
      
          <div class="message-sender">{{ message.text }}</div>
      

     </div>
    </div>
    <div class="chat-input">
      <input v-model="newMessage" type="text" placeholder="Escribe tu mensaje" />
      <button  @click="sendMessage">Enviar</button>
    </div>
  </div>
</template>

<script>
import Pusher from  'pusher-js';
export default {
  data() {
    return {
      messages: [],
      newMessage: '',
       user:this.$auth.user.name,
    };
  },
  mounted() {
    Pusher.logToConsole=true;
  this.$echo.channel('chat')
  .listen('.message.sent', (message) => {
    this.messages.push(message);
  }); 
  





    
  },
  methods: {
    sendMessage() {
        this.$axios.$post('/api/send-message',{
        text:this.newMessage,
        user:this.user
        })
      this.newMessage= '';
    
    },
   getMessageClass( user) {
      return   user === this.user ? 'right-message' : 'left-message';
    },
  },
};
</script>
<style scoped>
.message-sender {
  font-weight: bold;
  color: blue;
}
.chat-container {
  display: flex;
  flex-direction: column;
  height: 100vh;
  justify-content: space-between;
  padding: 20px;
}

.chat-messages {
  flex: 1;
  overflow-y: scroll;
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

.chat-input {
  display: flex;
  align-items: center;
}

.chat-input input {
  flex: 1;
  padding: 8px;
  border: 1px solid lightgray;
  border-radius: 4px;
}

.chat-input button {
  padding: 8px 16px;
  margin-left: 8px;
  border: none;
  background-color: rgb(13, 174, 228);
  color: white;
  border-radius: 4px;
  cursor: pointer;
}
</style>