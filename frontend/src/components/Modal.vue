<template>
  <component :is="view" v-model:opened="opened" @close="view = undefined"></component>
</template>

<script>
import { shallowRef } from 'vue';
import useEmitter from '../composables/useEmitter';

export default {
  setup() {
    const emitter = useEmitter();
    const view = shallowRef(undefined);
    const opened = shallowRef(false);

    emitter.on('modal/open', (v) => {
      view.value = v;
      opened.value = true;
    });

    emitter.on('modal/close', () => {
      view.value = undefined;
      opened.value = false;
    });

    return {
      view,
      opened
    }

  },
}
</script>
