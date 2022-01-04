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
            <div>
                Creator:
                {{ product.user.username }}#{{ format.hash(product.user.hash) }}
            </div>
            <br />
            <div>Status: {{ product.status }}</div>
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
export default {
    props: ["product", "canEdit"],
    emits: ["edit"],
    setup(){
        const format = formatter
        return {
            format
        };
    }
};
</script>