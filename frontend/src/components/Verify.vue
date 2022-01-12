<template>
    <template v-if="toast.active && $vaToast.init(toast.modal)" ></template>
</template>

<script>
import { reactive } from "@vue/reactivity";
import { useRoute, useRouter } from "vue-router";
import { verify } from "/src/services/auth";

export default {
    setup() {
        const $route = useRoute();
        const $router = useRouter();
        const token = $route.params.token;
        const toast = reactive({
            active: false,
            modal: {
                message: "",
                color: "",
                title: "",
            },
        });
        async function verifyUser(token) {
            await verify(token)
                .then(() => {
                    toast.modal = {
                        message: "User was Verify!",
                        color: "success",
                        title: "Verify:",
                    };
                    toast.active = true;
                    $router.replace("/shop");
                })
                .catch(() => {
                    toast.modal = {
                        message: "Token Invalid!",
                        color: "danger",
                        title: "Invalid:",
                    };
                    toast.active = true;
                    $router.replace("/");
                });
        }

        verifyUser(token);

        return {
            toast,
        };
    },
};
</script>