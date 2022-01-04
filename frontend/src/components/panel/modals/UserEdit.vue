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
                :user="dataM.user"
                :type="'update'"
            />
        </template>
        <template v-else>
            <UserCard
                v-on:edit="state.edit = true"
                v-on:close="$emit('close')"
                :product="dataM.user"
                :canEdit="true"
            />
        </template>
    </va-modal>
</template>

<script>
import { reactive } from "vue";
import UserForm from "../forms/UserForm.vue";
import UserCard from "../cards/UserCard.vue";
export default {
    components: {
        UserForm,
        UserCard,
    },
    props: ["opened", "dataM"],
    setup() {
        const state = reactive({
            edit: false,
        });

        return {
            state
        };
    },
};
</script>