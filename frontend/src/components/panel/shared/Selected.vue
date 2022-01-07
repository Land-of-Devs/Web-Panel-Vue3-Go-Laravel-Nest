<template>
    <div class="selectable">
        <va-select
            label="Action"
            v-model="action.option"
            :options="selected.list"
            width="40%"
        />
        <va-select
            v-if="action.option !== 'None' && selected.values[action.option]"
            label="Value"
            v-model="action.value"
            :options="selected.values[action.option]"
            width="40%"
        />
    </div>
    <div class="confirm">
        <template
            v-if="
                action.option !== 'None' &&
                ((selected.values[action.option] && action.value !== 'None') ||
                    !selected.values[action.option])
            "
        >
            <va-switch
                v-model="action.confirm"
                false-inner-label="Confirm"
                color="warning"
            />
            <va-button
                v-if="action.confirm"
                color="warning"
                gradient
                @click="$emit('confirm', action)"
            >
                <va-icon name="output" />
            </va-button>
            <va-button v-else disabled color="warning" gradient>
                <va-icon name="output" />
            </va-button>
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


<style lang="scss" scoped>
.selectable {
    display: flex;
}
.confirm {
    display: flex;
}
</style>