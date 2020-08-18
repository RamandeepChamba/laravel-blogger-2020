<template>
    <div class="row w-auto">
        <!-- Comments -->
        <ul id="comments" class="list-group">
            <comment
                v-for="comment in comments" 
                :key="comment.id"
                :comment="comment"
                :auth-id="authId">
            </comment>
        </ul>
    </div>
</template>

<script>
    import Comment from './Comment'

    export default {
        props: ['comments', 'authId'],
        components: {
            'comment': Comment
        }
    }
</script>
