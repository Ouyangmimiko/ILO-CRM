<template>
  <div class="container">
    <!-- form -->
    <el-form :model="queryForm" inline class="query-form">
      <el-form-item label="Search Type">
        <el-select
          v-model="queryForm.search_type"
          placeholder="Select search type"
          class="form-select"
        >
          <el-option label="All" value="true" />
          <el-option label="Organisation" value="organisation" />
          <el-option label="Organisation Sector" value="organisation_sector" />
          <el-option label="First Name" value="first_name" />
          <el-option label="Surname" value="surname" />
          <el-option label="Job Title" value="job_title" />
          <el-option label="Email" value="email" />
          <el-option label="Location" value="location" />
          <el-option label="UoB Alumni" value="uob_alumni" />
          <el-option
            label="Programme of Study Engaged"
            value="programme_of_study_engaged"
          />
          <el-option
            v-for="(year, index) in dynamicYears"
            :key="index"
            :label="`Mentoring ${year.replace('_', '/')}`"
            :value="`mentoring_${year}`"
          />

          <el-option
            v-for="(year, index) in dynamicYears"
            :key="index"
            :label="`YII ${year.replace('_', '/')}`"
            :value="`yii_${year}`"
          />
          <el-option
            v-for="(year, index) in dynamicYears"
            :key="index"
            :label="`Projects ${year.replace('_', '/')}`"
            :value="`projects_${year}`"
          />
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
        v-for="(year, index) in dynamicYears"
        :key="index"
        :prop="`mentoring_${year}`"
        :label="`Mentoring ${year.replace('_', '/')}`"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        v-for="(year, index) in dynamicYears"
        :key="index + dynamicYears.length"
        :prop="`yii_${year}`"
        :label="`YII ${year.replace('_', '/')}`"
        width="150"
        class-name="table-column"
      />
      <el-table-column
        v-for="(year, index) in dynamicYears"
        :key="index + dynamicYears.length * 2"
        :prop="`projects_${year}`"
        :label="`Projects ${year.replace('_', '/')}`"
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
              <el-button size="small" @click="showDetail(scope.row)">
                Detail
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
                :active-value="'yes'"
                :inactive-value="'no'"
                :active-text="'Yes'"
                :inactive-text="'No'"
              ></el-switch>
            </el-form-item>
            <!-- 动态生成 Mentoring、YII 和 Projects 字段 -->
            <el-form-item
              v-for="(field, index) in dynamicFields"
              :key="index"
              :label="field.label"
            >
              <el-switch
                v-model="editForm[field.key]"
                :active-value="'yes'"
                :inactive-value="'no'"
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

    <!-- Detail 弹窗 -->
    <el-dialog
      v-model="detailDialogVisible"
      :title="`Details for ${selectedRow.organisation}`"
      :before-close="handleDialogClose"
      width="800px"
      class="styled-dialog"
    >
      <div class="dialog-content">
        <!-- Static Info -->
        <el-form
          :model="selectedRow"
          label-width="200px"
          class="two-column-form"
        >
          <el-row :gutter="20">
            <el-col :span="12">
              <el-form-item label="Organisation:">
                <span>{{ selectedRow?.organisation }}</span>
              </el-form-item>
              <el-form-item label="Organisation Sector:">
                <span>{{ selectedRow?.organisation_sector }}</span>
              </el-form-item>
              <el-form-item label="First Name:">
                <span>{{ selectedRow?.first_name }}</span>
              </el-form-item>
              <el-form-item label="Surname:">
                <span>{{ selectedRow?.surname }}</span>
              </el-form-item>
            </el-col>
            <el-col :span="12">
              <el-form-item label="Job Title:">
                <span>{{ selectedRow?.job_title }}</span>
              </el-form-item>
              <el-form-item label="Email:">
                <span>{{ selectedRow?.email }}</span>
              </el-form-item>
              <el-form-item label="Location:">
                <span>{{ selectedRow?.location }}</span>
              </el-form-item>
            </el-col>
          </el-row>
          <el-row>
            <el-col :span="24">
              <el-form-item label="Contact Info:" class="contact-info-item">
                <span>{{
                  selectedRow?.info_related_to_contacting_the_partner
                }}</span>
              </el-form-item>
            </el-col>
          </el-row>
        </el-form>

        <!-- Dynamic Info -->
        <div class="dynamic-table-container">
          <el-table :data="dynamicData" align="center">
            <el-table-column
              prop="year"
              label="Year"
              width="150"
              align="center"
            />
            <el-table-column
              prop="mentoring"
              label="Mentoring"
              width="150"
              align="center"
            />
            <el-table-column
              prop="yii"
              label="YII"
              width="150"
              align="center"
            />
            <el-table-column
              prop="projects"
              label="Projects"
              width="150"
              align="center"
            />
          </el-table>
        </div>

        <div style="text-align: right; margin-top: 20px">
          <el-button @click="handleDialogClose">Close</el-button>
        </div>
      </div>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from "vue";
import axios from "../../api/axios";
import { ElForm, ElMessage, FormRules } from "element-plus";
import { AxiosError } from "axios";

const queryForm = reactive({
  // search_all: true, // boolean for search_all
  search_type: "",
  search_term: "",
});

const dialogVisible = ref(false);
// 获取当前年份
const currentYear = new Date().getFullYear();
const nextYear = currentYear + 1;

const dynamicYears = computed(() => {
  return Array.from({ length: 4 }, (_, i) => {
    const startYear = currentYear - i;
    const endYear = startYear + 1;
    return `${startYear}_${endYear.toString().slice(-2)}`;
  });
});

// 动态生成字段名称
const mentoringField = `mentoring_${currentYear}_${nextYear}`;
const yiiField = `yii_${currentYear}_${nextYear}`;
const projectsField = `projects_${currentYear}_${nextYear}`;

const editForm: Record<string, any> = reactive({
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
  [mentoringField]: "",
  [yiiField]: "",
  [projectsField]: "",
  info_related_to_contacting_the_partner: "",
});

const yearLabel = `${currentYear}-${nextYear.toString().slice(-2)}`;

const dynamicFields = computed(() => [
  {
    label: `Mentoring ${yearLabel}`,
    key: `mentoring_${currentYear}_${nextYear}`,
  },
  {
    label: `YII ${yearLabel}`,
    key: `yii_${currentYear}_${nextYear}`,
  },
  {
    label: `Projects ${yearLabel}`,
    key: `projects_${currentYear}_${nextYear}`,
  },
]);

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
    const { search_type, search_term } = queryForm;

    // Construct query parameters
    const params: any = {};

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
  queryForm.search_type = "";
  queryForm.search_term = "";
  handleQuery(); // Trigger query after resetting
};

const handleAdd = () => {
  // 清空表单并设置为新增模式
  Object.keys(editForm).forEach((key) => {
    editForm[key as keyof typeof editForm] = "";
  });
  editForm.id = ""; // 清空ID，表示新增
  dialogVisible.value = true;
};

const handleEdit = (row: any) => {
  Object.assign(editForm, row);
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

      if (response.status === 200 || response.status === 201) {
        ElMessage.success(response.data.message);
        handleClose();
        handleQuery();
      } else {
        ElMessage.error(response.data.errors);
      }
    } else {
      // 如果没有ID，则是新增模式，执行新增操作
      const response = await axios.post("/api/create", editForm);

      if (response.status === 201) {
        ElMessage.success(response.data.message);
        handleClose();
        handleQuery();
      } else {
        ElMessage.error(response.data.errors);
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

const detailDialogVisible = ref(false);
const selectedRow: Record<string, any> = ref({
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
  [mentoringField]: "",
  [yiiField]: "",
  [projectsField]: "",
  info_related_to_contacting_the_partner: "",
}); // 选中的行数据

const dynamicData = computed(() => {
  if (!selectedRow.value) return [];
  return dynamicYears.value.map((year) => ({
    year: year.replace("_", "/"),
    mentoring: selectedRow.value[`mentoring_${year}`],
    yii: selectedRow.value[`yii_${year}`],
    projects: selectedRow.value[`projects_${year}`],
  }));
});

const showDetail = (row: any) => {
  // Object.assign(selectedRow, row);
  selectedRow.value = row;
  detailDialogVisible.value = true;
};

// 处理关闭对话框
const handleDialogClose = () => {
  detailDialogVisible.value = false;
};
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
  display: flex;
  justify-content: center;
  align-items: center;
}

.dialog-content {
  text-align: center;
}

.dynamic-table-container {
  margin: 0 auto;
  width: fit-content;
}

.el-table {
  /* margin: 0 auto; */
  text-align: center;
}

.two-column-form .el-form-item {
  margin-bottom: 20px;
}

.contact-info-item {
  text-align: left;
}
</style>
