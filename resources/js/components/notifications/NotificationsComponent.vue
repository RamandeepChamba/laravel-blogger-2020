<template>
    <div class="notifications-container dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" 
            data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            Notifications {{notifications.length}}
        </button>
        <div class="dropdown-menu dropdown-menu-right notifications-list" 
            aria-labelledby="dropdownMenuButton"
        >
            <div class="dropdown-item" v-show="!notifications.length">
                <p>No new notifications</p>
            </div>
            <div class="dropdown-item d-flex justify-content-end mb-3" 
                v-show="(notifications.length >= 2)">
                <button class="btn btn-warning" type="button"
                    @click="markAllAsRead"
                >
                    mark all as read
                </button>
            </div>
            <div class="dropdown-item"
                v-show="notifications.length"
                v-for="(notification, index) in notifications"
                :key="notification.id"
                :class="(index !== (notifications.length - 1)) ? 'border-bottom' : null"
            >
                <component 
                    :is="getNotificationType(notification.type)"
                    :notification-data="notification.data"
                    @mark-as-read="markAsRead(notification.id)"
                    class="text-wrap"
                >
                </component>
            </div>
        </div>
    </div>
</template>

<script>
    import BlogLikedNotification from './BlogLikedNotification'
    import CommentLikedNotification from './CommentLikedNotification'
    import CommentAddedNotification from './CommentAddedNotification'
    import ReplyAddedNotification from './ReplyAddedNotification'
    import GainedFollowerNotification from './GainedFollowerNotification'
    import FollowingAddedBlogNotification from './FollowingAddedBlogNotification'

    export default {
        props: ['myNotifications', 'authId'],
        data() {
            return {
                processing: false,
                notifications: this.$props.myNotifications,
            }
        },
        components: {
            BlogLikedNotification,
            CommentLikedNotification,
            CommentAddedNotification,
            ReplyAddedNotification,
            GainedFollowerNotification,
            FollowingAddedBlogNotification,
        },
        mounted() {
            /*
            Echo.private(`App.User.${this.$props.authId}`)
                .notification((notification) => {
                    this.notifications.push(notification)
                });
            */
        },
        methods: {
            getNotificationType(type) {
                type = type.split('\\')
                type = type[type.length - 1]
                type = type.replace(/[A-Z]/g, letter => `-${letter.toLowerCase()}`)
                    .concat('-notification').slice(1)
                return type
            },
            markAsRead(id) {
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.get(`/notifications/${id}/markAsRead`)
                    .then((response) => {
                        this.notifications = response.data.notifications ?? []
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },
            markAllAsRead() {
                if(this.processing) {
                    return
                }
                this.processing = true

                axios.get(`/notifications/markAllAsRead`)
                    .then((response) => {
                        this.notifications = response.data.notifications ?? []
                        this.processing = false
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            }
        },
    }
</script>
