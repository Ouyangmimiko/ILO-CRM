<template>
  <el-form :model="formData" ref="formRef">
    <!-- 遍历表格头数据结构，生成表单项 -->
    <el-form-item
        v-for="field in formFields"
        :key="field.prop"
        :label="field.label"
        :prop="field.prop"
    >
      <el-input v-model="formData[field.prop]" />
      <!-- 如果字段类型不止input，可以使用动态组件 -->
    </el-form-item>

    <el-form-item>
      <el-button type="primary" @click="addField">Add Field</el-button>
      <el-button type="primary" @click="submitForm">Submit</el-button>
    </el-form-item>
  </el-form>
</template>

<script lang="ts" setup>
import { ref, reactive } from 'vue';
import { FormInstance } from 'element-plus';

// 表单实例引用
const formRef = ref<FormInstance>();

// 表格头结构，包含固定字段和可能的动态字段
const formFields = ref([
  { label: 'Username', prop: 'username' },
  { label: 'Email', prop: 'email' },
]);

// 表单数据，初始包含固定字段
const formData = reactive<Record<string, any>>({
  username: '',
  email: '',
  // 动态字段会在运行时加入这里
});

// 添加动态字段的方法
const addField = () => {
  const newFieldProp = `field${formFields.value.length + 1}`;
  formFields.value.push({
    label: `Dynamic Field ${formFields.value.length + 1}`,
    prop: newFieldProp,
  });
  // 初始化formData中的新字段
  formData[newFieldProp] = '';
};

const submitForm = () => {
  if (formRef.value) {
    formRef.value.validate((valid) => {
      if (valid) {
        console.log('Submit:', formData);
      } else {
        console.log('Error: Form validation failed');
      }
    });
  }
};
</script>

<style scoped>

</style>