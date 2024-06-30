<template>
    <div class="container">
        <div class="columns is-multiline">
            <div class="column is-12">
                <h1 class="title">My Account</h1>
            </div>

            <div class="colum is-12">
                <button v-on:click="logout()" class="button is-danger">Log out</button>
            </div>
        </div>
    </div>
</template>

<script setup>
import axios from 'axios';
import { useRouter } from 'vue-router';
import store from '@/store';

const router = useRouter();

const logout = async () => {
  try {
    await axios.post('/api/token/logout');
    console.log('Logged out');
  } catch (error) {
    console.log(JSON.stringify(error));
  }

  // back to initial state
  axios.defaults.headers.common['Authorization'] = '';
  localStorage.removeItem('token');
  store.commit('removeToken');

  router.push('/');
}

</script>