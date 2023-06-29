<template>
  <div>
    <h2>Puntajes</h2>
    <h2>{{ totalPuntos }}</h2>
  </div>
</template>

<script>
export default {
  data() {
    return {
      totalPuntos: 0
    };
  },
  mounted() {
    this.cargarpuntos();
  },
  methods: {
    cargarpuntos() {
      this.$axios
        .get('api/puntajes', {
          params: {
            users_id: this.$auth.user.id
          }
        })
        .then(response => {
          this.totalPuntos = response.data;
        })
        .catch(error => {
          console.log(error);
        });
    }
  }
};
</script>