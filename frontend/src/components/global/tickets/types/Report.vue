<template>
<div> - ID Products:  [ {{ content.productId }} ]</div>
<div> - Message:  [ {{ content.message }} ]</div>
</template>

<script>
export default {
    props: ["content"]
};
</script>