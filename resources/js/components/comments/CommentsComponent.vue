<style>
    .preserve-space {
        white-space: pre-wrap;
    }
</style>
<template>
    <div>
        <!-- Comments counter -->
        <comments-counter
            :comments-count="comments.length">
        </comments-counter>
        <hr>
        <!-- Comment Form -->
        <comment-form
            :blog-id="blogId"
            :auth-id="authId"
            v-on:comment-added="commentAdded">
        </comment-form>
        <hr>
        <!-- Comments -->
        <comments-list
            :v-show="comments.length"
            :comments="comments"
            :auth-id="authId"
            :highlight-comment-node="highlightCommentNode"
            ref="comments-list"
            v-on:delete-comment="deleteComment">
        </comments-list>
    </div>
</template>

<script>
    import Counter from './Counter'
    import Form from './Form'
    import Comments from './Comments'

    export default {
        props: ['blogId', 'authId', 'highlightCommentNode'],

        components: {
            'comments-counter': Counter,
            'comment-form': Form,
            'comments-list': Comments
        },

        data() {
            return {
                processing: false,
                comments: []
            }
        },

        mounted() {
            this.getComments()
        },

        methods: {
            getComments() {
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.get(`/blogs/${this.$props.blogId}/comments`)
                    .then((response) => {
                        this.comments = response.data
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },     

            deleteComment(id) {
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.delete(`/comments/${id}`)
                    .then((response) => {
                        // Remove comment from UI
                        this.comments.splice(this.comments.findIndex(
                            comment => comment.id === response.data.comment.id
                        ), 1)
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },

            async commentAdded(comment) {
                // Add comment to UI
                this.comments.push(comment)
                // Wait for comments to update
                await this.$nextTick()
                // Scroll to end (added comment)
                let commentToFocus = this.$refs['comments-list']
                    .$refs[`comment-${comment.id}-component`][0].
                    $refs[`comment-${comment.id}`]
                commentToFocus.scrollIntoView()
                // Highlight comment
                commentToFocus.parentElement.classList.add('highlight-add')
                setTimeout(() => {
                    commentToFocus.parentElement.classList.remove('highlight-add')    
                }, 4000);
            },
        }
    }
</script>