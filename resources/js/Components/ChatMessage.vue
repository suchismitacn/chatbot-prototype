<template>
    <div
        :class="['row p-1', self ? 'justify-content-start' : 'justify-content-end']"
    >
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span :class="[self ? 'text-success' : 'text-danger']">{{ message.origin }}</span>
                </div>
                <div class="card-body" v-html="message.content">
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["message", "sender"],
    data() {
        return {
            self: "",
        };
    },
    created() {
        console.log("ChatMessage created.");
        this.self = this.sender.id == this.message.sender_id;
    },
};
</script>
