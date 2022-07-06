<template>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Chats</div>

                    <div class="card-body">
                        <div v-for="n in 5" :key="n" :class="['row', n % 2 === 0 ? 'justify-content-start' : 'justify-content-end']">
                            <chat-message></chat-message>
                        </div>
                    </div>
                    <div class="card-footer">
                        <chat-box></chat-box>
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
        mounted() {
            console.log('Chat mounted.')
        }
    }
</script>
