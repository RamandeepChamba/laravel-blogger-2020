<template>
    <div>
        <a :href="`/profiles/${liker.id}`" target="_blank">
            {{liker.name}}
        </a>
        liked your
        <a :href="`/blogs/${comment.blog_id}?highlightedComment=${comment.id}`" 
            target="_blank">comment </a>
        <mark>
            {{comment.comment.substr(0,20) + '...'}}
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
                comment: this.$props.notificationData.comment,
            }
        },
        mounted() {
        },
        methods: {
        }
    }
</script>
