//import { getCurrentInstance } from 'vue'
import mitt from 'mitt';

const emitter = mitt();

export default function useEmitter() {
    return emitter;
}
