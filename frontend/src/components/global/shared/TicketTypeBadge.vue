<template>
    <va-badge
        v-if="type == 'Create Product'"
        text="Create Product"
        color="success"
    />
    <va-badge
        v-else-if="type == 'Report Product'"
        text="Report Product"
        color="danger"
    />
    <va-badge
        v-else-if="type == 'Update User'"
        text="Update User"
        color="secondary"
    />
</template>

<script>
import { defineComponent } from "vue";

export default defineComponent({
    props: ["type"],
});
</script>