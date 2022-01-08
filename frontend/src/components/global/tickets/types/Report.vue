<template>
<div> - ID Products:  [ {{ content.id }} ]</div>
<div> - Message:  [ {{ content.message }} ]</div>
</template>

<script>
export default {
    props: ["content"]
};
</script>