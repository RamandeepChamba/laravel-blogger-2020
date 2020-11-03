<template>
    <div>
        <a :href="`/profiles/${follower.id}`" target="_blank">
            {{follower.name}}
        </a>
        started following you
        <br>
        <button class="btn btn-primary" type="button"
            @click="$emit('mark-as-read')"
        >
            mark as read
        </button>
    </div>
</template>

<script>
    export default {
        props: ['notificationData'],
        data() {
            return {
                follower: this.$props.notificationData.follower
            }
        },
    }
</script>
