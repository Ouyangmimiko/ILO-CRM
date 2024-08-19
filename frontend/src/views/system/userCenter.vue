<script setup lang="ts">
import { ref, onMounted, reactive } from "vue";
import axios from "../../api/axios";
// import {AxiosError} from "axios";
import {ElMessage} from "element-plus";
import {AxiosError} from "axios";

const userInfo = ref<any>(null);
const userRole = localStorage.getItem("User_role");
const ifShowForm = ref(false);
const inputDisable = ref(false);

const form = reactive({
  name: '',
  email: '',
  is_admin: false,
});
const handleEdit = () => {
  ifShowForm.value = true;
};
const handleCancel = () => {
  ifShowForm.value = false;
  getUserInfo();
}

let id = 0;
const getUserInfo = async () => {
  try {
    const userId = localStorage.getItem("ILO_userId");
    if (userId != null && userId !== "") {
      id = parseInt(userId);
    }
    const response = await axios.get(`/api/users/${id}`);
    userInfo.value = response.data;
    form.name = response.data.name;
    form.email = response.data.email;
    form.is_admin = response.data.is_admin;
    inputDisable.value = response.data.name === "admin";
  } catch (e) {
    ElMessage.error("An unexpected error occurred");
  }
};
const handleConfirm = async () => {
  try {
     await axios.put(`/api/users/${id}`, form);
     await getUserInfo();
     ifShowForm.value = false;
  } catch (error) {
    if (error instanceof AxiosError && error.response && error.response.data) {
      let errorMessage = [];
      if (error.response.data.errors) {
        for (let field in error.response.data.errors) {
          if (error.response.data.errors.hasOwnProperty(field)) {
            error.response.data.errors[field].forEach((errMsg: string) => {
              errorMessage.push(`${field}: ${errMsg}`);
            });
          }
        }
      }
      console.log(errorMessage.join('\n'));
      ElMessage({
        message: errorMessage.join('<br>'),
        type: 'error',
        dangerouslyUseHTMLString: true
      });
    } else {
      ElMessage.error('An unexpected error occurred while search.');
    }
  }
};
onMounted(() => {
  getUserInfo();
})
</script>

<template>
  <div>
    <el-descriptions
        title="User Info"
        column="3"
        size="large"
        v-if="!ifShowForm"
    >
      <el-descriptions-item>
        <label class="el-descriptions__label">User Name</label>
        <span v-if="userInfo">{{ userInfo.name }}</span>
      </el-descriptions-item>
      <el-descriptions-item>
        <label class="el-descriptions__label">Email Address</label>
        <span v-if="userInfo">{{ userInfo.email }}</span>
      </el-descriptions-item>
      <el-descriptions-item>
        <label class="el-descriptions__label">User Role</label>
        <el-tag size="small" v-if="userInfo">{{ userRole }}</el-tag>
      </el-descriptions-item>
    </el-descriptions>
    <el-button type="primary" @click="handleEdit" v-if="!ifShowForm">Edit</el-button>
    <el-form :model="form" v-if="ifShowForm" style="width: 25vw">
      <el-form-item label="Username" prop="name">
        <el-input style="margin-left: auto; justify-content: flex-end; width: 300px" :disabled="inputDisable" v-model="form.name" />
      </el-form-item>
      <el-form-item label="Email address" prop="email">
        <el-input style="margin-left: auto; justify-content: flex-end; width: 300px" v-model="form.email" />
      </el-form-item>
    </el-form>
    <div style="margin-left: auto; justify-content: flex-end; width: 80vw">
      <el-button v-if="ifShowForm" @click="handleCancel">Cancel</el-button>
      <el-button v-if="ifShowForm" type="primary" @click="handleConfirm">Confirm</el-button>
    </div>

  </div>
</template>

<style scoped>
.el-descriptions__label{
  color: #337ecc;
}
</style>