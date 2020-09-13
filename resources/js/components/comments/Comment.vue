<style scoped>
    .disabled {
        pointer-events: none;
    }
</style>

<template>
    <div :class="comment.parent_id ? 'my-2 ml-5' : ''">
        <li class="list-group-item">
            <!-- Edit form -->
            <component
                :is="editFormComponent"
                :comment="comment"
                v-on:remove-edit-form="removeEditForm"
                v-on:update-comment="updateComment"
                :processing="processing"
            >
            </component>
            <!-- Comment -->
            <div class="comment" :class="{'disabled': disabled}" v-show="!editing" 
                :ref="`comment-${comment.id}`">
                <!-- Link to user profile -->
                <a href="#">{{comment.user.name}}</a>
                <p>{{comment.comment}}</p>
                <!-- Like Comment -->
                <like-component 
                    :auth-id="authId"
                    :comment-id="comment.id"
                    :likes-count="likes"
                    :is-liked="comment.likes">
                </like-component>
                <!-- Edit Comment -->
                <button type="button" class="btn btn-warning" 
                    v-show="(authId === comment.user.id)"
                    v-on:click="editComment">
                    Edit
                </button>
                <!-- Delete Comment -->
                <form v-on:submit.prevent="disableComment(); 
                    (comment['parent_id'] ? $emit('delete-reply') : $emit('delete-comment'));" 
                    v-show="(authId === comment.user.id)">
                    <button type="submit" class="btn btn-danger">Delete</button>
                </form>
                <!-- Reply Form with button -->
                <reply-form
                    :blog-id="comment.commentable_id"
                    :parent-id="comment.id"
                    v-show="authId"
                    v-on:reply-added="replyAdded">
                </reply-form>
                <!-- Show Replies -->
                <button 
                    type="button" 
                    class="btn btn-secondary"
                    v-show="repliesCount"
                    v-on:click="toggleReplies">
                    {{hasReplies ? 'Hide ' : 'Show '}}
                    {{repliesCount + ' '}}
                    {{repliesCount === 1 ? 'reply' : 'replies'}}
                </button>
            </div>
        </li>
        <!-- Replies -->
        <component 
            :is="repliesComponent"
            :comments="replies"
            :auth-id="authId"
            v-on:delete-reply="deleteReply"
        >
        </component>
    </div>
</template>

<script>
    import ReplyForm from './ReplyForm';
    import Comments from './Comments';
    import EditForm from './EditForm';
    
    export default {
        props: ['aComment', 'authId',],

        data() {
            return {
                comment: this.$props.aComment,
                processing: false,
                hasReplies: false,
                replies: [],
                repliesCount: this.$props.aComment['replies_count'],
                likes: this.$props.aComment['likes_count'],
                repliesComponent: null,
                editFormComponent: null,
                editing: false,
                disabled: false
            }
        },

        components: {
            'reply-form': ReplyForm,
            Comments,
            'edit-form': EditForm,
        },

        methods: {
            // Replies
            toggleReplies: function () {
                if (this.hasReplies) {
                    this.disableReplies()
                }
                else {
                    this.renderReplies()
                }
            },
            renderReplies: function () {
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.get(`/comments/${this.comment.id}/replies`)
                    .then((response) => {
                        this.repliesComponent = Comments
                        this.replies = response.data
                        this.hasReplies = true
                        this.repliesCount = this.replies.length
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },
            disableReplies: function () {
                if(this.processing) {
                    return
                }

                this.repliesComponent = null
                this.replies = []
                this.hasReplies = false
            },
            replyAdded: function () {
                this.renderReplies()
            },
            deleteReply: function (id) {
                // Delete reply
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.delete(`/comments/${id}`)
                    .then((response) => {
                        // Remove reply from UI
                        this.replies.splice(this.replies.findIndex(
                            comment => comment.id === response.data.comment.id
                        ), 1)
                        // Update reply count
                        this.repliesCount = this.replies.length
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },

            // Comment
            editComment: function () {
                if(this.processing) {
                    return
                }
                this.processing = true

                this.repliesComponent = null
                this.hasReplies = false
                this.editing = true
                this.editFormComponent = 'edit-form'
                this.processing = false
            },
            removeEditForm: function () {
                if(this.processing) {
                    return
                }
                this.processing = true

                this.editFormComponent = null
                this.editing = false

                this.processing = false
            },
            updateComment: function (comment) {
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.patch(`/comments/${this.comment.id}`, {comment})
                    .then((response) => {
                        this.comment = response.data
                        this.replies.length = response.data['replies_count']
                        this.processing = false
                        this.removeEditForm()
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },
            disableComment: function () {
                this.disabled = true
            },
        }
    }
</script>
