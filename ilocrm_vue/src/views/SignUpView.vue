<template>
    <div class="container">
        <div class="columns">
            <div class="column is-4 is-offset-4">
                <h1 class="title">Sign up</h1>

                <form @submit.prevent="submitForm">
                    <div class="field">
                        <label>Email</label>
                        <div class="control">
                            <input type="email" name="email" class="input" v-model="username" placeholder="e.g. alex@smith.com">
                        </div>
                    </div>

                    <div class="field">
                        <label>Password</label>
                        <div class="control">
                            <input type="password" name="password1" class="input" v-model="password1">
                        </div>
                    </div>

                    <div class="field">
                        <label>Authentication Password</label>
                        <div class="control">
                            <input type="password" name="password2" class="input" v-model="password2">
                        </div>
                    </div>

                    <!--show warning messages-->
                    <div class="notification is-danger" v-if="errors.length">
                        <p v-for="error in errors" v-bind:key="error">{{ error }}</p>
                    </div>

                    <div class="field">
                        <div class="control">
                            <button class="button is-success">Submit</button>
                        </div>
                    </div>

                </form>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue';
import axios from 'axios';
import { toast } from 'bulma-toast';
import { useRouter } from 'vue-router';

// define data
const username = ref('');
const password1 = ref('');
const password2 = ref('');
const errors = ref([]);

const router = useRouter();

// define methods
function submitForm() {
    // show warning massages method
    errors.value = [];
    if(username.value === '') {
        errors.value.push('The username is missing')
    }
    if(password1.value === '') {
        errors.value.push('The password is too short')
    }
    if(password2.value === '') {
        errors.value.push('The authentication password is missing')
    }

    // submit the form data using axios
    if(errors.value.length === 0) {
        const formData = {
            username: username.value,
            password: password1.value,
            password2: password2.value
        }

        axios
        .post('/api/register', formData) 
        .then(response => {
            toast({ 
                message: 'Account was created, please log in', 
                type: 'is-success',
                dismissible: true,
                pauseOnHover: true,
                duration: 2000,
                position: 'bottom-right'
            });

            // navigate to login page
            router.push('/log-in');
        })
        .catch(error => {
            if (error.response) {
                for (const property in error.response.data) {
                    errors.value.push('${property}: ${error.response.data[property]}')
                }
            } else if (error.message) {
                errors.value.push('Somethig went wrong, please try again!')
            }
        });
    }
}
</script>