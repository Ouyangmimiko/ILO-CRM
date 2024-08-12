<template>
  <div>
    <h1>Role Management</h1>
    <el-table :data="users" style="width: 100%">
      <el-table-column
        prop="name"
        label="Name"
      ></el-table-column>
      <el-table-column
          prop="is_admin"
          label="Role Management"
      >
        <template v-slot="scope">
          <el-switch
              v-model="scope.row.is_admin"
              @change="handleSwitch(scope.row)"
              active-text="Administrator"
              inactive-text="Normal user"
              :disabled="switchDisabled(scope.row)"
          ></el-switch>
        </template>
      </el-table-column>
    </el-table>

    <el-dialog
      title="Confirm changing user role?"
      v-model="dialogVisible"
      @close="resetDialog"
      width="40%"
      >
      <span>Are you sure you want to update this setting?</span>
      <span slot="footer" class="dialog-footer">
      <el-button @click="resetDialog">Cancel</el-button>
      <el-button type="primary" @click="confirmUpdate">Confirm</el-button>
    </span>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import axios from "../../api/axios.ts";
import {AxiosError} from "axios";
import {ElMessage} from "element-plus";
import {onMounted, ref} from "vue";

const users = ref([]);
const originUsers = ref([]);
const dialogVisible = ref(false);
const userToUpdate = ref<any>(null);

const handleSwitch = (row: any) => {
  userToUpdate.value = row;
  dialogVisible.value = true;
};

const resetDialog = () => {
  if (userToUpdate.value) {
    // Restore the original status when the dialog is closed
    const user = users.value.find(user => (user as {id: number}).id === userToUpdate.value.id);
    const originUser = originUsers.value.find(user => (user as {id: number}).id === userToUpdate.value.id);
    if (user && originUser) {
      (user as {is_admin: boolean}).is_admin = (originUser as {is_admin: boolean}).is_admin;
    }
  }
  dialogVisible.value = false;
  userToUpdate.value = null;
};

const switchDisabled = (row: any) => {
  return row.name === "admin";

}

const confirmUpdate = async () => {
  console.log(userToUpdate.value);
  try {
    const response = await axios.put(`/api/users/${userToUpdate.value.id}`, userToUpdate.value);
    ElMessage.success(response.data.message);
    await getUsers();
  } catch (error) {
    if (error instanceof AxiosError && error.response && error.response.data) {
      ElMessage.error(error.response.data.message);
    } else {
      ElMessage.error("An unexpected error occurred while getting users.");
    }
  }
  resetDialog();
};

const getUsers = async () => {
  try {
    const usersData = await axios.get("/api/users");
    users.value = usersData.data.users;
    console.log(typeof users.value);
    originUsers.value = JSON.parse(JSON.stringify(usersData.data.users));
  } catch (error) {
    if (error instanceof AxiosError && error.response && error.response.data) {
      ElMessage.error(error.response.data.message);
    } else {
      ElMessage.error("An unexpected error occurred while getting users.");
    }
  }
};
onMounted(getUsers);
</script>
