<template>
    <va-badge v-if="role == 3" text="ADMIN" color="danger" />
    <va-badge v-if="role == 2" text="STAFF" color="warning" />
    <va-badge v-if="role == 1" text="CLIENT" color="primary" />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: ["role"],
});
</script>