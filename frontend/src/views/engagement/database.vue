<template>
  <div class="container">
    <!-- form -->
    <el-form :model="queryForm" inline class="query-form">
      <el-form-item label="Search All">
        <el-select
          v-model="queryForm.search_all"
          placeholder="Select an option"
          class="form-select"
        >
          <el-option label="True" :value="true" />
          <el-option label="False" :value="false" />
        </el-select>
      </el-form-item>
      <el-form-item label="Search Type">
        <el-select
          v-model="queryForm.search_type"
          placeholder="Select search type"
          class="form-select"
        >
          <el-option label="Organisation" value="organisation" />
          <el-option label="First Name" value="first_name" />
          <el-option label="Surname" value="surname" />
          <el-option label="Job Title" value="job_title" />
          <el-option label="Email" value="email" />
          <el-option label="Location" value="location" />
        </el-select>
      </el-form-item>
      <el-form-item label="Search Term">
        <el-input
          v-model="queryForm.search_term"
          placeholder="Enter search term"
          class="form-input"
        />
      </el-form-item>
      <el-form-item class="form-actions">
        <el-button type="primary" @click="handleQuery">Query</el-button>
        <el-button @click="handleReset">Reset</el-button>
      </el-form-item>
    </el-form>

    <!-- More Function -->
    <div class="function-buttons">
      <el-button type="primary" @click="handleAdd">Add</el-button>
      <el-upload
        :action="uploadUrl"
        :before-upload="handleBeforeUpload"
        :auto-upload="true"
        :file-list="fileList"
        :on-remove="handleRemove"
      >
        <el-button>Upload Excel</el-button>
      </el-upload>

      <el-button @click="exportExcel">Export</el-button>
    </div>

    <!-- table -->
    <el-table
      :data="data"
      stripe
      border
      style="width: 100%"
      max-height="500"
      table-layout="fixed"
      size="small"
    >
      <!-- Table columns here -->
      <el-table-column
        fixed
        prop="organisation"
        label="Organisation"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="organisation_sector"
        label="Organisation Sector"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="first_name"
        label="First Name"
        width="120"
        class-name="table-column"
      />
      <el-table-column
        prop="surname"
        label="Surname"
        width="120"
        class-name="table-column"
      />
      <el-table-column
        prop="job_title"
        label="Job Title"
        width="120"
        class-name="table-column"
      />
      <el-table-column
        prop="email"
        label="Email"
        width="180"
        class-name="table-column"
      />
      <el-table-column
        prop="location"
        label="Location"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="uob_alumni"
        label="UoB Alumni"
        width="120"
        class-name="table-column"
      />
      <el-table-column
        prop="programme_of_study_engaged"
        label="Programme of Study Engaged"
        width="250"
        class-name="table-column"
      />
      <el-table-column
        prop="mentoring_2021_22"
        label="Mentoring 2021/22"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="mentoring_2022_23"
        label="Mentoring 2022/23"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="mentoring_2023_24"
        label="Mentoring 2023/24"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="yii_2021_22"
        label="YII 2021/22"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="yii_2022_23"
        label="YII 2022/23"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="yii_2023_24"
        label="YII 2023/24"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="projects_2021_22"
        label="Projects 2021/22"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="projects_2022_23"
        label="Projects 2022/23"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="projects_2023_24"
        label="Projects 2023/24"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        prop="info_related_to_contacting_the_partner"
        label="Contact Info"
        width="250"
        class-name="table-column"
      />
      <el-table-column fixed="right" label="Operations" min-width="150">
        <template #default="scope">
          <div class="operation-buttons">
            <div>
              <el-button size="small" @click="handleEdit(scope.row)">
                Edit
              </el-button>
            </div>
            <div>
              <el-button
                size="small"
                type="danger"
                @click="handleDelete(scope.row.id)"
              >
                Delete
              </el-button>
            </div>
          </div>
        </template>
      </el-table-column>
    </el-table>

    <!-- dialog for add/edit -->
    <el-dialog
      v-model="dialogVisible"
      :title="editForm.id ? 'Edit Data' : 'Add Data'"
      :before-close="handleClose"
      width="900px"
      class="styled-dialog"
      :style="{ maxHeight: '80vh', overflow: 'auto' }"
    >
      <el-form
        :model="editForm"
        :rules="rules"
        label-width="200px"
        ref="formRef"
        class="two-column-form"
      >
        <el-row :gutter="20">
          <!-- 第一列 -->
          <el-col :span="12">
            <el-form-item label="ID" v-if="editForm.id">
              <el-input v-model="editForm.id" disabled></el-input>
            </el-form-item>
            <el-form-item label="Organisation">
              <el-input v-model="editForm.organisation"></el-input>
            </el-form-item>
            <el-form-item label="Organisation Sector">
              <el-input v-model="editForm.organisation_sector"></el-input>
            </el-form-item>
            <el-form-item label="First Name">
              <el-input v-model="editForm.first_name"></el-input>
            </el-form-item>
            <el-form-item label="Surname">
              <el-input v-model="editForm.surname"></el-input>
            </el-form-item>
            <el-form-item label="Job Title">
              <el-input v-model="editForm.job_title"></el-input>
            </el-form-item>
            <el-form-item label="Email" prop="email">
              <el-input v-model="editForm.email" />
            </el-form-item>
            <el-form-item label="Location">
              <el-input v-model="editForm.location"></el-input>
            </el-form-item>
            <el-form-item label="Info Related to Contacting the Partner">
              <el-input
                type="textarea"
                v-model="editForm.info_related_to_contacting_the_partner"
              ></el-input>
            </el-form-item>
          </el-col>
          <!-- 第二列 -->
          <el-col :span="12">
            <el-form-item label="UOB Alumni">
              <el-switch
                v-model="editForm.uob_alumni"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="Mentoring 2021-22">
              <el-switch
                v-model="editForm.mentoring_2021_22"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="Mentoring 2022-23">
              <el-switch
                v-model="editForm.mentoring_2022_23"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="Mentoring 2023-24">
              <el-switch
                v-model="editForm.mentoring_2023_24"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="YII 2021-22">
              <el-switch
                v-model="editForm.yii_2021_22"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="YII 2022-23">
              <el-switch
                v-model="editForm.yii_2022_23"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="YII 2023-24">
              <el-switch
                v-model="editForm.yii_2023_24"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="Projects 2021-22">
              <el-switch
                v-model="editForm.projects_2021_22"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="Projects 2022-23">
              <el-switch
                v-model="editForm.projects_2022_23"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <el-form-item label="Projects 2023-24">
              <el-switch
                v-model="editForm.projects_2023_24"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
          </el-col>
        </el-row>

        <el-form-item>
          <el-button type="primary" @click="handleConfirm">Confirm</el-button>
          <el-button @click="handleClose">Cancel</el-button>
        </el-form-item>
      </el-form>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted } from "vue";
import axios from "../../api/axios";
import { ElForm, ElMessage, FormRules } from "element-plus";
import { AxiosError } from "axios";

const queryForm = reactive({
  search_all: false, // boolean for search_all
  search_type: "",
  search_term: "",
});

const dialogVisible = ref(false);

type EditFormType = {
  id: string;
  organisation: string;
  organisation_sector: string;
  first_name: string;
  surname: string;
  job_title: string;
  email: string;
  location: string;
  uob_alumni: string;
  programme_of_study_engaged: string;
  mentoring_2021_22: string;
  mentoring_2022_23: string;
  mentoring_2023_24: string;
  yii_2021_22: string;
  yii_2022_23: string;
  yii_2023_24: string;
  projects_2021_22: string;
  projects_2022_23: string;
  projects_2023_24: string;
  info_related_to_contacting_the_partner: string;
};

const editForm: EditFormType = reactive({
  id: "", // 默认空值
  organisation: "",
  organisation_sector: "",
  first_name: "",
  surname: "",
  job_title: "",
  email: "",
  location: "",
  uob_alumni: "",
  programme_of_study_engaged: "",
  mentoring_2021_22: "",
  mentoring_2022_23: "",
  mentoring_2023_24: "",
  yii_2021_22: "",
  yii_2022_23: "",
  yii_2023_24: "",
  projects_2021_22: "",
  projects_2022_23: "",
  projects_2023_24: "",
  info_related_to_contacting_the_partner: "",
});

// 校验规则
const rules: FormRules = {
  email: [
    { required: true, message: "Email is required", trigger: "blur" },
    {
      type: "email",
      message: "Invalid email address",
      trigger: ["blur", "change"],
    },
  ],
  // 其他字段的校验规则
};

// 表单引用
const formRef = ref<InstanceType<typeof ElForm> | null>(null);

const data = ref<any[]>([]);

const fileList = ref([]);
const uploadUrl = "/api/import";

const handleQuery = async () => {
  try {
    const { search_all, search_type, search_term } = queryForm;

    // Construct query parameters
    const params: any = {};
    if (search_all !== undefined) {
      params.search_all = search_all;
    }
    if (search_type) {
      params.search_type = search_type;
    }
    if (search_term) {
      params.search_term = search_term;
    }

    // Make request
    const response = await axios.get("/api/customers", { params });
    data.value = response.data.customers;
  } catch (error) {
    console.error("Query failed:", error);
  }
};

const handleReset = () => {
  queryForm.search_all = false;
  queryForm.search_type = "";
  queryForm.search_term = "";
  handleQuery(); // Trigger query after resetting
};

const handleAdd = () => {
  // 清空表单并设置为新增模式
  Object.keys(editForm).forEach((key) => {
    editForm[key as keyof EditFormType] = "";
  });
  editForm.id = ""; // 清空ID，表示新增
  console.log(editForm);
  dialogVisible.value = true;
};

const handleEdit = (row: any) => {
  Object.assign(editForm, row);
  console.log(row);
  dialogVisible.value = true;
};

const handleDelete = async (id: any) => {
  try {
    await axios.delete(`/api/data/${id}`);
    ElMessage.success("Entry deleted successfully");
    handleQuery(); // Refresh data after deletion
  } catch (error) {
    ElMessage.error("Error deleting entry");
  }
};

// Handle file upload
const handleBeforeUpload = (file: string | Blob) => {
  // Create FormData object
  const formData = new FormData();
  formData.append("file", file);

  // Upload the file
  axios
    .post(uploadUrl, formData, {
      headers: {
        "Content-Type": "multipart/form-data",
      },
    })
    .then(() => {
      ElMessage.success("File uploaded successfully");
      handleQuery(); // Refresh data after successful upload
    })
    .catch(() => {
      ElMessage.error("Error uploading file");
    });

  // Prevent the default upload behavior
  return false;
};

// Handle file remove
const handleRemove = () => {
  // Handle file removal logic if necessary
};

const exportExcel = () => {
  axios
    .get("/api/export", { responseType: "blob" })
    .then(() => {
      const url = window.URL.createObjectURL(new Blob());
      const link = document.createElement("a");
      link.href = url;
      link.setAttribute("download", "export.xlsx"); // You can specify the file name here
      document.body.appendChild(link);
      link.click();
      document.body.removeChild(link);
    })
    .catch(() => {
      ElMessage.error("Error exporting data");
    });
};

const handleClose = () => {
  dialogVisible.value = false;
};

const handleConfirm = async () => {
  try {
    // 校验表单
    await formRef.value?.validate();

    if (editForm.id) {
      // 如果存在ID，则是编辑模式，执行更新操作
      const response = await axios.put(`/api/update/${editForm.id}`, editForm);

      if (response.status === 200) {
        ElMessage.success(response.data.message);
        handleQuery();
      } else {
        ElMessage.success(response.data.errors);
      }
    } else {
      // 如果没有ID，则是新增模式，执行新增操作
      const response = await axios.post("/api/create", editForm);
      console.log(response, "respinse");
      if (response.status === 201) {
        ElMessage.success(response.data.message);
        handleQuery();
      } else {
        ElMessage.success(response.data.errors);
      }
    }
  } catch (error: unknown) {
    if (error instanceof AxiosError) {
      console.error("Update failed:", error);

      const errorMessage = error.response?.data.errors;
      if (errorMessage) {
        // 确保 errorMessage 是对象
        if (typeof errorMessage === "object" && errorMessage !== null) {
          // 提取错误信息并格式化为字符串
          const formattedErrors = Object.entries(errorMessage)
            .map(([field, messages]) => {
              // 确保 messages 是数组
              if (Array.isArray(messages)) {
                return `${field}: ${messages.join(", ")}`;
              }
              return `${field}: Unknown error format`;
            })
            .join("; ");

          ElMessage.error(formattedErrors);
        } else {
          ElMessage.error("An error occurred");
        }
      } else {
        ElMessage.error("An unexpected error occurred");
      }
    } else {
      console.error("Unexpected error:", error);
      ElMessage.error("An unexpected error occurred");
    }
  }
};

// Automatically trigger query on component mount
onMounted(() => {
  handleQuery();
});
</script>

<style scoped>
.container {
  padding: 20px;
  background-color: #fff;
}

.query-form {
  display: flex;
  flex-wrap: wrap;
  gap: 16px; /* 间距 */
  align-items: center; /* 对齐表单项 */
}

.el-form-item {
  flex: 1;
  min-width: 200px; /* 确保表单项有一定宽度 */
}

.form-select,
.form-input {
  width: 100%;
}

.form-actions {
  display: flex;
  gap: 12px; /* 间距 */
  justify-content: flex-end; /* 将按钮对齐到右侧 */
}

.el-button {
  min-width: 100px; /* 确保按钮有足够宽度 */
}

.function-buttons {
  margin-bottom: 20px;
  display: flex;
  gap: 10px; /* 设置按钮之间的间距 */
  flex-wrap: wrap; /* 确保按钮在窄屏上换行 */
}

.table-container {
  background-color: #fff;
  padding: 20px;
  border-radius: 4px;
}

.el-table {
  border-radius: 4px;
  overflow: hidden;
}

.el-table th {
  background-color: #f5f5f5;
  font-weight: bold;
  text-align: center;
}

.el-table td {
  text-align: center;
}

.table-column {
  text-align: center;
}

.operation-buttons {
  display: flex;
  flex-direction: column;
  gap: 8px;
  justify-content: center;
  align-items: center;
}

.pagination-wrapper {
  margin-top: 20px;
  display: flex;
  justify-content: center;
}

.styled-dialog {
  max-height: 80vh;
  overflow: auto;
}

.two-column-form .el-form-item {
  margin-bottom: 20px;
}
</style>
