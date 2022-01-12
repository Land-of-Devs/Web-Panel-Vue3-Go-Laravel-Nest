<template>
    <va-card>
        <va-card-title>{{ user.id }}</va-card-title>
        <va-image :src="format.getUserImage(user)"/>
        <va-card-content>
            <div>User: {{ user.username }}#{{ format.hash(user.hash) }}</div>
            <br />
            <div>Email: {{ user.email }}</div>
            <br />
            <div>Role: <RoleBadge :role="user.role" /></div>
            <br />
            <div>
                Verify:
                <va-icon v-if="user.verify" name="done" color="success" />
                <va-icon v-else name="dangerous" color="danger" />
            </div>
            <br />
            <div>Joined: {{ format.date(user.joined || user.created_at) }}</div>
            <br />
        </va-card-content>
        <va-card-actions>
            <va-button v-if="canEdit" color="primary" @click="$emit('edit')"
                >Edit</va-button
            >
            <va-button color="danger" @click="$emit('close')">X</va-button>
        </va-card-actions>
    </va-card>
</template>

<script>
import * as formatter from "/src/utils/formatter";
import RoleBadge from "../shared/RoleBadge";
export default {
    components: {
        RoleBadge,
    },
    props: ["user", "canEdit"],
    emits: ["edit", "close"],
    setup() {
        const format = formatter;
        return {
            format,
        };
    },
};
</script>