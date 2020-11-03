<style scoped>
    @keyframes highlight-add {
        0%   {background-color: default;}
        50%  {background-color: #A9F09B;}
        100%   {background-color: default;}
    }

    @keyframes highlight-edit {
        0%   {background-color: default;}
        50%  {background-color: #F2F38C;}
        100%   {background-color: default;}
    }

    .disabled {
        pointer-events: none;
    }
    .highlight-add {
        animation-name: highlight-add;
        animation-duration: 4s;
    }
    .highlight-edit {
        animation-name: highlight-edit;
        animation-duration: 4s;
    }
    .avatar-small {
        vertical-align: middle;
        height: 50px;
        width: 50px;
        border-radius: 50%;
    }
</style>

<template>
    <div :class="comment.parent_id ? 'my-2 ml-5' : ''">
        <li class="list-group-item"
            :class="{'highlight-edit': highlightEdit}">
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
            <div class="comment" 
                :class="{'disabled': disabled,}"
                v-show="!editing" 
                :ref="`comment-${comment.id}`">
                <!-- Link to user profile -->
                <a :href="`/profiles/${comment.user.id}`" class="d-flex align-items-stretch mb-3">
                    <img :src="comment.user.profile.avatar" 
                        class="d-flex mr-1 img-fluid img-thumbnail avatar-small" alt="avatar">
                    <p class="d-flex align-self-center m-0">{{comment.user.name}}</p>
                </a>
                <hr>
                <p class="preserve-space">{{comment.comment}}</p>
                <hr>
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
                    :auth-id="authId"
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
            :highlight-id="highlightReplyId"
            :highlight-comment-node="highlightTree ? highlightTree : []"
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
        props: ['aComment', 'authId', 'highlightCommentNode'],

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
                disabled: false,
                highlightReplyId: null,
                highlightEdit: false,
                highlightTree: this.$props.highlightCommentNode
            }
        },

        components: {
            'reply-form': ReplyForm,
            Comments,
            'edit-form': EditForm,
        },

        mounted() {
            if(this.highlightTree.length
                && this.highlightTree[this.highlightTree.length - 1] === this.comment.id 
                && this.highlightTree.length !== 1) 
            {
                // Render replies
                this.renderReplies()
            }
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
                        
                        if(this.highlightTree.length) {
                            this.highlightTree.pop()
                        }

                        if (this.highlightTree.length
                            && this.replies.find(el => 
                            el.id === this.highlightTree[this.highlightTree.length - 1])) 
                        {
                            this.highlightReplyId = this.highlightTree[this.highlightTree.length - 1]
                        } else {
                            this.highlightReplyId = this.replies[this.replies.length - 1].id
                        }
                        
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
                this.highlightReplyId = null
            },
            replyAdded: function (id) {
                // Highlight added reply
                this.highlightReplyId = id
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
                        this.highlightEdit = true
                        setTimeout(() => {
                            this.highlightEdit = false    
                        }, 4000);
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
