<template>
    <div>
        <input type="text" class="form-control" v-model="message" placeholder="Press enter to send your message"
            @keyup.enter="sendMessage">
            <button @click="sendMessage">Send message</button>
        This is message: {{ message }}
    </div>
</template>

<script>
export default {
    mounted() {
        console.log('ChatBox mounted.')
    },
    data() {
        return {
            message: 'Test'
        }
    },
    methods: {
        sendMessage() {
            console.log("Enter has been pressed");
        }
    }
}
</script>
