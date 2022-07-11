<template>
    <div
        :class="['row p-1', self ? 'justify-content-start' : 'justify-content-end']"
    >
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    {{ message.sender ? message.sender.name : "User" }}
                </div>
                <div class="card-body">
                    {{ message?.content }}
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
    mounted() {
        console.log("ChatMessage mounted.");
        this.self = this.sender.id == this.message.sender_id;
    },
};
</script>
