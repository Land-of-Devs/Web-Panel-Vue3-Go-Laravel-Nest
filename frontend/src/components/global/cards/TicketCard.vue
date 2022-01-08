<template>
    <va-card>
        <va-card-content>
            <div>ID: {{ ticket.id }}</div>
            <br />
            <div>Title: {{ ticket.title }}</div>
            <br />
            <div>
                Creator:
                {{ ticket.user.username }}#{{ format.hash(ticket.user.hash) }}
            </div>
            <br />
            <div>Status: <StatusBadge :status="ticket.status" /></div>
            <br />
            <div>Type: <TicketTypeBadge :type="ticket.type" /></div>
            <br />
            <div>
                <ul>
                    Content:
                    <br />
                    <br />
                    <li>
                        <ContentType
                            :content="JSON.parse(ticket.content)"
                            :type="ticket.type"
                        />
                    </li>
                </ul>
            </div>
            <br />
            <div>Created: {{ format.date(ticket.created_at) }}</div>
            <br />
        </va-card-content>
        <va-card-actions>
            <va-button color="danger" @click="$emit('close')">X</va-button>
        </va-card-actions>
    </va-card>
</template>

<script>
import * as formatter from "/src/utils/formatter";
import StatusBadge from "../shared/StatusBadge";
import TicketTypeBadge from "../shared/TicketTypeBadge";
import ContentType from "../tickets/ContentType";
export default {
    components: {
        StatusBadge,
        TicketTypeBadge,
        ContentType,
    },
    props: ["ticket"],
    emits: ["close"],
    setup() {
        const format = formatter;
        return {
            format,
        };
    },
};
</script>