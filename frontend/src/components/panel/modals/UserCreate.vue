<template>
    <va-modal
        title="User"
        :modelValue="opened"
        ok-text="Success"
        :dataM="dataM"
        hide-default-actions
        size="large"
    >
        <UserForm
            v-on:exit="state.edit = false"
            v-on:close="$emit('close')"
            :user="dataM"
            :action="'create'"
        />
    </va-modal>
</template>

<script>
import UserForm from "../forms/UserForm.vue";
export default {
    components: {
        UserForm,
    },
    props: ["opened", "dataM"],
};
</script>