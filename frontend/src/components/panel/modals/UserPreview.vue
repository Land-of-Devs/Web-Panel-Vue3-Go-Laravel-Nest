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
import UserCard from "../cards/UserCard.vue";
export default {
    components: {
        UserCard
    },
    props: ["opened", "dataM"],
    setup(props) {

        console.log(props.dataM, "Es")
        return {};
    }
};
</script>