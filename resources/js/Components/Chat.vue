<template>
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <span>Live Chat</span> - {{ recipient.name }}
            </div>
            <div
                id="chat-log"
                class="card-body overflow-auto"
                style="height: 500px"
            >
                <chat-message
                    v-for="message in messages"
                    :key="message.id"
                    :message="message"
                    :sender="sender"
                ></chat-message>
            </div>
            <div class="card-footer">
                <chat-box @message-sent="messageSent"></chat-box>
                <small class="text-muted">{{ incomingText }}</small>
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
    props: ["users", "sender", "recipient", "chatId"],
    data() {
        return {
            messages: [],
            incomingText: "",
        };
    },
    mounted() {
        console.log("Chat mounted.");
        console.log("Chat Initiated Between:", this.sender, this.recipient);
        Echo.channel(`messages.${this.sender.id}`).listen(
            ".message.received",
            (e) => {
                console.log(`A new message from ${e.message.origin}`);
                this.incomingText = `${e.message.origin} is typing...`;
                this.pushMessage(e.message, 2000);
            }
        );
        // .whisper("typing", {
        //     name: this.sender.name,
        // }).listenForWhisper(
        //     "typing",
        //     (e) => {
        //         console.log("Someone is typing", e.name);
        //     }
        // );

        this.getAllMessages();
    },
    updated() {
        console.log("Chat updated");
        let el = document.getElementById("chat-log");
        el.scrollTo({
            top: el.scrollHeight,
            behavior: "smooth",
        });
    },
    methods: {
        messageSent(message) {
            let data = {
                chat_session: this.chatId,
                sender_id: this.sender.id,
                recipient_id: this.recipient.id,
                origin: this.sender.name,
                content: message,
            };
            // console.log("Sending...", data);
            axios
                .post("/chat/send-message", data)
                .then((response) => {
                    // console.log("Response", response);
                    this.pushMessage(response.data);
                })
                .catch((error) => console.error("Error", error));
        },
        getAllMessages() {
            let data = {
                chat_session: this.chatId,
                sender_id: this.sender.id,
                recipient_id: this.recipient.id,
            };
            axios
                .post("/chat/fetch-messages", data)
                .then((response) => {
                    // console.log("Response", response);
                    this.messages = response.data; // use paginated data later
                })
                .catch((error) => console.error("Error", error));
        },
        pushMessage($message, $delay = 100) {
            setTimeout(() => {
                this.messages.push($message);
                this.incomingText = "";
            }, $delay);
        },
    },
};
</script>
