import Vue from 'vue';

// Crea una instancia global reactiva para almacenar las adivinanzas
const adivinanzasStore = Vue.observable({
  adivinanzas: [],
});

// Exporta una función para actualizar las adivinanzas en la instancia global
export function updateAdivinanzas(nuevasAdivinanzas) {
  adivinanzasStore.adivinanzas = nuevasAdivinanzas;
}

// Exporta una función para acceder a las adivinanzas desde cualquier componente
export function getAdivinanzas() {
  return adivinanzasStore.adivinanzas;
}