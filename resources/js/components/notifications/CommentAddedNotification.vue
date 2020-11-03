<template>
    <div>
        <a :href="`/profiles/${commenter.id}`" target="_blank">
            {{commenter.name}}
        </a>
        <a :href="`/blogs/${blog.id}?highlightedComment=${comment.id}`" 
            target="_blank">commented </a>
        <mark>
            {{comment.comment.substr(0,20) + '...'}}
        </mark>
        on your
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
                commenter: this.$props.notificationData.commenter,
                blog: this.$props.notificationData.blog,
                comment: this.$props.notificationData.comment,
            }
        },
        methods: {
        }
    }
</script>
