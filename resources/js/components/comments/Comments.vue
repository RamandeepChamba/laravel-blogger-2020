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
                    :highlight-comment-node="highlightCommentNode.includes(comment.id) 
                        ? highlightCommentNode : []"
                    @hook:mounted="(
                        (
                            (highlightCommentNode[highlightCommentNode.length - 1] === comment.id)
                            || (highlightId  === comment.id) 
                        )
                        ? highlightReply(comment.id) : null
                    )"
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
        props: ['comments', 'authId', 'highlightId', 'highlightCommentNode'],
        components: {
            'comment-component': Comment
        },
        methods: {
            highlightReply(id) {
                // Scroll to end (added reply)
                let replyToFocus = this.
                    $refs[`comment-${id}-component`][0].
                    $refs[`comment-${id}`]
                replyToFocus.scrollIntoView()
                // Highlight reply
                replyToFocus.parentElement.classList.add('highlight-add')
                setTimeout(() => {
                    replyToFocus.parentElement.classList.remove('highlight-add')    
                }, 4000);
            },
        },
    }
</script>
