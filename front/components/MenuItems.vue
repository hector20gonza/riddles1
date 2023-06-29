<template>
    <div class="menu-items">
      <div class="menu-item" @click="$emit('historial')">Historial</div>
      <div class="menu-item" @click="$emit('reglas')">Reglas</div>
      <div class="menu-item" @click="$emit('cerrar-sesion')">Cerrar sesión</div>
    </div>
  </template>
  
  <style>
  .menu-items {
    display: flex;
    flex-direction: column;
    align-items: flex-start;
  }
  
  .menu-item {
    font-size: 2rem;
    margin: 1rem;
    cursor: pointer;
    transition: transform 0.2s ease-in-out;
    width: 100%;
    text-align: center;
  }
  
  .menu-item:hover {
    transform: scale(1.1);
  }
  </style>