<script setup lang="ts">
import { Head } from '@inertiajs/vue3'
import { ref } from 'vue'

const username = ref('')
const password = ref('')
const loading = ref(false)
const errorMessage = ref('')

async function login() {
    errorMessage.value = ''
    loading.value = true

    try {
        const response = await fetch('/api/login', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                username: username.value,
                password: password.value,
            }),
        })

        const data = await response.json()

        if (!response.ok) {
            errorMessage.value = data.message || 'Login gagal'
            return
        }

        localStorage.setItem('token', data.token)
        localStorage.setItem('user', JSON.stringify(data.user))
        localStorage.setItem('loggedIn', '1')

        window.location.href = '/dashboard'
    } catch (error) {
        console.error(error)
        errorMessage.value = 'Tidak dapat terhubung ke server Laravel'
    } finally {
        loading.value = false
    }
}
</script>

<template>
    <Head title="Login" />

    <div class="flex min-h-screen items-center justify-center bg-gray-100">
        <div class="w-full max-w-md rounded-lg bg-white p-8 shadow">
            <h1 class="mb-6 text-center text-2xl font-bold">
                Login POS
            </h1>

            <form @submit.prevent="login" class="space-y-4">

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Username
                    </label>

                    <input
                        v-model="username"
                        type="text"
                        class="w-full rounded border p-2"
                        placeholder="Username"
                        required
                    />
                </div>

                <div>
                    <label class="mb-1 block text-sm font-medium">
                        Password
                    </label>

                    <input
                        v-model="password"
                        type="password"
                        class="w-full rounded border p-2"
                        placeholder="Password"
                        required
                    />
                </div>

                <div
                    v-if="errorMessage"
                    class="rounded bg-red-100 p-3 text-sm text-red-600"
                >
                    {{ errorMessage }}
                </div>

                <button
                    type="submit"
                    class="w-full rounded bg-blue-600 p-2 text-white"
                    :disabled="loading"
                >
                    {{ loading ? 'Memproses...' : 'Login' }}
                </button>

            </form>
        </div>
    </div>
</template>