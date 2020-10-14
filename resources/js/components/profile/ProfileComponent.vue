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
        <div class="d-flex align-items-center">
            <h2 id="profile-name" class="mr-3">
                {{profile.user.name}} 
                <small v-show="hasPermission">(you)</small>
            </h2>
            <!-- Follow Button -->
            <component
                :is="followBtn"
                :leader-id="profile.user.id"
                :auth-id="authId"
                @profile-updated="profileUpdated"
            >
            </component>
        </div>
        <small id="profile-email">{{profile.user.email}}</small>
        <hr>
        <p>
            <a :href="`/followers/${authId}/followers`" 
                v-show="(authId === profile.user.id)"
            >
                <strong>Followers: </strong>
            </a>
            <strong v-show="(authId !== profile.user.id)">Followers: </strong>
            {{profile.user.followers_count}}
        </p>
        <p>
            <a :href="`/followers/${authId}/followings`" 
                v-show="(authId === profile.user.id)"
            >
                <strong>Following: </strong>
            </a>
            <strong v-show="(authId !== profile.user.id)">Following: </strong>
            {{profile.user.followings_count}}
        </p>
        <hr>
        <h3 class="mb-4">About</h3>
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
            <h3 class="mb-4">Blogs</h3>
            <a :href="`/users/${profile.user.id}/blogs`" class="h4">--> Check Blogs</a>
        </div>
        <hr>
        <!-- Remove account button -->
        <button class="btn btn-danger" 
            v-show="(authId === profile.user.id)"
            @click="removeAccount"
        >
            Remove account
        </button>
    </div>
</template>

<script>
    import AvatarForm from './AvatarForm'
    import BioForm from './BioForm'
    import FollowBtn from './FollowBtn'
    
    export default {
        props: ['aProfile', 'authId'],
        data() {
            return {
                profile: this.$props.aProfile,   
                hasPermission: (this.$props.aProfile.user.id === this.$props.authId),
                avatarForm: null,
                bioForm: null,
                followBtn: 'follow-btn',
                processing: false
            }
        },
        components: {
            'avatar-form': AvatarForm,
            'bio-form': BioForm,
            'follow-btn': FollowBtn,
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
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    })
            }
        },
    }
</script>
