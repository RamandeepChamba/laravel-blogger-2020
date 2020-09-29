<template>
    <div>
        <form @submit="addBio">
            <input type="text" name="bio" placeholder="Tell about yourself" 
                v-model="bio"
                id="profile-bio" required>
            <br>
            <small>Max 200 characters</small>
            <br>
            <button class="btn btn-warning" type="submit" :disabled="processing">
                {{processing ? 'Editing...' : 'Edit'}}
            </button>
            <button class="btn btn-danger" type="button" @click="$emit('hide-bio-form')" 
                :disabled="processing" 
            >
                Cancel
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        props: ['bioText'],
        data() {
            return {
                bio: this.$props.bioText,
                processing: false,
            }
        },
        methods: {
             addBio(e) {
                this.processing = true
                e.preventDefault()
                
                let data = {
                    bio: this.bio
                }
                axios.post('/profiles', data)
                    .then((response) => {
                        this.$emit('profile-updated', response.data.profile)
                        this.$emit('hide-bio-form')
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },
        }
    }
</script>
