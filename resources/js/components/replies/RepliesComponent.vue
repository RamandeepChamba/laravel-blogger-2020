<template>
    <div>
        <button class="btn btn-secondary" v-on:click="replyButtonAction">
            {{
                hideReplies
                ? "Hide replies"
                : `Show ${repliesCount} replies`
            }}
        </button>
        <li v-show="replies.length" class="replies-container list-group-item ml-5">
            <ul class="replies list-group">
                <li v-for="reply in replies" :key="reply.parent_id">
                    {{reply.comment}}
                </li>
            </ul>
        </li>
    </div>
</template>

<script>
    import axios from "axios";
    
    export default {
        props: ['parentId', 'replyCount'],

        data() {
            return {
                replies: [],
                repliesCount: this.$props.replyCount,
                replyButtonAction: this.showReplies,
                hideReplies: false,
                processing: false
            }
        },

        methods: {
            showReplies(event) {
                if (this.processing) {
                    return
                }
                this.processing = true
                
                axios.get(`/comments/${this.$props.parentId}/replies`)
                    .then((response) => {
                        this.replies = response.data
                        this.repliesCount = response.data.length
                        this.replyButtonAction = this.clearReplies
                        this.hideReplies = true
                        this.processing = false
                        
                    })
                    .catch((error) => {
                        console.log(error)
                        this.processing = false
                    });
            },

            clearReplies(event) {
                this.replies = []
                this.replyButtonAction = this.showReplies
                this.hideReplies = false
            }
        }
    }
</script>
