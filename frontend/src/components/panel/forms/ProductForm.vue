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
    <va-button text-color="primary" class="mr-1" @click="$emit('exit')" flat
        >Cancel</va-button
    >
    <va-button
        v-if="type === 'update'"
        text-color="white"
        class="ml-1"
        @click="formRef.validate() && update()"
        gradient
        >Update</va-button
    >
    <va-button
        v-else-if="type === 'create'"
        text-color="white"
        class="ml-1"
        @click="formRef.validate() && create()"
        gradient
        >Create</va-button
    >
</template>

<script>
import { reactive, ref } from "vue";
import * as validator from "/src/utils/validator";
export default {
    props: ["product", "type"],
    emits: ["exit"],
    setup(props) {
        const val = validator;
        const formRef = ref(null);
        const state = reactive({
            form: {
                name: "",
                image: [],
                description: "",
                price: null,
            },
            rules: {
                name: [val.rules.required, val.rules.name, val.rules.string],
                description: [
                    val.rules.required,
                    val.rules.string,
                ],
                price: [val.rules.number],
                image: val.rules.file,
            },
        });

        if (props.type == "update") {
            state.form = {
                name: props.product.name,
                description: props.product.description,
                price: props.product.price,
            };
        }

        function update() {
            let form = new FormData();
            state.form.price = parseInt(state.form.price);
            form = val.update(props.product, state.form);
            console.log(form)
        }

        function create(){

        }

        return {
            state,
            formRef,
            update,
            create
        };
    },
};
</script>