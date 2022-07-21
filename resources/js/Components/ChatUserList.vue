<template>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <span>Chat Users</span>
            </div>
            <div
                id="user-list"
                class="card-body overflow-auto"
                style="height: 500px"
            >
                <chat-user
                    v-for="user in users"
                    :key="user.id"
                    :user="user"
                ></chat-user>
            </div>
        </div>
    </div>
</template>

<script>
import axios from "axios";
import ChatUser from './ChatUser.vue';
export default {
  components: { ChatUser },
    props: ["users", "sender"],
    data() {
        return {
            users: [],
        };
    },
    mounted() {
        console.log("ChatUserList mounted.");
        this.getAllChatUsers();
    },
    updated() {
        console.log("ChatUserList updated");
        // let el = document.getElementById("user-list");
        // el.scrollTo({
        //     top: el.scrollHeight,
        //     behavior: "smooth",
        // });
    },
    methods: {
        getAllChatUsers() {
            let data = {
                sender_id: this.sender.id,
            };
            axios
                .post("/chat/fetch-users", data)
                .then((response) => {
                    // console.log("Response", response);
                    this.users = response.data; // use paginated data later
                })
                .catch((error) => console.error("Error", error));
        },
    },
};
</script>