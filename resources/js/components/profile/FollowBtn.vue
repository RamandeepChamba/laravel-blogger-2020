<template>
    <div>
        <button type="button"
            v-show="(authId !== leaderId)"
            :class="`btn btn-${following ? 'danger' : 'primary'}`" 
            :disabled="processing"
            @click="followOrUnfollow"
        >
            {{following ? 'Unfollow' : 'Follow'}}
        </button>
    </div>
</template>

<script>
    export default {
        props: ['leaderId', 'authId'],
        data() {
            return {
                processing: false,
                following: false
            }
        },
        mounted() {
            if(this.$props.authId !== -1) {
                this.checkIfFollowing()
            }
        },
        methods: {
             checkIfFollowing() {
                 if(this.processing) {
                     return
                 }
                 this.processing = true
                 axios.get(`/followers/isFollowing/${this.$props.leaderId}`)
                    .then((response) => {
                        this.following = response.data.isFollowing
                        this.processing = false
                    })
                    .catch((error) => {
                        if(error.response && error.response.status == 401) {
                            window.location = '/login'
                        }
                        console.log(error)
                        this.processing = false
                    })
             },
             followOrUnfollow() {
                if(this.processing) {
                    return
                }
                this.processing = true

                let data = {
                    leader_id: this.$props.leaderId
                }
                axios.post(`/followers/${(this.following ? 'unfollow' : 'follow')}`, data)
                    .then((response) => {
                        this.$emit('profile-updated', response.data.profile)
                        this.following = !this.following
                        this.processing = false
                    })
                    .catch((error) => {
                        if(error.response && error.response.status == 401) {
                            window.location = '/login'
                            this.processing = false
                        }
                        else {
                            console.log(error)
                            this.processing = false
                        }
                    })
             },
        }
    }
</script>
