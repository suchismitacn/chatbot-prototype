<template>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">{{message?.name}}</div>
            <div class="card-body">
                {{message?.content}}
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message'],
    mounted() {
        console.log('ChatMessage mounted.', this.message)
    }
}
</script>
