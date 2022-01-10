<template>
  <div class="slider">
    <va-icon name="chevron_left" @click="move(-1)" class="button-left" v-if="!oneImage"></va-icon>
    <va-icon name="chevron_right" @click="move(1)" class="button-right" v-if="!oneImage"></va-icon>
    <div :style="state.styling" class="imgs" ref="slider">
      <div v-for="img in state.images" :key="img.key"
        :style="'background-image: url(' + img.data + ')'"
        alt="">
        
      </div>
    </div>
  </div>
  
</template>

<script>
import { computed, onMounted, reactive, ref } from '@vue/runtime-core';
export default {
  props: ['images'],
  setup(props) {
    const slider = ref(null);
    const state = reactive({
      images: [
        {data: props.images[props.images.length-1], key: 0 }, 
        ...props.images.map((i, c) => ({data: i, key: c+1})),
        {data: props.images[0], key: props.images.length }
      ],
      styling: {}
    });
    state.styling.transition = 'transform .4s ease-in-out';
    const oneImage = computed(() => state.images.length == 1);
    let timeout;
    let moving = false;
    let p = 1;
    function timeoutMove() {
      timeout = setTimeout(move, 3000);
    }
    function move(d=1) {
      if (moving) {
        return;
      }
      clearTimeout(timeout);
      p+=d;
      state.styling.transition = 'transform .4s ease-in-out';
      state.styling.transform = `translateX(${(p)/state.images.length*100*-1}%)`;
      moving = true;
      
      //
      //
      timeoutMove();
    }
    function transitionEnd() {
      if (p == 0) {
        p = state.images.length-2;
        
      } else if (p == state.images.length-1) {
        p = 1;
      }
      state.styling.transition = '';
      state.styling.transform = `translateX(${(p)/state.images.length*100*-1}%)`;
      moving = false;
      
      
      //delete state.styling.transition;
      //state.styling.transform = `translateX(-${1/state.images.length*100}%)`;
    }
    
    onMounted(() => {
      if (!oneImage.value) {
        slider.value.addEventListener('transitionend', transitionEnd);
        state.styling.transform = `translateX(${(p)/state.images.length*100*-1}%)`;
        state.styling.width = `${state.images.length*100}%`;
        
        timeoutMove();
        //timeoutMove();
      } 
    });
    return {
      move,
      state,
      slider,
      oneImage
    }
    
  }
}
</script>

<style lang="scss" scoped>
.slider {
  height: 100%;
  position: relative;
  overflow-x: hidden;
  .imgs {
    height: 100%;
    display: flex;
    
    div {
      height: 100%;
      width: 100%;
      background-repeat: no-repeat;
      background-position: center 60%;
      background-size: cover;
      //filter: brightness(50%);
    }
  }
  .title {
    position: absolute;
    z-index: 1;
    top: 50%;
    left: 50%;
    transform: translate(-50%,-50%);
    font-size: 50pt;
    color: white;
    font-weight: bold;
    user-select: none;
  }
  @mixin buttons {
    position: absolute;
    color: white;
    z-index: 1;
    bottom: 50%;
    transform: scale(3);
    filter: opacity(50%);
    cursor: pointer;
    transition: 0.2s ease-in-out;
    
    &:hover {
      transform: scale(3.5);
    }
  }
  .button-left {
    @include buttons;
    left: 30px;
  }
  .button-right {
    @include buttons;
    right: 30px;
  }
}
</style>