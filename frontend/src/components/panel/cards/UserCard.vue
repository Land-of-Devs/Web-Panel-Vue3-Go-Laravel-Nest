<template>
    <va-card>
        <va-card-title>{{ user.id }}</va-card-title>
        <va-image v-if="user.image" :src="'/api/data/img/users/' + user.image" />
        <va-image v-else :src="'/api/data/img/users/default.png'"/>
        <va-card-content>
            <div>User: {{ user.username }}#{{ format.hash(user.hash) }}</div>
            <br />
            <div>Email: {{ user.email }}</div>
            <br />
            <div>Verify: 
                <va-icon v-if="user.verify" name='done' color="success" />
                <va-icon v-else name='dangerous' color="danger" />
            </div>
            <br />
            <div>Joined: {{ format.date(user.created_at) }}</div>
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
import * as formatter from "/src/utils/formatter"
export default {
    props: ["user", "canEdit"],
    emits: ["edit", "close"],
    setup(){
        const format = formatter;
        return {
            format
        }
    }
};
</script>