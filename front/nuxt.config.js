export default {
  // Disable server-side rendering: https://go.nuxtjs.dev/ssr-mode
  ssr: false,
  loading: false,
  


  head: {
    title: 'front',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      { charset: 'utf-8' },
      { name: 'viewport', content: 'width=device-width, initial-scale=1' },
      { hid: 'description', name: 'description', content: '' },
      { name: 'format-detection', content: 'telephone=no' }
    ],
    link: [
      { rel: 'icon', type: 'image/x-icon', href: '/favicon.ico' }
    ],
    link: [
      {
        rel: 'stylesheet',
        href: '/css/bootstrap.min.css'
      }
    ],
    link: [
      { 
        rel: 'stylesheet',
        href: '/css/custom.css'
      }
    ],
    script: [
      {
        src :'/js/bootstrap.min.js',
        type: 'text/javascript'
      }
    ]



  },

  // Global CSS: https://go.nuxtjs.dev/config-css
  css: [
    '@/static/css/bootstrap.css'
  ],
  js: [
    '@/static/js/bootstrap.min.js'
  ],
  
  // Plugins to run before rendering page: https://go.nuxtjs.dev/config-plugins
 
  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,
  // variables de entorno del cliente

  dotenv: {
    path: process.cwd(),
  },

  // Mod,ules for dev and build (recommended): https://go.nuxtjs.dev/config-modules
  buildModules: [
 
      
 ],

  // Modules: https://go.nuxtjs.dev/config-modules
  modules: [
    // https://go.nuxtjs.dev/axios
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    '@nuxtjs/dotenv',
   '@nuxtjs/laravel-echo',
   'vue-sweetalert2/nuxt'
  
  ],
  sweetalert: {
    confirmButtonColor: '#41b882',
    cancelButtonColor: '#ff7674'
  },
  echo: {
    plugins: [ '~/plugins/echo.js' ]
 },

  // Axios module configuration: https://go.nuxtjs.dev/config-axios
  axios: {
    // Workaround to avoid enforcing hard-coded localhost:3000: https://github.com/nuxt-community/axios-module/issues/308
    baseURL: 'http://localhost:8000/',
    credentials:true
  },
  auth: {
    onError(error) {
      const code = parseInt(error.response && error.response.status)

      if (code === 401) {
       
        window.location.href = '/auth/login'
      }
       throw error
    },



    strategies: {
        'laravelSanctum': {
            provider: 'laravel/sanctum',
            url: 'http://localhost:8000',
            endpoints: {
                login: {
                    url: '/login'
                },
                logout: {
                    url: '/logout'
                },
                user: {
                    url: '/api/user'
                }
                
            },
            user: {
                property: false
            }
        },
      
    },
    redirect: {
      login: '/auth/login',
      logout: '/',
      home: '/salas_game/sala',
      
  }

  },
  // Build Configuration: https://go.nuxtjs.dev/config-build
  build: {
  },
  router: {
   
    extendRoutes(routes, resolve) {
      routes.push({
        name: 'SalaInterna',
        path: '/salas_game/:id/sala-interna/:cantidad_jugadores',
        component: resolve(__dirname, 'pages/salas_game/sala-interna.vue'),
      });
    },
  },
  


 
}
