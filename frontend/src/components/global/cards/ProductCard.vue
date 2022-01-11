<template>
    <va-card>
        <va-card-title>{{ product.slug }}</va-card-title>
        <va-image :src="'/api/data/img/products/' + product.image" />
        <va-card-content>
            <div>ID: {{ product.id }}</div>
            <br />
            <div>Name: {{ product.name }}</div>
            <br />
            <div>Price: {{ product.price }}€</div>
            <br />
            <div>Description: {{ product.description }}</div>
            <br />

            <div>
                Creator:
                {{ product.user ? product.user.username + '#' + format.hash(product.user.hash) : 'None' }}
            </div>
            <br />
            <div>Status: <StatusBadge :status="product.status" /></div>
            <br />
            <div>Created: {{ format.date(product.created_at) }}</div>
            <br />
            <div>Updated: {{ format.date(product.updated_at) }}</div>
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
import StatusBadge from "../shared/StatusBadge";
export default {
    components: {
        StatusBadge,
    },
    props: ["product", "canEdit"],
    emits: ["edit", "close"],
    setup() {
        const format = formatter;
        return {
            format,
        };
    },
};
</script>