<template>
    <div>
        <form 
            v-show="authId !== -1"
            v-on:submit.prevent="addComment">
            <textarea v-model="comment" cols="70"
                aria-describedby="commentHelp" 
                placeholder="Write your opinion on this"
                maxlength="300" minlength="5" required
                :ref="parentId ? 'reply-textarea' : null"
            ></textarea>
            <small id="commentHelp" class="form-text text-muted">
                Can only use upto 300 characters
            </small>
            <br>
            <button type="submit" 
                class="btn btn-success"
                :disabled="processing">
                {{processing ? 'Adding...' : (parentId ? 'Reply' : 'Add')}}
            </button>
            <button type="button" class="btn btn-warning"
                v-show="parentId"
                v-on:click="$emit('toggle-reply-form')">
                Cancel
            </button>
        </form>
        <a 
            v-show="authId === -1"
            href="/login">
            <button class="btn btn-primary">Login to comment</button>
        </a>
    </div>
</template>

<script>
    export default {
        props: ['blogId', 'parentId', 'authId'],
        
        data() {
            return {
                comment: null,
                processing: false
            }
        }, 

        mounted() {
            if(this.$refs['reply-textarea']) {
                this.$refs['reply-textarea'].focus()
            }
        },

        methods: {
            addComment(event) {
                if(this.processing) {
                    return
                }
                this.processing = true

                const data = {
                    blog_id: this.$props.blogId,
                    parent_id: this.$props.parentId,
                    comment: this.comment
                }

                axios.post('/comments', data)
                    .then((response) => {
                        let comment = response.data
                        if(comment.parent_id) {
                            this.$emit('reply-added')
                        }
                        else {
                            this.$emit('comment-added', comment)
                        }
                        this.comment = null
                        this.processing = false
                    })
                    .catch((error) => {
                        if(error.response.status === 401) {
                            window.location = '/login';
                        }
                        else {
                            console.log(error)
                        }
                        this.processing = false
                    });
            }
        },
    }
</script>