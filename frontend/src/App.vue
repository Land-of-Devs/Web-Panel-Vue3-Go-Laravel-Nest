<template>
  <Modal/>
  <NavBar/>
  <router-view/>
</template>

<script>
import NavBar from './components/NavBar.vue';
import Modal from './components/global/Modal.vue';

export default {
  components: {
    NavBar,
    Modal
  }  
}
</script>

<style lang="scss">
.shadow {
  -webkit-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
  -moz-box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
  box-shadow: 0px 0px 5px 0px rgba(0,0,0,0.75);
}
body, html {
  margin: 0px;
}

body {
  font-family: Verdana, Geneva, Tahoma, sans-serif;
}
#app {
  height: 100vh;
  display: flex;
  flex-direction: column;
}

.form-err {
  color: red;
  font-size: 11pt;
  text-align: center;
  margin-bottom: 5px;
}

.va-toast {
  z-index: 100000 !important;
}
.va-dropdown__content-wrapper {
  z-index: 1000000000 !important;
}

*::-webkit-scrollbar {
  width: 7px;
  height: 7px;
}

*::-webkit-scrollbar-track {
  background: white;
}

*::-webkit-scrollbar-thumb {
  border-radius: 40px;
  background: gray;
}

*::-webkit-scrollbar-thumb:hover {
  background: rgb(65, 65, 65);
}

</style>
