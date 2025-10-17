<template>
    <div class="container">
        <h1>Tableau de bord</h1>
    <button v-on:click="handleLogout" class="logout-btn">Déconnexion</button>
        <h2>Modules</h2>
        <p v-if="modulesStore.loading">Chargement...</p>
        <p v-if="modulesStore.error" class="error">{{ modulesStore.error }}</p>
        <ul class="module-list">
            <li v-for="module in modulesStore.modules" v-bind:key="module.id">
                <span>{{ module.name }} - {{ module.description }}</span>
                <button v-on:click="activateModule(module.id)" v-bind:disabled="modulesStore.loading">Activer</button>
                <button v-on:click="deactivateModule(module.id)" v-bind:disabled="modulesStore.loading">Désactiver</button>
            </li>
        </ul>
    </div>
</template>

<script>
import { useAuthStore } from '../stores/Auth'
import { useModulesStore } from '../stores/modulesStore'
import { useRouter } from 'vue-router'

export default {
    name: 'AppDashboard',
    setup() {
        const authStore = useAuthStore()
        const modulesStore = useModulesStore()
        const router = useRouter()

        if (!authStore.token) {
            router.push('/login')
        }

        modulesStore.fetchModules()

        function activateModule(moduleId) {
            modulesStore.activateModule(moduleId)
                .then(function () {
                    modulesStore.fetchModules() // Rafraîchir la liste
                })
                .catch(function () {
                    // L'erreur est gérée dans le store
                })
        }

        function deactivateModule(moduleId) {
            modulesStore.deactivateModule(moduleId)
                .then(function () {
                    modulesStore.fetchModules() // Rafraîchir la liste
                })
                .catch(function () {
                    // L'erreur est gérée dans le store
                })
        }

        function handleLogout() {
            authStore.logout()
            router.push('/login')
        }
        return { authStore, modulesStore, activateModule, deactivateModule, handleLogout }
    }
}
</script>

<style scoped>
.container {
    width: 600px;
    margin: 50px auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
    background-color: #f9f9f9;
}
h1, h2 {
    text-align: center;
    color: #333;
}
.logout-btn {
    display: block;
    margin: 10px auto;
    padding: 10px;
    background-color: #ff4444;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.logout-btn:hover {
    background-color: #cc0000;
}
.module-list {
    list-style: none;
    padding: 0;
}
.module-list li {
    margin: 10px 0;
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.module-list span {
    flex-grow: 1;
}
.module-list button {
    padding: 8px;
    margin-left: 10px;
    background-color: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.module-list button:disabled {
    background-color: #cccccc;
    cursor: not-allowed;
}
.module-list button:nth-child(3) {
    background-color: #ff4444;
}
.module-list button:nth-child(3):hover {
    background-color: #cc0000;
}
.module-list button:hover:not(:disabled) {
    background-color: #45a049;
}
.error {
    color: red;
    text-align: center;
}
</style>
