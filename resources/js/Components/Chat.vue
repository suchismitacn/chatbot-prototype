<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Live Chat</div>
                    <div class="card-body overflow-auto" style="height:500px">
                        <chat-message v-for="(message, index) in messages" :key="message.id" :message="message" :index="index"></chat-message>
                    </div>
                    <div class="card-footer">
                        <chat-box @message-sent="messageSent"></chat-box>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import ChatBox from './ChatBox.vue'
import ChatMessage from './ChatMessage.vue'
export default {
    components: { ChatMessage, ChatBox },
    data() {
        return {
            messages: []
        }
    },
    mounted() {
        console.log('Chat mounted.')
        this.getAllMessages();
    },
    methods: {
        messageSent(message) {
            console.log('This message is sent', message);
            this.messages.push({ 'id': this.messages.length + 1, 'name': message.name, 'content': message.content });
        },
        getAllMessages() {
            this.messages = [];
        }
    }
}
</script>
