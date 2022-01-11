<template>
    <va-card class="action mb-2">
        <va-card-title>Ticket List</va-card-title>
        <va-card-content>

            <div class="selector row">
                <div class="flex xs6">
                    <va-select
                        label="Status"
                        v-model="status"
                        :options="[
                            'All',
                            'Pending',
                            'Accepted',
                            'Cancelled',
                            'Complete',
                        ]"
                />
                </div>
                <div class="flex xs6">
                    <va-select
                        label="Type"
                        v-model="type"
                        :options="[
                            'All',
                            'Create Product',
                            'Report Product',
                            'Update User',
                        ]"
                    />
                </div>
            </div>
            <Selected
                v-if="selectedItems.length > 0"
                v-on:confirm="selectAction($event)"
                :selected="selected"
            />
        </va-card-content>
    </va-card>
    <va-card class="datable">
        <va-card-content>
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
                        v-if="user && user.username"
                        color="#ffac0a"
                        gradient
                        @click="creatorPrev(user)"
                        >{{ user && user.username }}</va-button
                    >
                    <div v-else>None</div>
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
