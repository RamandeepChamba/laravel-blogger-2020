<template>
    <div
        v-show="(likes > -1)"
        class="container m-0 p-0">
        <button
            class="btn"
            :class="`btn-${liked ? 'danger' : 'primary'}`"
            :disabled="processing"
            @click="liked ? dislike() : like()">
            {{processing ? (liked ? 'Disliking...' : 'Liking...') : (liked ? 'Dislike' : 'Like')}}
        </button>
        <small id="comment-likes-count">{{likes}}</small>
    </div>
</template>

<script>
    export default {
        props: ['commentId', 'blogId', 'authId', 'likesCount', 'isLiked'],
        data() {
            return {
                processing: false,
                likes: this.$props.likesCount,
                type: (this.$props.commentId ? 'comment' 
                    : (this.$props.blogId ? 'blog' : null)),
                id: (this.$props.commentId ?? this.$props.blogId),
                liked: this.$props.isLiked.length,
            }
        },
        methods: {
            like() {
                // If user not authenticated redirect to login
                if (this.$props.authId === -1) {
                    return this.login()
                }
                
                if(this.processing) {
                    return
                }
                this.processing = true

                let data = {
                    type: this.type,
                    id: this.id,
                }
                axios.post('/likes', data)
                    .then((response) => {
                        // this.likes = response.data.likes
                        this.liked = 1
                        this.likes = response.data.likesCount
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },
            dislike() {
                // If user not authenticated redirect to login
                if (this.$props.authId === -1) {
                    return this.login()
                }
                
                if(this.processing) {
                    return
                }
                this.processing = true
                
                let data = {
                    type: this.type,
                    id: this.id,
                }
                axios.delete('/likes', {data})
                    .then((response) => {
                        this.liked = 0
                        this.likes = response.data.likesCount
                        this.processing = false
                    })
                    .catch((error) => {
                        if(error.response && error.response.status === 401) {
                            window.location = '/login';
                        }
                        else {
                            if (confirm(error + '\n Content got updated' + '\n Refresh the page?')) {
                                location.reload()
                            } else {
                                0
                            }
                        }
                        this.processing = false
                    });
            },
            login() {
                window.location = '/login'
            },
            showAlert() {
                console.log('alert')
            }
        }
    }
</script>
