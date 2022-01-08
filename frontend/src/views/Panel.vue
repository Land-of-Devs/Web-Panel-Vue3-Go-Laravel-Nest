<template>
    <div class="panel">
        <va-sidebar
            text-color="white"
            color="primary"
            class="panel-sidebar"
            gradient
        >
            <va-sidebar-item>
                <va-sidebar-item-content>
                    <va-sidebar-item-title class="sidebar-title">
                        Panel Admin
                    </va-sidebar-item-title>
                </va-sidebar-item-content>
            </va-sidebar-item>

            <va-sidebar-item :to="{ name: 'Panel.Dashboard' }">
                <va-sidebar-item-content>
                    <va-icon name="dashboard" />
                    <va-sidebar-item-title> Dashboard </va-sidebar-item-title>
                </va-sidebar-item-content>
            </va-sidebar-item>

            <va-sidebar-item :to="{ name: 'Panel.Tickets' }">
                <va-sidebar-item-content>
                    <va-icon name="description" />
                    <va-sidebar-item-title> Tickets </va-sidebar-item-title>
                </va-sidebar-item-content>
            </va-sidebar-item>

            <va-sidebar-item :to="{ name: 'Panel.Products' }">
                <va-sidebar-item-content>
                    <va-icon name="local_mall" />
                    <va-sidebar-item-title> Products </va-sidebar-item-title>
                </va-sidebar-item-content>
            </va-sidebar-item>

            <va-sidebar-item :to="{ name: 'Panel.Users' }" v-if="role >= 3">
                <va-sidebar-item-content>
                    <va-icon name="person" />
                    <va-sidebar-item-title> Users </va-sidebar-item-title>
                </va-sidebar-item-content>
            </va-sidebar-item>

            <va-sidebar-item to="/">
                <va-sidebar-item-content>
                    <va-icon name="meeting_room"></va-icon>
                </va-sidebar-item-content>
            </va-sidebar-item>
        </va-sidebar>
        <div class="panel-body">
            <suspense>
                <template #default>
                    <router-view></router-view>
                </template>
                <template #fallback>
                    <div>Loading.....</div>
                </template>
            </suspense>
        </div>
    </div>
</template>

<script>
import { computed } from "vue";
import { useStore } from "vuex";
export default {
    setup() {
        const store = useStore();
        const role = computed(() => store.getters["user/getRole"]);

        return {
            role,
        };
    },
};
</script>

<style lang="scss" scoped>
.panel {
    display: flex;
    height: 100%;
    width: 100%;

    .panel-sidebar {
        height: 100%;
        width: 20%;

        .sidebar-title {
            font-size: 15pt;
            font-weight: bold;
        }

        .back {
            margin-top: auto;
        }
    }

    .panel-body {
        display: flex;
        flex-direction: column;
        flex-grow: 1;
        width: 80%;
    }
}
</style>
