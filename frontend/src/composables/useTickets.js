import * as ticketService from '../services/tickets';
import { ref, watch } from 'vue';

export function useTickets() {

    //------[ VARS ]------\\
    const tickets = ref([]);
    const ticketsCount = ref(0);
    const page = ref(1);
    const status = ref('All');
    const type = ref('All');
    const totalPages = ref(1);
    const loading = ref(false);

    //------[ PRODUCT LIST ]------\\
    async function fetchTickets() {

        tickets.value = [];
        let responsePromise = await ticketService.ticketList(
            page.value, status.value !== 'All' ? status.value : null,
            type.value !== 'All' ? type.value : null
        );
        if (responsePromise !== null) {
            const response = responsePromise;
            tickets.value = response.list;
            ticketsCount.value = response.total;
            totalPages.value = response.totalPages;
            loading.value = false;

            console.log(tickets.value)
        } else {
            throw new Error(`Nothing was found!`);
        }
    }

    //------[ ACTIONS ]------\\

    //Create
    const statusTickets = async (indexs, value) => {
        let result = await ticketService.status({ ids: indexs, status: value });
        if (result.count > 0) {
            newData();
            return result.efected
        }
    }

    const deleteTickets = async (indexs) => {
        let result = await ticketService.del({ ids: indexs });
        if (result.count > 0) {
            newData();
            return result.efected
        }
    }

    //------[ WATCHERS AND FUNC ]------\\
    const newData = async () => {
        loading.value = true;
        if (page.value !== 1) changePage(1)
        else await fetchTickets()
    };

    const changePage = (num) => {
        page.value = num;
    };

    watch(page, fetchTickets);

    watch(status, newData);

    watch(type, newData);

    return {
        fetchTickets,
        tickets,
        ticketsCount,
        page,
        totalPages,
        changePage,
        deleteTickets,
        statusTickets,
        status,
        type
    };
}