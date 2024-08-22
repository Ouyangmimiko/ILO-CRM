<template>
  <div class="login-bg">
    <div class="login-container">
      <div class="login-header">
        <img class="logo mr10" src="../../assets/vue.svg" alt="logo" />
        <div class="login-title">ILO CRM</div>
      </div>
      <el-form :model="param" :rules="rules" ref="login" size="large">
        <el-form-item prop="email">
          <el-input v-model="param.email" placeholder="Email">
            <template #prepend>
              <el-icon>
                <User />
              </el-icon>
            </template>
          </el-input>
        </el-form-item>
        <el-form-item prop="password">
          <el-input
            type="password"
            placeholder="Password"
            v-model="param.password"
            @keyup.enter="submitForm(login)"
          >
            <template #prepend>
              <el-icon>
                <Lock />
              </el-icon>
            </template>
          </el-input>
        </el-form-item>
        <div class="pwd-tips">
          <el-checkbox
            class="pwd-checkbox"
            v-model="checked"
            label="Remember Password"
          />
          <el-link type="primary" @click="$router.push('/reset-pwd')"
            >Forget the password？</el-link
          >
        </div>
        <el-button
          class="login-btn"
          type="primary"
          size="large"
          @click="submitForm(login)"
        >
          Log In
        </el-button>

        <p class="login-text">
          Didn't have an account？<el-link
            type="primary"
            @click="$router.push('/register')"
            >Sign up</el-link
          >
        </p>
      </el-form>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ElMessage, FormInstance, FormRules } from "element-plus";
import { reactive, ref } from "vue";
import { useRouter } from "vue-router";
import { usePermissStore } from "../../store/permiss";
import axios, {AxiosError} from "axios";

interface LoginInfo {
  email: string;
  password: string;
}

const lgStr = localStorage.getItem("login-param");
const defParam = lgStr ? JSON.parse(lgStr) : null;
const checked = ref(lgStr ? true : false);

const router = useRouter();
const param = reactive<LoginInfo>({
  email: defParam ? defParam.email : "",
  password: defParam ? defParam.password : "",
});

const rules: FormRules = {
  email: [
    { required: true, message: "Please Input Email", trigger: "blur" },
  ],
  password: [
    { required: true, message: "Please Input Password", trigger: "blur" },
  ],
};
const permiss = usePermissStore();
const login = ref<FormInstance>();

const submitForm = async (formEl: FormInstance | undefined) => {
  if (!formEl) return;
  formEl.validate(async (valid: boolean) => {
    if (valid) {
      try {
        // sen request to /api/login
        const response = await axios.post("/api/login", {
          email: param.email,
          password: param.password,
        });

        if (response.data.status) {
          ElMessage.success(response.data.message);


          const { user, token, is_admin } = response.data;
          // Store user
          localStorage.setItem("ILO_user", user);
          localStorage.setItem("ILO_userId", user.id.toString());
          localStorage.setItem("ILO_user_name", user.name);
          if (user.name === 'admin') {
            localStorage.setItem("User_role", 'master');
          } else {
            localStorage.setItem("User_role", is_admin === 1 ? "admin" : "user");
          }
          // Store api token
          localStorage.setItem("auth_token", token);

          // Set permission of user
          const keys = permiss.defaultList[is_admin === 1 ? "admin" : "user"];
          permiss.handleSet(keys);
          router.push("/");

          // store login information
          if (checked.value) {
            localStorage.setItem("login-param", JSON.stringify(param));
          } else {
            localStorage.removeItem("login-param");
          }
        } else {
          // Handle login exception
          ElMessage.error(response.data.message);
        }
      } catch (error) {
        // Handle errors, show error message from server
        if (error instanceof AxiosError && error.response && error.response.data) {
          ElMessage.error(error.response.data.message);
        } else {
          ElMessage.error("An unexpected error occurred while logging in.");
        }
      }
    } else {
      ElMessage.error("Please complete the form correctly.");
    }
  });
};
</script>

<style scoped>
.login-bg {
  display: flex;
  align-items: center;
  justify-content: center;
  width: 100%;
  height: 100vh;
  background: url(../../assets/img/login-bg.jpg) center/cover no-repeat;
}

.login-header {
  display: flex;
  align-items: center;
  justify-content: center;
  margin-bottom: 40px;
}

.logo {
  width: 35px;
}

.login-title {
  font-size: 22px;
  color: #333;
  font-weight: bold;
}

.login-container {
  width: 450px;
  border-radius: 5px;
  background: #fff;
  padding: 40px 50px 50px;
  box-sizing: border-box;
}

.pwd-tips {
  display: flex;
  justify-content: space-between;
  align-items: center;
  font-size: 14px;
  margin: -10px 0 10px;
  color: #787878;
}

.pwd-checkbox {
  height: auto;
}

.login-btn {
  display: block;
  width: 100%;
}

.login-tips {
  font-size: 12px;
  color: #999;
}

.login-text {
  display: flex;
  align-items: center;
  margin-top: 20px;
  font-size: 14px;
  color: #787878;
}
</style>
