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
const ifShowCard = ref(false);
const passwordFormRef = ref<any>(null);

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
const handleChangePass = () => {
  ifShowCard.value = !ifShowCard.value;
}

const resetForm = {
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
};

const passwordForm = ref({
  current_password: '',
  new_password: '',
  new_password_confirmation: '',
});

const rules = {
      current_password: [
        { required: true, message: 'Please input your current password', trigger: 'blur' },
      ],
      new_password: [
        { required: true, message: 'Please input a new password', trigger: 'blur' },
        { min: 6, message: 'Password length should be at least 6 characters', trigger: 'blur' },
      ],
      new_password_confirmation: [
        { required: true, message: 'Please confirm your new password', trigger: 'blur' },
        { validator: (_rule: any, value: any, callback: (error?: Error) => void): void => {
            if (value !== passwordForm.value.new_password) {
              callback(new Error('Passwords do not match'));
            } else {
              callback();
            }
          }, trigger: 'blur'
        }
      ],
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
      errorMessage.push(error.response.data.message);
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
      ElMessage.error('An unexpected error occurred while update.');
    }
  }
};
const updatePass = async () => {
  if (passwordFormRef.value) {
    passwordFormRef.value.validate(async (valid: boolean) => {
      if (valid) {
        try {
          const response = await axios.post(`/api/users/changePassword`, passwordForm.value);
          ElMessage.success(response.data.message);
          handleChangeCancel();
        } catch (error) {
          if (error instanceof AxiosError && error.response && error.response.data) {
            let errorMessage = [];
            errorMessage.push(error.response.data.message);
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
            ElMessage.error('An unexpected error occurred while update.');
          }
        }
      } else {
        ElMessage.error("Failed to update password");
      }
    })
  }
};
const handleChangeCancel = () => {
  passwordFormRef.value.resetFields();
  passwordForm.value = resetForm;
  ifShowCard.value = false;
};
onMounted(() => {
  getUserInfo();
})
</script>

<template>
  <div>
    <el-descriptions
        title="User Info"
        :column="3"
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
    <el-button @click="handleChangePass" v-if="!ifShowForm">Change Password</el-button>
    <el-form :model="form" v-if="ifShowForm" style="width: 25vw">
      <el-form-item label="Username" prop="name" label-position="top">
        <el-input style="margin-left: auto; justify-content: flex-end; width: 300px" :disabled="inputDisable" v-model="form.name" />
      </el-form-item>
      <el-form-item label="Email address" prop="email" label-position="top">
        <el-input style="margin-left: auto; justify-content: flex-end; width: 300px" v-model="form.email" />
      </el-form-item>
    </el-form>
    <div style="margin-left: auto; justify-content: flex-end; width: 80vw">
      <el-button v-if="ifShowForm" @click="handleCancel">Cancel</el-button>
      <el-button v-if="ifShowForm" type="primary" @click="handleConfirm">Confirm</el-button>
    </div>
    <el-card class="box-card" v-if="ifShowCard" style="width: 50vw; margin-top: 20px">
      <h3>Change Password</h3>
      <el-form :model="passwordForm" :rules="rules" ref="passwordFormRef" label-width="120px" label-position="top" style="width: 25vw; ">
        <el-form-item label="Current Password" prop="current_password">
          <el-input
              type="password"
              v-model="passwordForm.current_password"
              autocomplete="off"
          ></el-input>
        </el-form-item>

        <el-form-item label="New Password" prop="new_password">
          <el-input
              type="password"
              v-model="passwordForm.new_password"
              autocomplete="off"
              placeholder="Required min length: 6"
          ></el-input>
        </el-form-item>

        <el-form-item label="Confirm Password" prop="new_password_confirmation">
          <el-input
              type="password"
              v-model="passwordForm.new_password_confirmation"
              autocomplete="off"
              placeholder="Repeat new password"
          ></el-input>
        </el-form-item>

        <el-form-item>
          <el-button type="primary" @click="updatePass">Update Password</el-button>
          <el-button @click="handleChangeCancel"> Cancel</el-button>
        </el-form-item>
      </el-form>
    </el-card>
  </div>
</template>

<style scoped>
.el-descriptions__label{
  color: #337ecc;
}
</style>