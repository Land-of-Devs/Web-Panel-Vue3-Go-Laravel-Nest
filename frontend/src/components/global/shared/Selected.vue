<template>
    <div class="row">
        <div class="flex xs6 md4">
            <va-select
                label="Action"
                v-model="action.option"
                :options="selected.list"
            />
        </div>
        <div class="flex xs6 md4">
            <va-select
                v-if="action.option !== 'None' && selected.values[action.option]"
                label="Value"
                v-model="action.value"
                :options="selected.values[action.option]"
            />
        </div>
        <template
            v-if="
                action.option !== 'None' &&
                ((selected.values[action.option] && action.value !== 'None') ||
                    !selected.values[action.option])
            "
        >
            <div class="flex flex-center xs6 md2">
                <va-switch
                    v-model="action.confirm"
                    false-inner-label="Confirm"
                    color="warning"
                />
            </div>
            <div class="flex flex-center xs6 md2">
                <va-button
                    :disabled="!action.confirm"
                    color="warning"
                    gradient
                    @click="$emit('confirm', action)"
                >
                    <va-icon name="output" />
                </va-button>
            </div>
        </template>
    </div>
</template>

<script>
import { defineComponent, reactive } from "vue";

export default defineComponent({
    props: ["selected"],
    emits: ["confirm"],
    setup() {
        const action = reactive({
            option: "None",
            confirm: false,
            value: "None",
        });
        return {
            action,
        };
    },
});
</script>
