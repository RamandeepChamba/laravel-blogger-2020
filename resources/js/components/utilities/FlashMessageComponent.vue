<template>
    <div class="container fixed-top" v-if="!hide">
        <div :class="`alert d-flex justify-content-between align-items-center
            fade show alert-${flashMsgClass}`" 
            ref="alert">
            {{flashMsg}}
            <button type="button" :class="`btn btn-${flashMsgClass}`" @click="close">x</button>
        </div>
    </div>
</template>

<script>
    export default {
        props: ['flashMessage', 'flashClass', 'flashType', 'flashData', 'authId'],
        data() {
            return {
                hide: true,
                flashMsg: this.$props.flashMessage ?? '',
                flashMsgClass: this.$props.flashClass ?? 'info',
            } 
        },
        mounted() {
            if(this.$props.flashType && (this.$props.flashType == 'broadcast')) {
                this.hide = true
                // Listen for broadcast event
                if (this.$props.flashData && this.$props.flashData.blogId) {
                    Echo.channel('blog-updated')
                    .listen('BlogUpdated', (e) => {
                        if(e.id == this.$props.flashData.blogId
                            && (e.triggererId !== this.$props.authId)) 
                        {
                            this.flashMsg = `This blog has been updated, 
                                you are viewing outdated content,
                                please refresh the page`
                            this.flashMsgClass = 'danger'
                            this.hide = false
                        }
                    });
                }
            }
            else {
                this.hide = false
            }
            if(!this.hide) {
                setTimeout(this.close, 5000)
            }
        },
        methods: {
            close() {
                $(this.$refs['alert']).alert('close')
                this.hide = true
            }
        }
    }
</script>
