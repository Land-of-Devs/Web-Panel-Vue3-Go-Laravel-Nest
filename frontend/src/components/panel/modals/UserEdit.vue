<template>
    <va-modal
        title="User"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
        <template v-if="state.edit">
            <UserForm
                v-on:exit="state.edit = false"
                v-on:close="$emit('close')"
                :user="dataM"
                :action="'update'"
            />
        </template>
        <template v-else>
            <UserCard
                v-on:edit="state.edit = true"
                v-on:close="$emit('close')"
                :user="dataM.user.value"
                :canEdit="true"
            />
        </template>
    </va-modal>
</template>

<script>
import { reactive } from "vue";
import UserForm from "../forms/UserForm.vue";
import UserCard from "/src/components/global/cards/UserCard.vue";
export default {
    components: {
        UserForm,
        UserCard,
    },
    props: ["opened", "dataM"],
    setup(props) {
        const state = reactive({
            edit: false,
        });
        console.log(props.dataM)
        return {
            state
        };
    },
};
</script>