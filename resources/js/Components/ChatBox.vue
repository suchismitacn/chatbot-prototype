<template>
    <div>
        <input type="text" class="form-control" v-model="message" placeholder="Press enter to send your message"
            @keyup.enter="sendMessage">
    </div>
</template>

<script>
export default {
    mounted() {
        console.log('ChatBox mounted.')
    },
    data() {
        return {
            message: ''
        }
    },
    methods: {
        sendMessage() {
            console.log("Enter has been pressed");
            this.$emit('messageSent', { 'name': 'Test 1', 'content': this.message });
            this.message = '';
        }
    }
}
</script>
