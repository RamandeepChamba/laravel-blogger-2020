<template>
    <div>
        <form @submit="uploadAvatar" enctype="multipart/form-data">
            <input type="file" @change="previewAvatar" name="avatar" id="avatar">
            <br>
            <small>Max size: 2048 kb</small>
            <br>
            <div 
                v-for="(error, index) in errors" 
                :key="`error-${index}`"
                class="alert alert-danger">
                {{error}}
            </div>
            <button class="btn btn-success" type="submit" 
                :disabled="processing">
                Upload
            </button>
            <button class="btn btn-danger" type="button" 
                @click="$emit('hide-avatar-form')" 
                :disabled="processing">
                Cancel
            </button>
        </form>
    </div>
</template>

<script>
    export default {
        props: [],
        data() {
            return {
                processing: false,
                errors: []
            }
        },
        methods: {
            previewAvatar(e) {
                this.readURL(e.target)
            },
            uploadAvatar(e) {
                this.processing = true
                this.errors = []
                e.preventDefault()
                let avatar = document.querySelector('#avatar').files[0]
                let formData = new FormData();
                formData.append('avatar', avatar);

                axios.post('/profiles', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    }
                })
                    .then((response) => {
                        this.$emit('profile-updated', response.data.profile)
                        this.$emit('hide-avatar-form')
                        this.processing = false
                    })
                    .catch((error) => {
                        if(error.response.status === 413) {
                            this.errors.push('Image size too large')
                            this.processing = false
                            return
                        }
                        if(error.response.data.errors.avatar) {
                            this.errors = [...this.errors, ...error.response.data.errors.avatar]
                        }
                        console.log(error.response)
                        this.processing = false
                    });
            },
            readURL(input) {
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    
                    reader.onload = (e) => {
                        this.$emit('preview-avatar', e.target.result)
                    }
                    
                    reader.readAsDataURL(input.files[0]); // convert to base64 string
                }
            }

        },
    }
</script>
