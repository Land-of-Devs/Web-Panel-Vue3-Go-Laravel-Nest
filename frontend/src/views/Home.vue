<template>
  <div style="height: 100%;">
    <div class="title">Web panel</div>
    <ImageCarousel :images="[
      'https://images.ctfassets.net/hrltx12pl8hq/4ACnMj4WVSOZRZt0jHu9h5/1506f652bcd70f4dc3e88219fefea858/shutterstock_739595833-min.jpg?fit=fill&w=800&h=300',
      'https://img.freepik.com/vector-gratis/tecnologia-internet-nube_53876-94170.jpg?size=626&ext=jpg&ga=GA1.2.964378125.1636416000',
      'http://nfq.es/wp-content/uploads/2017/12/Blockchain-Technology.jpg'
    ]" />
  </div>
</template>


<script>
import ImageCarousel from './../components/global/ImageCarousel.vue';
export default {
  components: {
    ImageCarousel
  },
  setup() {

  }
}
</script>

<style lang="scss" scoped>
.title {
  z-index: 10;
  font-size: 50pt;
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);
  color: white;
  user-select: none;
}
</style>