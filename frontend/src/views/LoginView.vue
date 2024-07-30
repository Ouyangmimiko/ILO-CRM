<template>
    <div class="container">
        <div class="columns">
            <div class="column is-4 is-offset-4">
                <h1 class="title">Log in</h1>

                <form @submit.prevent="handleLogin">
                    <div class="field">
                        <label>Email</label>
                        <div class="control">
                            <input type="email" name="email" class="input" v-model="email" placeholder="e.g. alex@smith.com">
                        </div>
                    </div>

                    <div class="field">
                        <label>Password</label>
                        <div class="control">
                            <input type="password" name="password" class="input" v-model="password">
                        </div>
                    </div>

                     <!--show error messages-->
                     <div class="notification is-danger" v-if="errors.length">
                       <span v-for="error in errors" :key="error" style="display:inline;">{{ error }}</span>
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
import { useStore } from 'vuex';
import axios from 'axios';
import { useRouter } from 'vue-router';


const email = ref('');
const password = ref('');
const errors = ref('');

const router = useRouter();
const store = useStore();

const handleLogin = async () => {
  errors.value = [];
  try {
    const credentials = {
          email: email.value,
          password: password.value,
        };
    const response = await axios.post('/api/login', credentials);

    if (response.status === 200) {
      const token = response.data.token;
      store.commit('setToken', token);
      // axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
      localStorage.setItem('token', token);
      router.push('/dashboard/my-account');
    } else {
      errors.value = response.data.message;
    }
  } catch (error) {
    console.log('here');
    errors.value = error.response.data.message;
  }
}

// export default defineComponent({
//   setup() {
//     const email = ref('');
//     const password = ref('');
//     const errors = ref('');
//
//     axios.defaults.headers.common['Authorization'] = '';
//     localStorage.removeItem('token');
//     const handleLogin = async () => {
//       try {
//         const credentials = {
//           email: email.value,
//           password: password.value,
//         };
//         const response = await login(credentials);
//
//         if (response.status) {
//           // get token from response
//           const token = response.data.token;
//           // use store index.ts to manage state
//           store.commit('setToken', token);
//
//           axios.defaults.headers.common['Authorization'] = 'Token ' + token;
//
//           localStorage.setItem('token', token);
//           // print stored token
//           //console.log('Token stored in localStorage:', localStorage.getItem('token'));
//
//           router.push('/dashboard/my-account');
//         } else {
//           errors.value = response.data.message;
//         }
//       } catch (error) {
//         errors.value = error.message;
//       }
//     }
//   }
// })
</script>

<!--<script setup>-->
<!--import { ref } from 'vue';-->
<!--import axios from 'axios';-->
<!--import { useRouter } from 'vue-router';-->
<!--//import { useStore } from 'vuex';-->
<!--import store from '@/store';-->

<!--// define data-->
<!--const username = ref('');-->
<!--const password = ref('');-->
<!--const errors = ref([]);-->

<!--const router = useRouter();-->

<!--function submitForm() {-->
<!--    // initialize login-->
<!--    axios.defaults.headers.common['Authorization'] = ''-->
<!--    localStorage.removeItem('token')-->

<!--    errors.value = [] //keep or not？-->

<!--    const formData = {-->
<!--        username: username.value,-->
<!--        password: password.value-->
<!--    }-->

<!--    axios-->
<!--    .post('@/backend/api', formData)-->
<!--    .then(response => {-->
<!--        // get auth_token from response-->
<!--        const token = response.data.auth_token;-->
<!--        // use store index.ts to manage state-->
<!--        store.commit('setToken', token);-->

<!--        axios.defaults.headers.common['Authorization'] = 'Token ' + token;-->

<!--        localStorage.setItem('token', token);-->
<!--        // print stored token-->
<!--        //console.log('Token stored in localStorage:', localStorage.getItem('token'));-->

<!--        router.push('/dashboard/my-account');-->
<!--    })-->
<!--    .catch(error => {-->
<!--        // if (error.response) {-->
<!--        //     console.log(error.response.data)-->
<!--        //     errors.value.push('password error')-->
<!--        // } else if (error.message) {-->
<!--        //     errors.value.push('Somethig went wrong, please try again!')-->
<!--        // }-->
<!--      errors.value = error.message;-->
<!--    })-->
<!--}-->
<!--</script>-->