<template>
    <div class="action">
        <div class="selector">
            <va-select
                label="Status"
                v-model="status"
                class="mr-5 ml-5"
                :options="[
                    'All',
                    'Pending',
                    'Accepted',
                    'Cancelled',
                    'Complete',
                ]"
            />
            <va-select
                label="Type"
                v-model="type"
                class="mr-5 ml-5"
                :options="[
                    'All',
                    'Create Product',
                    'Report Product',
                    'Update User',
                ]"
            />
        </div>

        <div class="selected mt-3">
            <Selected
                v-if="selectedItems.length > 0"
                v-on:confirm="selectAction($event)"
                :selected="selected"
            />
        </div>
    </div>
    <div class="datable">
        <va-data-table
            :items="list"
            :current-page="page"
            :selectable="true"
            v-model="selectedItems"
            :clickable="true"
            :loading="loading"
            :striped="true"
            :columns="columns"
        >
            <template #header(id)>Actions</template>
            <template #cell(hash)="{ source: hash }"
                >#{{ format.hash(hash) }}</template
            >
            <template #cell(verify)="{ source: verify }">
                <va-icon v-if="verify" name="done" color="success" />
                <va-icon v-else name="dangerous" color="danger" />
            </template>
            <template #cell(user)="{ source: user }">
                <va-button
                    v-if="user.username"
                    color="#ffac0a"
                    gradient
                    @click="creatorPrev(user)"
                    >{{ user.username }}</va-button
                >
            </template>
            <template #cell(role)="{ source: role }">
                <RoleBadge :role="role" />
            </template>
            <template #cell(id)="{ source: id }">
                <va-button color="primary" gradient @click="ticketPrev(id)"
                    ><va-icon name="preview"
                /></va-button>
                <va-button color="danger" gradient @click="del(id)"
                    ><va-icon name="delete"
                /></va-button>
            </template>
            <template #cell(status)="{ source: status }">
                <StatusBadge :status="status" />
            </template>
            <template #cell(type)="{ source: type }">
                <TicketTypeBadge :type="type" />
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
import * as formatter from "/src/utils/formatter";
import { useTickets } from "/src/composables/useTickets";
import { defineComponent, ref, reactive } from "vue";
import useEmitter from "/src/composables/useEmitter";
import Selected from "/src/components/global/shared/Selected.vue";
import StatusBadge from "/src/components/global/shared/StatusBadge.vue";
import TicketPreviewVue from "./modals/TicketPreview.vue";
import UserPreviewVue from "./modals/UserPreview.vue";
import TicketTypeBadge from "/src/components/global/shared/TicketTypeBadge.vue"

export default defineComponent({
    components: {
        Selected,
        StatusBadge,
        TicketTypeBadge
    },
    async setup() {
        const format = formatter;
        const emitter = useEmitter();
        const tickets = useTickets();
        const status = ref(tickets.status);
        const type = ref(tickets.type);
        const loading = ref(tickets.loading);
        const details = reactive({ ticket: {} });
        const selected = ref({
            list: ["None", "Delete", "Status"],
            values: {
                Status: [
                    "None",
                    "Pending",
                    "Accepted",
                    "Cancelled",
                    "Complete",
                ],
            },
        });

        await tickets.fetchTickets();
        const list = tickets.tickets;
        const page = tickets.page;
        const totalPages = tickets.totalPages;
        const columns = [
            { key: "title", verticalAlign: "middle" },
            { key: "type", sortable: true, verticalAlign: "middle" },
            { key: "user", sortable: true, verticalAlign: "middle" },
            { key: "status", verticalAlign: "middle" },
            { key: "id", verticalAlign: "middle" },
        ];

        function ticketPrev(index) {
            details.ticket = list.value.find(
                (ticket) => ticket.id == index
            );
            emitter.emit("modal/open", {
                view: TicketPreviewVue,
                data: details,
            });
        }

        function creatorPrev(user) {
            emitter.emit("modal/open", { view: UserPreviewVue, data: user });
        }
        const selectedItems = ref([]);

        async function selectAction(action) {
            let newItemsKey = selectedItems.value.map(({ id }) => id);
            if (action.option === "Status") {
                await tickets.statusTickets(newItemsKey, action.value);
            } else {
                await tickets.deleteTickets(newItemsKey);
            }
            selectedItems.value = [];
        }

        async function del(id) {
            let index = list.value.findIndex((i) => i.id == id);
            let ident = list.value[index].id;
            await tickets.deleteTickets([ident]);
        }

        return {
            list,
            status,
            loading,
            selected,
            page,
            totalPages,
            type,
            columns,
            ticketPrev,
            selectedItems,
            selectAction,
            del,
            format,
            creatorPrev,
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
    margin-top: 20px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;

    .selector {
        display: flex;
        flex-direction: row;
        justify-content: space-between;
    }
    .selected {
        display: flex;
        justify-content: space-evenly;
    }
}
</style>
