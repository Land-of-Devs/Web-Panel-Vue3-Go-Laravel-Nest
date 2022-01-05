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
            :rules="[state.rules.name]"
            label="nombre"
            class="mb-4"
            type="text"
            v-model="state.form.name"
        >
        </va-input>
        <va-input
            :rules="state.rules.description"
            label="Description"
            class="mb-4"
            v-model="state.form.description"
            type="textarea"
            @keyup.enter="formRef.validate()"
        ></va-input>
        <va-input
            :rules="state.rules.price"
            label="Price"
            class="mb-4"
            v-model="state.form.price"
            type="number"
            @keyup.enter="formRef.validate()"
        ></va-input>
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
    props: ["product", "action"],
    emits: ["exit", "close"],
    setup(props, context) {
        const createForm = reactive(props.product);
        const newForm = toRef(props.product, "new");
        const val = validator;
        const formRef = ref({});
        const state = reactive({
            form: {
                name: "",
                image: [],
                description: "",
                price: 0,
            },
            rules: {
                name: [val.rules.required, val.rules.name, val.rules.string],
                description: [val.rules.required, val.rules.string],
                price: [val.rules.number],
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
                name: props.product.product.value.name,
                description: props.product.product.value.description,
                price: props.product.product.value.price,
            };
        }

        async function update() {
            let obj = {};
            state.form.price = parseInt(state.form.price);
            obj = val.update(props.product.product.value, state.form);
            if (!obj) {
                state.toast = {
                    message: "There isn't changes!",
                    color: "warning",
                    title: "Warning:",
                };
            } else {
                state.toast = {
                    message: "Product Updated!",
                    color: "success",
                    title: "Success:",
                };
                context.emit("exit");
                newForm.value = obj;
            }
        }

        function create() {
            state.form.price = parseInt(state.form.price);
            if (!state.form) {
                state.toast = {
                    message: "Couldn't create a Object!",
                    color: "warning",
                    title: "Warning:",
                };
            } else {
                state.toast = {
                    message: "Product Created!",
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