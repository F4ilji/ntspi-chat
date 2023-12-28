<script>
import axios from "axios";

export default {
    name: "Index",
    props: ['messages'],
    data() {
        return {
            allMessage: this.messages,
            form: this.$inertia.form({
                body: null,
            }),
        }
    },
    methods: {
        store() {
            axios.post(route('message.store'), this.form)
                .then(res => {
                    this.form.body = null
                    this.allMessage.push(res.data)
                    setTimeout(this.scrollBottom, 0)
                })
        },
        scrollBottom() {
            const element = document.getElementById('chat');
            element.scrollTo(0, 999999);
        }
    },
    created() {
        window.Echo.channel('store_message').listen('.store_message', res => {
            this.allMessage.push(res.message)
            setTimeout(this.scrollBottom, 0)
        })
    },
    mounted() {
        setTimeout(this.scrollBottom, 0)
    }
}
</script>

<template>


    <body class="flex flex-col items-center justify-center w-screen min-h-screen bg-gray-100 text-gray-800 p-10">

    <!-- Component Start -->
    <div class="flex flex-col flex-grow w-full max-w-xl bg-white shadow-xl rounded-lg overflow-hidden">
        <div id="chat" class="flex flex-col flex-grow h-0 p-4 overflow-auto">
            <template v-for="message in allMessage">
                <div class="flex w-full mt-2 space-x-3 max-w-xs ml-auto justify-end" v-if="message.user_id === this.$inertia.page.props.auth.user.id">
                    <div>
                        <div class="bg-blue-600 text-white p-3 rounded-l-lg rounded-br-lg">
                            <p class="text-sm">{{ message.body }}</p>
                        </div>
                        <span class="text-xs text-gray-500 leading-none">{{ message.time }} | {{ message.userName }}</span>
                    </div>
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300"></div>
                </div>

                <div v-else class="flex w-full mt-2 space-x-3 max-w-xs">
                    <div class="flex-shrink-0 h-10 w-10 rounded-full bg-gray-300"></div>
                    <div>
                        <div class="bg-gray-300 p-3 rounded-r-lg rounded-bl-lg">
                            <p class="text-sm">{{ message.body }}</p>
                        </div>
                        <span class="text-xs text-gray-500 leading-none">{{ message.time }} | {{ message.userName }}</span>
                    </div>
                </div>
            </template>
        </div>

        <form>
            <div class="bg-gray-300 p-4 flex">
                <input v-model="form.body" class="flex items-center h-10 w-full rounded px-3 text-sm mr-4" type="text" placeholder="Type your message…">
                <button @click.prevent="store" class="border border-black px-2">Отправить</button>
            </div>
        </form>
    </div>
    <!-- Component End  -->

    </body>
</template>

<style scoped>
#chat {
    scroll-behavior: smooth;
}

</style>
