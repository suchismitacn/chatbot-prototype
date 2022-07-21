<template>
    <div class="row p-1">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <span>{{
                        user.name
                    }}</span>
                </div>
                <div class="card-body" v-html="user.message"></div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["user"],
    data() {
        return {
        };
    },
    mounted() {
        console.log("ChatUser mounted.");
    },
};
</script>
