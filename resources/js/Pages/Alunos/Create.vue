<template>
    <div>
      <Head title="Novo Usuário" />
      <h1 class="mb-8 text-3xl font-bold">
        <Link class="text-red-400 hover:text-red-600" href="/users">Users</Link>
        <span class="text-red-400 font-medium">/</span> Criar
      </h1>
      <div class="max-w-3xl bg-white rounded-md shadow overflow-hidden">
        <form @submit.prevent="store">
          <div class="flex flex-wrap -mb-8 -mr-6 p-8">
            <text-input v-model="form.name" :error="form.errors.name" class="pb-8 pr-6 w-full lg:w-1/2" label="Nome" />
            <text-input v-model="form.email" :error="form.errors.email" class="pb-8 pr-6 w-full lg:w-1/2" label="Email" />
            <text-input v-model="form.password" :error="form.errors.password" class="pb-8 pr-6 w-full lg:w-1/2" type="password" autocomplete="new-password" label="Password" />
            <select-input v-model="form.owner" :error="form.errors.owner" class="pb-8 pr-6 w-full lg:w-1/2" label="Owner">
              <option :value="true">Sim</option>
              <option :value="false">Não</option>
            </select-input>
            <file-input v-model="form.photo" :error="form.errors.photo" class="pb-8 pr-6 w-full lg:w-1/2" type="file" accept="image/*" label="Photo" />
          </div>
          <div class="flex items-center justify-end px-8 py-4 bg-red-50 border-t border-red-100">
            <loading-button :loading="form.processing" class="btn-red" type="submit">Create User</loading-button>
          </div>
        </form>
      </div>
    </div>
  </template>

  <script>
  import { Head, Link } from '@inertiajs/vue3'
  import Layout from '@/Shared/Layout'
  import FileInput from '@/Shared/FileInput'
  import TextInput from '@/Shared/TextInput'
  import SelectInput from '@/Shared/SelectInput'
  import LoadingButton from '@/Shared/LoadingButton'

  export default {
    components: {
      FileInput,
      Head,
      Link,
      LoadingButton,
      SelectInput,
      TextInput,
    },
    layout: Layout,
    remember: 'form',
    data() {
      return {
        form: this.$inertia.form({
          name: '',
          email: '',
          password: '',
          owner: false,
          photo: null,
        }),
      }
    },
    methods: {
      store() {
        this.form.post('/users')
      },
    },
  }
  </script>
