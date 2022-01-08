<template>
    <va-modal
        title="Ticket"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
        <TicketCard v-on:close="$emit('close')" :ticket="dataM.ticket" />
    </va-modal>
</template>

<script>
import TicketCard from "/src/components/global/cards/TicketCard.vue";
export default {
    components: {
        TicketCard,
    },
    props: ["opened", "dataM"],
};
</script>