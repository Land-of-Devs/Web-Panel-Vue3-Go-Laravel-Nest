<template>
    <va-modal
        title="User"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
        <UserCard
            v-on:close="$emit('close')"
            :user="dataM"
            :canEdit="false"
        />
    </va-modal>
</template>

<script>
import UserCard from "/src/components/global/cards/UserCard.vue";
export default {
    components: {
        UserCard
    },
    props: ["opened", "dataM"],
    setup() {
        return {};
    }
};
</script>