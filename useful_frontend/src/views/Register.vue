<template>
    <div class="container">
        <h1>Inscription</h1>
        <form v-on:submit.prevent="handleRegister">
            <div class="form-group">
                <label>Nom :</label>
                <input type="text" v-model="name" required />
            </div>
            <div class="form-group">
                <label>Email :</label>
                <input type="email" v-model="email" required />
            </div>
            <div class="form-group">
                <label>Mot de passe :</label>
                <input type="password" v-model="password" required />
            </div>
            <button type="submit" v-bind:disabled="authStore.loading">S'inscrire</button>
            <p v-if="authStore.error" class="error">{{ authStore.error }}</p>
            <p v-if="authStore.loading">Chargement...</p>
            <p><router-link to="/login">Déjà un compte ? Se connecter</router-link></p>
        </form>
    </div>
</template>

<script>
import { useAuthStore } from '../stores/Auth'
import { useRouter } from 'vue-router'
import { ref } from 'vue'

export default {
    name: 'AppRegister',
    setup() {
        const authStore = useAuthStore()
        const router = useRouter()
    const name = ref('')
    const email = ref('')
    const password = ref('')

        function handleRegister() {
            authStore.register(name.value, email.value, password.value)
                .then(function () {
                    router.push('/login')
                })
                .catch(function () {
                    // L'erreur est gérée dans le store
                })
        }

        return { authStore, name, email, password, handleRegister }
    }
}
</script>

<style scoped>
.container {
    width: 400px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}
h1 {
    text-align: center;
    color: #333;
}
.form-group {
    margin-bottom: 15px;
}
label {
    display: block;
    margin-bottom: 5px;
    font-weight: bold;
}
input {
    width: 100%;
    padding: 8px;
    border: 1px solid #ccc;
    border-radius: 4px;
}
button {
    width: 100%;
    padding: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}
.error {
    color: red;
    text-align: center;
}
p {
    text-align: center;
}
a {
    color: #4CAF50;
    text-decoration: none;
}
a:hover {
    text-decoration: underline;
}
</style>
