<template>
  <div>
    <h1>User Management</h1>

    <el-table :data="users" style="width: 100%">
      <el-table-column
          v-for="field in fields"
          :key="field"
          :prop="field"
          :label="field"
      ></el-table-column>
    </el-table>

    <div>
      <el-button type="primary" @click="showCreatUserDialog">Create New User</el-button>
      <!-- Dialog of creating user -->
      <el-dialog
          title="Create New User"
          v-model="isDialogVisible"
          width="500px"
          @close="resetForm"
      >
        <el-form :model="newUser" ref="formRef" label-width="120px">
          <el-form-item label="Name" prop="name" :rules="[{ required: true, message: 'Name is required', trigger: 'blur' }]">
            <el-input v-model="newUser.name" />
          </el-form-item>
          <el-form-item label="Email" prop="email" :rules="[{ required: true, message: 'Email is required', trigger: 'blur' }]">
            <el-input v-model="newUser.email" />
          </el-form-item>
          <el-form-item label="Password">
            <el-input v-model="newUser.password" type="password" />
            <small style="color: #888;">Default password: abc123</small>
          </el-form-item>
        </el-form>
        <div slot="footer" class="dialog-footer">
          <el-button @click="isDialogVisible = false">Cancel</el-button>
          <el-button type="primary" @click="creatUser">Confirm</el-button>
        </div>
      </el-dialog>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, onMounted, reactive } from "vue";
import axios from "../../api/axios";
import {AxiosError} from "axios";
import {ElMessage} from "element-plus";

const fields = ref<string[]>([]);
const users = ref([]);
const isDialogVisible = ref(false);

const newUser = reactive({
  name: '',
  email: '',
  password: 'abc123' // default password
});

const formRef = ref<any>(null);

const showCreatUserDialog = () => {
  isDialogVisible.value = true;
  console.log("Dialog visibility:", isDialogVisible.value);
};

// form reset
const resetForm = () => {
  newUser.name = '';
  newUser.email = '';
  newUser.password = 'abc123';
};
const getUsers = async () => {
  try {
    const usersData = await axios.get("/api/users");
    fields.value = usersData.data.fields;
    users.value = usersData.data.users;

  } catch (error) {
    if (error instanceof AxiosError && error.response && error.response.data) {
      ElMessage.error(error.response.data.message);
    } else {
      ElMessage.error("An unexpected error occurred while getting users.");
    }
  }
};
onMounted(getUsers);
const creatUser = async () => {
  if (formRef.value) {
    await formRef.value.validate();
  }
  try {
    await axios.post('/api/users/add', newUser)
    ElMessage.success("User created successfully.");
    isDialogVisible.value = false;
    await getUsers();
  } catch (error) {
    if (error instanceof AxiosError && error.response && error.response.data) {
      ElMessage.error(error.response.data.message);
    } else {
      ElMessage.error('An unexpected error occurred while creating user.');
    }
  }
};
</script>
