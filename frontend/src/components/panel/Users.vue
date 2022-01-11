<template>
    <va-card class="action mb-2">
        <va-card-title>User List</va-card-title>
        <va-card-content>
            <div class="table-action row">
                <div class="flex xs9 md10 lg4 search">
                    <va-input
                        placeholder="Search by Email...."
                        v-model="search"
                        label="Search"
                    />
                </div>
                <div class="flex flex-center xs3 md2 lg2 offset--lg6 btn-actions">
                    <va-button color="success" gradient @click="userCrt()">
                        <va-icon name="group_add" />
                    </va-button>
                </div>
            </div>
            <Selected
                v-if="selectedItems.length > 0 && hasRole(3)"
                v-on:confirm="selectAction($event)"
                :selected="selected"
            />
        </va-card-content>
    </va-card>
    <va-card class="datable">
        <va-card-content>
            <va-data-table
                :items="list"
                :columns="columns"
                :current-page="page"
                :selectable="hasRole(3)"
                v-model="selectedItems"
                :clickable="true"
                :loading="loading"
                :striped="true"
            >
                <template #header(username)>Name</template>
                <template #header(id)>Actions</template>
                <template #header(image)>Avatar</template>
                <template #cell(image)="{ source: image }">
                    <va-avatar
                        v-if="image"
                        square
                        :src="'/api/data/img/users/' + image"
                    />
                    <va-avatar
                        v-else
                        square
                        :src="'/api/data/img/users/default.png'"
                    />
                </template>
                <template #cell(hash)="{ source: hash }"
                    >#{{ format.hash(hash) }}</template
                >
                <template #cell(verify)="{ source: verify }">
                    <va-icon v-if="verify" name="done" color="success" />
                    <va-icon v-else name="dangerous" color="danger" />
                </template>
                <template #cell(role)="{ source: role }">
                    <RoleBadge :role="role" />
                </template>
                <template #cell(id)="{ source: id }">
                    <va-button color="primary" gradient @click="userPrev(id)"
                        ><va-icon name="preview"
                    /></va-button>
                    <va-button
                        color="danger"
                        gradient
                        v-if="hasRole(3)"
                        @click="del(id)"
                        ><va-icon name="delete"
                    /></va-button>
                </template>
            </va-data-table>
        </va-card-content>
        <va-card-actions align="center">
            <va-pagination
                v-model="page"
                input
                :pages="totalPages"
                :per-page="list.length"
            />
        </va-card-actions>
    </va-card>
</template>

<script>
import * as validator from "/src/utils/validator";
import * as formatter from "/src/utils/formatter";
import { useUsers } from "/src/composables/useUsers";
import { defineComponent, ref, toRefs, watch } from "vue";
import useEmitter from "/src/composables/useEmitter";
import UserEditVue from "./modals/UserEdit.vue";
import UserCreateVue from "./modals/UserCreate.vue";
import Selected from "/src/components/global/shared/Selected.vue";
import RoleBadge from "/src/components/global/shared/RoleBadge.vue";
import { hasRole } from "/src/utils/store"

export default defineComponent({
    components: {
        Selected,
        RoleBadge,
    },
    async setup() {
        const val = validator;
        const format = formatter;
        const emitter = useEmitter();
        const users = useUsers();
        const search = ref(users.search);
        const loading = ref(users.loading);
        const details = toRefs(users.details);
        console.log(details.new)
        const newUser = ref({});
        const selected = ref({
            list: ["None", "Delete", "Verify"],
            values: {},
        });

        watch(
            () => details.new,
            async (newVal) => {
                if (Object.keys(newVal.value).length > 0) {
                    await users.updateUser(
                        details.user.value["id"],
                        val.create(newVal.value)
                    );
                }
            },
            { deep: true }
        );

        watch(
            () => newUser,
            async (newVal) => {
                if (Object.keys(newVal.value).length > 0) {
                    await users.createUser(val.create(newUser.value));
                    Object.assign(newUser, {});
                }
            },
            { deep: true }
        );

        await users.fetchUsers();
        const list = users.users;
        const page = users.page;
        const totalPages = users.totalPages;
        const columns = [
            { key: "image", verticalAlign: "middle" },
            { key: "email", sortable: true, verticalAlign: "middle" },
            { key: "username", sortable: true, verticalAlign: "middle" },
            { key: "hash", verticalAlign: "middle" },
            { key: "role", sortable: true, verticalAlign: "middle" },
            { key: "verify", sortable: true, verticalAlign: "middle" },
            { key: "id", verticalAlign: "middle" },
        ];

        function userPrev(index) {
            details.user.value = list.value.find((usr) => usr.id == index);
            emitter.emit("modal/open", { view: UserEditVue, data: details });
        }

        function userCrt() {
            emitter.emit("modal/open", {
                view: UserCreateVue,
                data: newUser,
            });
        }

        const selectedItems = ref([]);

        async function selectAction(action) {
            let newItemsKey = selectedItems.value.map(({ id }) => id);
            if (action.option === "Verify") {
                users.verifyUsers(newItemsKey);
            } else {
                users.deleteUsers(newItemsKey);
            }
            selectedItems.value = [];
        }

        async function del(id) {
            let index = list.value.findIndex((i) => i.id == id);
            let uuid = list.value[index].id;
            await users.deleteUsers([uuid]);
        }

        return {
            list,
            search,
            loading,
            selected,
            page,
            totalPages,
            columns,
            userPrev,
            userCrt,
            selectedItems,
            selectAction,
            del,
            format,
            hasRole
        };
    },
});
</script>
