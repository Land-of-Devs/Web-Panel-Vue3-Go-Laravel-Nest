<template>
    <div class="action">
        <div class="table-action">
            <div class="search">
                <va-input
                    placeholder="Search by Email...."
                    v-model="search"
                    label="Search"
                />
            </div>
            <div class="btn-actions">
                <va-button color="success" gradient @click="userCrt()">
                    <va-icon name="group_add" />
                </va-button>
            </div>
        </div>
        <div class="selected">
            <Selected
                v-if="selectedItems.length > 0 && role == 3"
                v-on:confirm="selectAction($event)"
                :selected="selected"
            />
        </div>
    </div>
    <div class="datable">
        <va-data-table
            :items="list"
            :columns="columns"
            :current-page="page"
            :selectable="role == 3"
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
                    v-if="role == 3"
                    @click="del(id)"
                    ><va-icon name="delete"
                /></va-button>
            </template>
        </va-data-table>
        <va-pagination
            v-model="page"
            input
            :pages="totalPages"
            :per-page="list.length"
        />
    </div>
</template>

<script>
import * as validator from "/src/utils/validator";
import * as formatter from "/src/utils/formatter";
import { useUsers } from "/src/composables/useUsers";
import { defineComponent, ref, toRefs, watch, computed } from "vue";
import useEmitter from "/src/composables/useEmitter";
import UserEditVue from "./modals/UserEdit.vue";
import UserCreateVue from "./modals/UserCreate.vue";
import Selected from "/src/components/global/shared/Selected.vue";
import RoleBadge from "/src/components/global/shared/RoleBadge.vue";
import { useStore } from "vuex";

export default defineComponent({
    components: {
        Selected,
        RoleBadge,
    },
    async setup() {
        const store = useStore();
        const role = computed(() => store.getters["user/getRole"]);
        const val = validator;
        const format = formatter;
        const emitter = useEmitter();
        const users = useUsers();
        const search = ref(users.search);
        const loading = ref(users.loading);
        const details = toRefs(users.details);
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
            role,
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
        };
    },
});
</script>

<style lang="scss" scoped>
.datable {
    max-width: 100%;
    display: flex;
    flex-direction: column;
    align-items: center;
}
.action {
    .table-action {
        margin-top: 20px;
        display: flex;
        flex-direction: row;
        justify-content: space-between;
        .search {
            margin-left: 10px;
        }
        .btn-actions {
            margin-right: 10px;
        }
    }
    .selected {
        display: flex;
        justify-content: space-evenly;
    }
}
</style>
