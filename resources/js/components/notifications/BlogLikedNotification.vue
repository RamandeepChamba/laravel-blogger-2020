<template>
    <div>
        <a :href="`/profiles/${liker.id}`" target="_blank">
            {{liker.name}}
        </a>
        liked your
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
                liker: this.$props.notificationData.liker,
                blog: this.$props.notificationData.blog,
            }
        },
        methods: {
        }
    }
</script>
