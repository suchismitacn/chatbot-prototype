<template>
    <div>
        <input
            type="text"
            class="form-control"
            v-model.trim="message"
            placeholder="Press enter to send your message"
            @keyup.enter="sendMessage"
        />
    </div>
</template>

<script>
export default {
    created() {
        console.log("ChatBox created.");
    },
    data() {
        return {
            message: "",
        };
    },
    methods: {
        sendMessage() {
            console.log("Enter pressed");
            if (this.message) {
                this.$emit("messageSent", this.message);
                this.message = "";
            }
        },
    },
};
</script>
