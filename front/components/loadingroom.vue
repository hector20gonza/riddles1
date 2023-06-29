<template>
    <div class="loading-circle">
      <div class="circle"></div>
      <div class="circle"></div>
      <div class="circle"></div>
    </div>
  </template>
  <script>
  export default {
    name: 'LoadingCircle'
  }
  </script>

  <style scoped>
  .loading-circle {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 100px;
  }
  
  .circle {
    width: 20px;
    height: 20px;
    background-color: #2b6cb0;
    border-radius: 50%;
    margin: 0 5px;
    animation: loading-animation 1s ease-in-out infinite alternate;
  }
  
  @keyframes loading-animation {
    from {
      transform: scale(0.7);
    }
    to {
      transform: scale(1);
    }
  }
  </style>