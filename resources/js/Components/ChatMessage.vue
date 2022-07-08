<template>
    <div :class="['row', index % 2 === 0 ? 'justify-content-start' : 'justify-content-end']">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">{{ message?.name }}</div>
                <div class="card-body">
                    {{ message?.content }}
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ['message', 'index'],
    mounted() {
        console.log('ChatMessage mounted.');
    }
}
</script>
