<template>
    <va-form ref="formRef">
        <div class="form-err">{{ state.err }}</div>
        <va-file-upload
            v-model="state.form.image"
            :type="'single'"
            file-types="image/*"
            :disabled="state.rules.image(state.form.image)"
        />
        <va-input
            :rules="state.rules.username"
            label="username"
            class="mb-4"
            type="text"
            v-model="state.form.username"
            @keyup.enter="formRef.validate()"
        >
        </va-input>
        <va-input
            :rules="state.rules.email"
            label="Email"
            class="mb-4"
            v-model="state.form.email"
            type="text"
            @keyup.enter="formRef.validate()"
        ></va-input>
        <va-input
            :rules="state.rules.password"
            label="Password"
            class="mb-4"
            v-model="state.form.password"
            type="password"
            @keyup.enter="formRef.validate()"
        ></va-input>
        <va-input
            :rules="state.rules.role"
            label="Role"
            class="mb-4"
            v-model="state.form.role"
            type="number"
            @keyup.enter="formRef.validate()"
        ></va-input>
        <va-switch
            v-model="state.form.verify"
            false-inner-label="Verify"
            true-inner-label="Unverify"
            color="primary"
            class="mb-4"
        />
    </va-form>
    <template v-if="action === 'update'">
        <va-button text-color="primary" class="mr-1" @click="$emit('exit')" flat
            >Cancel</va-button
        >
        <va-button
            text-color="white"
            class="ml-1"
            @click="
                formRef.validate() && (update(), $vaToast.init(state.toast))
            "
            gradient
            >Update</va-button
        >
    </template>
    <template v-else-if="action === 'create'">
        <va-button
            text-color="primary"
            class="mr-1"
            @click="$emit('close')"
            flat
            >Cancel</va-button
        >
        <va-button
            text-color="white"
            class="ml-1"
            @click="
                formRef.validate() && (create(), $vaToast.init(state.toast))
            "
            gradient
            >Create</va-button
        >
    </template>
</template>

<script>
import { reactive, ref, toRef } from "vue";
import * as validator from "/src/utils/validator";
export default {
    props: ["user", "action"],
    emits: ["exit", "close"],
    setup(props, context) {
        const createForm = reactive(props.user);
        console.log(props.user)
        const newForm = toRef(props.user, "new");
        const val = validator;
        const formRef = ref({});
        const state = reactive({
            form: {
                username: "",
                image: [],
                email: "",
                password: "",
                role: 0,
                verify: false,
            },
            rules: {
                username: [val.rules.required, val.rules.username],
                email: [val.rules.required, val.rules.email],
                password: [val.rules.password],
                role: [val.rules.number],
                image: val.rules.file,
            },
            toast: {
                message: "",
                color: "",
                title: "",
            },
        });

        if (props.action == "update") {
            state.form = {
                username: props.user.user.value.username,
                email: props.user.user.value.email,
                role: props.user.user.value.role,
                verify: props.user.user.value.verify,
            };
            state.rules.password = [val.rules.password];
        }

        async function update() {
            let obj = {};
            state.form.role = parseInt(state.form.role);
            obj = val.update(props.user.user.value, state.form);
            if (!obj) {
                state.toast = {
                    message: "There isn't changes!",
                    color: "warning",
                    title: "Warning:",
                };
            } else {
                state.toast = {
                    message: "User Updated!",
                    color: "success",
                    title: "Success:",
                };
                context.emit("exit");
                newForm.value = obj;
            }
        }

        function create() {
            state.form.role = parseInt(state.form.role);
            if (!state.form) {
                state.toast = {
                    message: "Couldn't create a Object!",
                    color: "warning",
                    title: "Warning:",
                };
            } else {
                state.toast = {
                    message: "User Created!",
                    color: "success",
                    title: "Success:",
                };
                context.emit("close");
                createForm.value = state.form;
            }
        }

        return {
            state,
            formRef,
            update,
            create,
        };
    },
};
</script>