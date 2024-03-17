<template>
    <Head title="Login" />
    <div :style="{'background-image':'url(https://images.pexels.com/photos/1552252/pexels-photo-1552252.jpeg?auto=compress&cs=tinysrgb&w=1260&h=750&dpr=1)'}" class="flex bg-no-repeat bg-cover bg-center bg-fixed items-center justify-center p-6 min-h-screen">
        <div class="w-full max-w-md">
            <form class="mt-8 bg-white rounded-lg shadow-xl overflow-hidden" @submit.prevent="login">
                <div class="px-10 py-12">
                    <h1 class="text-center text-3xl font-bold">Bem vindo à FitManager!</h1>
                    <div class="mt-6 mx-auto w-24 border-b-2" />
                    <text-input v-model="form.email" :error="form.errors.email" class="mt-10 " label="Email" type="email"
                        autofocus autocapitalize="off" />
                    <text-input v-model="form.password" :error="form.errors.password" class="mt-6" label="Senha"
                        type="password" />
                    <label class="flex items-center mt-6 select-none" for="remember">
                        <input id="remember" v-model="form.remember" class="mr-1" type="checkbox" />
                        <span class="text-sm">Lembre-se de mim.</span>
                    </label>
                </div>
                <div class="flex px-10 py-4 bg-orange-100 border-t border-orange-100">
                    <div class="flex flex-col items-start">
                        <span class="text-sm mb-2">Já fez cadastro?</span>
                        <loading-button :loading="form.processing" class="btn-red"
                            type="button" @click="goToRegister">Cadastro</loading-button>
                    </div>
                    <loading-button :loading="form.processing" class="btn-red ml-auto"
                        type="submit">Login</loading-button>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import { Head } from '@inertiajs/vue3'
import TextInput from '@/Shared/TextInput'
import LoadingButton from '@/Shared/LoadingButton'

export default {
    components: {
        Head,
        LoadingButton,
        TextInput,
    },
    data() {
        return {
            form: this.$inertia.form({
                email: 'funcionario@example.com',
                password: 'secret',
                remember: false,
            }),
        }
    },
    methods: {
        login() {
            this.form.post('/login')
        },
        goToRegister() {
            this.$inertia.visit('/register')
        },
    },
}
</script>