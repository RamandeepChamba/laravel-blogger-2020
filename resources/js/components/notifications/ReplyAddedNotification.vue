<template>
    <div>
        <a :href="`/profiles/${replier.id}`" target="_blank">
            {{replier.name}}
        </a>
        <a :href="`/blogs/${blog.id}?highlightedComment=${reply.id}`" 
            target="_blank">replied </a>
        <mark>
            {{reply.comment.substr(0,20) + '...'}}
        </mark>
        to your
        <a :href="`/blogs/${blog.id}?highlightedComment=${parentComment.id}`" 
            target="_blank">comment </a>
        <mark>
            {{parentComment.comment.substr(0,20) + '...'}}
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
                parentComment: this.$props.notificationData.parentComment,
                blog: this.$props.notificationData.blog,
                reply: this.$props.notificationData.reply,
                replier: this.$props.notificationData.replier,
            }
        },
        methods: {
        }
    }
</script>
