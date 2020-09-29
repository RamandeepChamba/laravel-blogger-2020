<style scoped>
    .avatar {
        vertical-align: middle;
        width: 200px;
        height: 200px;
    }
</style>

<template>
    <div>
        <img id="avatar-img" class="img-fluid img-thumbnail avatar"
            :src="profile.avatar" alt="avatar">
        <br>
        <!-- Upload avatar -->
        <button class="btn btn-success"
            v-show="(hasPermission && !avatarForm)" @click="showAvatarForm">
            Upload
        </button>
        <!-- Upload avatar form -->
        <component
            :is="avatarForm"
            @hide-avatar-form="hideAvatarForm"
            @preview-avatar="previewAvatar"
            @profile-updated="profileUpdated"
        >
        </component>
        <hr>
        <h2 id="profile-name">{{profile.user.name}} 
            <small v-show="hasPermission">(you)</small>
        </h2>
        <small id="profile-email">{{profile.user.email}}</small>
        <hr>
        <p id="profile-bio" v-show="!bioForm">
            {{profile.bio ? profile.bio : 'No bio given'}}
        </p>
        <!-- Edit bio -->
        <button class="btn btn-warning" 
            v-show="(hasPermission && !bioForm)" @click="showBioForm">
            Edit
        </button>
        <!-- Edit Bio Form -->
        <component
            :is="bioForm"
            @hide-bio-form="hideBioForm"
            @profile-updated="profileUpdated"
            :bio-text="profile.bio"
        >
        </component>
        <hr>
        <!-- Link to blogs by this user -->
        <div>
            <a :href="`/users/${profile.user.id}/blogs`" class="h2">Check Blogs</a>
        </div>
        <hr>
        <!-- Remove account button -->
        <button class="btn btn-danger" @click="removeAccount">Remove account</button>
    </div>
</template>

<script>
    import AvatarForm from './AvatarForm'
    import BioForm from './BioForm'
    
    export default {
        props: ['aProfile', 'authId'],
        data() {
            return {
                profile: this.$props.aProfile,   
                hasPermission: (this.$props.aProfile.user.id === this.$props.authId),
                avatarForm: null,
                bioForm: null,
                processing: false
            }
        },
        components: {
            'avatar-form': AvatarForm,
            'bio-form': BioForm,
        },
        methods: {
            showAvatarForm() {
                this.processing = true
                this.avatarForm = 'avatar-form'
                this.processing = false
            },
            showBioForm() {
                this.processing = true
                this.bioForm = 'bio-form'
                this.processing = false
            },
            hideAvatarForm() {
                this.processing = true
                this.avatarForm = null
                this.processing = false
            },
            hideBioForm() {
                this.processing = true
                this.bioForm = null
                this.processing = false
            },
            previewAvatar(img) {
                $('#avatar-img').attr('src', img);
            },
            profileUpdated(profile) {
                this.profile = profile
            },
            removeAccount() {
                this.processing = true

                axios.delete('/users')
                    .then((response) => {
                        window.location.href = '/login'
                    })
                    .catch((error) => {
                        console.log(error)
                    })
            }
        },
    }
</script>
