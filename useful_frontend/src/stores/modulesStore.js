import { defineStore } from 'pinia'
import { useAuthStore } from './Auth'

export const useModulesStore = defineStore('modules', {
    state: function () {
        return {
            modules: [],
            loading: false,
            error: null
        }
    },
    actions: {
        async fetchModules() {
            this.loading = true
            this.error = null
            const authStore = useAuthStore()
            try {
                const response = await fetch('/api/modules', {
                    method: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + authStore.token,
                        'Accept': 'application/json'
                    }
                })
                const data = await response.json()
                if (response.status !== 200) {
                    this.error = data.error || 'Erreur lors du chargement des modules'
                    this.loading = false
                    throw new Error('Échec du chargement')
                }
                this.modules = data
                this.loading = false
            } catch (error) {
                this.loading = false
                this.error = 'Erreur réseau ou serveur'
                throw error
            }
        },
        async activateModule(moduleId) {
            this.loading = true
            this.error = null
            const authStore = useAuthStore()
            try {
                const response = await fetch('/api/modules/' + moduleId + '/activate', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + authStore.token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                const data = await response.json()
                if (response.status !== 200) {
                    this.error = data.error || 'Erreur lors de l\'activation'
                    this.loading = false
                    throw new Error('Échec de l\'activation')
                }
                this.loading = false
                return data
            } catch (error) {
                this.loading = false
                this.error = 'Erreur réseau ou serveur'
                throw error
            }
        },
        async deactivateModule(moduleId) {
            this.loading = true
            this.error = null
            const authStore = useAuthStore()
            try {
                const response = await fetch('/api/modules/' + moduleId + '/deactivate', {
                    method: 'POST',
                    headers: {
                        'Authorization': 'Bearer ' + authStore.token,
                        'Accept': 'application/json',
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({})
                })
                const data = await response.json()
                if (response.status !== 200) {
                    this.error = data.error || 'Erreur lors de la désactivation'
                    this.loading = false
                    throw new Error('Échec de la désactivation')
                }
                this.loading = false
                return data
            } catch (error) {
                this.loading = false
                this.error = 'Erreur réseau ou serveur'
                throw error
            }
        }
    }
})
