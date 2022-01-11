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
                    <va-sidebar-item-title
                        class="sidebar-title sidebar-title-minimized"
                    >
                        PA
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

            <va-sidebar-item :to="{ name: 'Panel.Users' }" v-if="hasRole(3)">
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
        <div class="panel-body layout fluid gutter--xl">
            <suspense>
                <template #default>
                    <router-view></router-view>
                </template>
                <template #fallback>
                    <va-card class="flex xs12 md6 offset--md3">
                        <va-card-content>
                            <va-progress-bar indeterminate
                                >Loading...</va-progress-bar
                            >
                        </va-card-content>
                    </va-card>
                </template>
            </suspense>
        </div>
    </div>
</template>

<script>
import { hasRole } from "/src/utils/store";
export default {
    setup() {
        return {
            hasRole,
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
        max-height: 100vh;
        overflow-y: auto;
    }
}

.sidebar-title-minimized {
    display: none;
}

@media screen and (max-width: 576px) {
    .va-sidebar {
        width: min-content !important;
    }

    .va-sidebar-item-title {
        display: none;
    }

    .sidebar-title-minimized {
        display: block;
    }
}
</style>

<style lang="scss">
.va-pagination__input {
    min-width: 50px;
}
</style>