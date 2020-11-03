<template>
    <div>
        <a :href="`/profiles/${leader.id}`" target="_blank">
            {{leader.name}}
        </a>
        created a new
        <a :href="`/blogs/${blog.id}`" target="_blank">blog </a>
        <mark>
            {{blog.title.substr(0,20) + '...'}}
        </mark>
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
                leader: this.$props.notificationData.leader,
                blog: this.$props.notificationData.blog,
            }
        },
        methods: {
        }
    }
</script>
