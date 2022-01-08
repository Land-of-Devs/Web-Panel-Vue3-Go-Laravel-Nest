<template>
    <div>
        - New Name:  [ {{ content.newName }} ]
    </div>
    <br />
    <div>
        - New Email:  [ {{ content.newEmail }} ]
    </div>
    <br />
    <div>
        - New Password:  [ {{ content.newPasswordHash }} ]
    </div>
</template>

<script>
export default {
    props: ["content"],
};
</script>