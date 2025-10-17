import { defineStore } from 'pinia'

export const useAuthStore = defineStore('auth', {
    state: function () {
        return {
            token: localStorage.getItem('token') || null,
            userId: localStorage.getItem('userId') || null,
            error: null,
            loading: false
        }
    },
    actions: {
        async register(name, email, password) {
            this.loading = true
            this.error = null
            try {
                const response = await fetch('http://localhost:8000/api/register', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email,
                        password: password
                    })
                })
                const data = await response.json()
                if (response.status !== 201) {
                    this.error = data.error || 'Erreur lors de l\'inscription'
                    this.loading = false
                    throw new Error('Inscription échouée')
                }
                this.loading = false
                return data
            } catch (error) {
                this.loading = false
                this.error = 'Erreur réseau ou serveur'
                throw error
            }
        },
        async login(email, password) {
            this.loading = true
            this.error = null
            try {
                const response = await fetch('http://localhost:8000/api/login', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: JSON.stringify({
                        email: email,
                        password: password
                    })
                })
                const data = await response.json()
                if (response.status !== 200) {
                    this.error = data.error || 'Mauvais identifiants'
                    this.loading = false
                    throw new Error('Connexion échouée')
                }
                this.token = data.token
                this.userId = data.user_id
                localStorage.setItem('token', this.token)
                localStorage.setItem('userId', this.userId)
                this.loading = false
            } catch (error) {
                this.loading = false
                this.error = 'Erreur réseau ou serveur'
                throw error
            }
        },
        logout() {
            this.token = null
            this.userId = null
            localStorage.removeItem('token')
            localStorage.removeItem('userId')
        }
    }
})
