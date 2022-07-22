<template>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <span>Chat Users</span>
            </div>
            <ul
                id="user-list"
                class="list-group list-group-flush overflow-auto"
                style="height: 552px"
            >
                <li
                    v-for="list in lists"
                    :key="list.id"
                    class="list-group-item d-flex justify-content-between align-items-start"
                    @click="startChat(list)"
                >
                    <div class="ms-2 me-auto">
                        <div class="fw-bold">User</div>
                        <span v-html="list.content"></span>
                    </div>
                    <span v-if="!list.read_at" class="badge bg-primary rounded-pill">New</span>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
import axios from "axios";
export default {
    props: ["sender"],
    data() {
        return {
            lists: [],
        };
    },
    created() {
        console.log("ChatUserList created.", this.sender);
        this.getAllChatUsers();
    },
    // updated() {
    //     console.log("ChatUserList updated");
        // let el = document.getElementById("user-list");
        // el.scrollTo({
        //     top: el.scrollHeight,
        //     behavior: "smooth",
        // });
    // },
    methods: {
        getAllChatUsers() {
            let data = {
                userId: this.sender.id,
            };
            axios
                .post("/chat/fetch-users", data)
                .then((response) => {
                    console.log("Response", response.data);
                    this.lists = response.data; // use paginated data later
                })
                .catch((error) => console.error("Error", error));
        },
        startChat(list) {
            let recipient = null;
            let chatId = list.chat_session_id;
            if(list.recipient_id == this.sender.id) {
                recipient = {
                    id: list.sender_id,
                    name: 'User'
                }
            } else {
                recipient = {
                    id: list.recipient_id,
                    name: 'User'
                }
            }
            this.$emit('newChat', this.sender, recipient, chatId);
        }
    },
};
</script>
