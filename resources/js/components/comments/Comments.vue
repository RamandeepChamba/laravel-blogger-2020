<style scoped>
    .list-item {
    display: inline-block;
    margin-right: 10px;
    }
    .list-enter-active, .list-leave-active {
    transition: all 1s;
    }
    .list-enter, .list-leave-to /* .list-leave-active below version 2.1.8 */ {
    opacity: 0;
    transform: translateY(30px);
    }
</style>

<template>
    <div class="d-flex flex-column" ref="comments-list">
        <!-- Comments -->
        <ul :id="comments[0] ? (!comments[0]['parent_id'] ? 'comments' : null) : null" 
            class="list-group"
            :class="comments[0] ? (comments[0]['parent_id'] ? 'replies' : null) : null">
            
            <transition-group name="list" tag="p">
                <comment-component
                    v-for="comment in comments"
                    :key="comment.id"
                    :a-comment="comment"
                    :auth-id="authId"
                    :ref="`comment-${comment.id}-component`"
                    v-on:delete-comment="$emit('delete-comment', comment.id)"
                    v-on:delete-reply="$emit('delete-reply', comment.id)">
                </comment-component>
            </transition-group>
                
        </ul>
    </div>
</template>

<script>
    import Comment from './Comment'

    export default {
        props: ['comments', 'authId'],
        components: {
            'comment-component': Comment
        },
    }
</script>
