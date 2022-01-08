<template>
    <Create v-if="type === 'Create Product'" :content="content" />
    <Report v-else-if="type === 'Report Product'" :content="content" />
    <Update v-else-if="type === 'Update User'" :content="content" />
</template>

<script>
import Create from "/src/components/global/tickets/types/Create";
import Report from "/src/components/global/tickets/types/Report.vue"
import Update from "/src/components/global/tickets/types/Update";
export default {
    components: {
        Create,
        Report,
        Update,
    },
    props: ["content", "type"],
};
</script>