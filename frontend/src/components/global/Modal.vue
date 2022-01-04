<template>
  <component :is="view" v-model:opened="opened" v-model:dataM="data" @close="view = undefined"></component>
</template>

<script>
import { shallowRef } from 'vue';
import useEmitter from '../../composables/useEmitter';

export default {
  setup() {
    const emitter = useEmitter();
    const view = shallowRef(undefined);
    const opened = shallowRef(false);
    const data = shallowRef(undefined);

    emitter.on('modal/open', (config) => {
      view.value = config.view;
      opened.value = true;
      data.value = config.data;
    });

    emitter.on('modal/close', () => {
      view.value = undefined;
      opened.value = false;
      data.value = undefined;
    });

    return {
      view,
      opened,
      data
    }

  },
}
</script>
