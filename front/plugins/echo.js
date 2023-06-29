/*import Echo from 'laravel-echo';
import Cookies from 'js-cookie';
window.Pusher = require('pusher-js');

const echoInstance = new Echo({
  broadcaster: 'pusher',
  key: process.env.PUSHER_APP_KEY,
  cluster: process.env.PUSHER_APP_CLUSTER,
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false,
  authEndpoint: 'http://localhost:8000/api/broadcasting/auth', // Ruta de autorización en tu backend


  
  






  
});
*/
import Echo from 'laravel-echo';
import Cookies from 'js-cookie';
window.Pusher = require('pusher-js');



export default ({ app }, inject) => {
 
 
  





const echoInstance = new Echo({
 

  broadcaster:'pusher',
  key:'local',
  cluster: 'mt1',
  wsHost: window.location.hostname,
  wsPort: 6001,
  forceTLS: false,
 // authEndpoint: 'http://localhost:8001/api/broadcasting/auth',
  enabledTransports: ['ws', 'wss'],
auth:{
     header :{
    'Content-Type': 'application/json',
    Authorization: Cookies.get('XSRF-TOKEN'),
    'Access-Control-Allow-Credentials': true,
  },
},
  authorizer: (channel, options) => {
      return {
          authorize: (socketId, callback) => {
            app.$axios.$post('/api/broadcasting/auth', {
                  socket_id: socketId,
                  channel_name: channel.name
              })
              .then(response => {
                  callback(false, response);
              }
              )
              .catch(error => {
                  callback(true, error);
              });
          }
      };
  },
})

  app.$echo = echoInstance;
  inject('echo', echoInstance);
};
