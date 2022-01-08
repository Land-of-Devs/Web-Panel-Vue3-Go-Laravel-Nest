<template>
    <va-badge v-if="status == 'Pending'" text="Pending" color="secondary" />
    <va-badge
        v-else-if="status == 'Complete'"
        text="Complete"
        color="success"
    />
    <va-badge
        v-else-if="status == 'Accepted'"
        text="Accepted"
        color="primary"
    />
    <va-badge
        v-else-if="status == 'Cancelled'"
        text="Cancelled"
        color="danger"
    />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: ["status"],
});
</script>