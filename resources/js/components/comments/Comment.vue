<template>
    <li class="list-group-item" :class="comment.parent_id ? 'my-5 ml-5' : ''">
        <!-- Link to user profile -->
        <a href="#">{{comment.user.name}}</a>
        <p>{{comment.comment}}</p>
        <!-- Like Comment -->
        <form action="#">
            <button type="submit" class="btn btn-primary">Like</button>
        </form>
        <!-- Edit Comment -->
        <form action="#" v-if="(authId === comment.user.id)">
            <button type="submit" class="btn btn-warning">Edit</button>
        </form>
        <!-- Delete Comment -->
        <form action="#" v-if="(authId === comment.user.id)">
            <button type="submit" class="btn btn-danger">Delete</button>
        </form>
        <!-- Reply Form with button -->
        <reply-form
            :blog-id="comment.commentable_id"
            :parent-id="comment.id">
        </reply-form>
        <!-- Show Replies -->
        <button type="button" 
            class="btn btn-secondary"
            v-on:click="toggleReplies">
            {{ hasReplies ? 'Hide Replies' : 'Show Replies'}}
        </button>
        <!-- Replies -->
        <component 
            :is="repliesComponent"
            :comments="replies"
            :auth-id="authId"
        >
        </component>
    </li>
</template>

<script>
    import ReplyForm from '../replyForm/ReplyForm';
    import Comments from './Comments';
    
    export default {
        props: ['comment', 'authId'],

        data() {
            return {
                processing: false,
                hasReplies: false,
                replies: [],
                repliesComponent: null,
            }
        },

        components: {
            'reply-form': ReplyForm,
            Comments,
        },

        methods: {
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

                axios.get(`/comments/${this.$props.comment.id}/replies`)
                    .then((response) => {
                        this.repliesComponent = Comments
                        this.replies = response.data
                        this.hasReplies = true
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
            }
        }
    }
</script>
