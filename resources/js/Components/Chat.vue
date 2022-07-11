<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span>Live Chat</span> - {{ recipient?.name }}
            </div>
            <div class="card-body overflow-auto" style="height: 500px">
                <chat-message
                    v-for="message in messages"
                    :key="message.id"
                    :message="message"
                ></chat-message>
            </div>
            <div class="card-footer">
                <chat-box @message-sent="messageSent"></chat-box>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import ChatBox from "./ChatBox.vue";
import ChatMessage from "./ChatMessage.vue";
export default {
    components: { ChatMessage, ChatBox },
    props: ["users", "sender", "recipient"],
    data() {
        return {
            messages: [],
        };
    },
    mounted() {
        console.log("Chat mounted.");
        console.log("Chat Initiated Between:", this.sender, this.recipient);
        this.getAllMessages();
    },
    methods: {
        messageSent(message) {
            let data = {
                sender_id: this.sender.id,
                recipient_id: this.recipient.id,
                origin: "Live Chat",
                content: message,
            };
            console.log("Sending...", data);
            axios
                .post("/chat/send-message", data)
                .then((response) => {
                    console.log("Response", response);
                    this.messages.push({
                        id: this.messages.length + 1,
                        name: this.sender.name,
                        content: message,
                        by: "sender",
                    });
                })
                .catch((error) => console.error("Error", error));
        },
        getAllMessages() {
            let data = {
                sender_id: this.sender.id,
                recipient_id: this.recipient.id,
            };
            axios
                .post("/chat/fetch-messages", data)
                .then((response) => {
                    console.log("Response", response);
                    this.messages = response.data.data;
                })
                .catch((error) => console.error("Error", error));
        },
    },
};
</script>
