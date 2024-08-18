<template>
  <div class="container">
    <!-- Year Selector-->
    <div class="YearSelector">
      <el-select
          v-model="selectedStartYear"
          placeholder="Start Year"
          :style="{ width: '5em' }"
          @change="handleStartYear"
      >
        <el-option
            v-for="year in StartYearOptions"
            :key="year"
            :label="year"
            :value="year"
        />
       </el-select>
      <span > to </span>
      <el-select
          v-model="selectedEndYear"
          placeholder="End Year"
          :style="{ width: '5em' }"
          @change="handleEndYear"
      >
        <el-option
            v-for="year in EndYearOptions"
            :key="year"
            :label="year"
            :value="year"
        />
      </el-select>

    </div>
    <!-- Function Button -->
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
    <div class="main_table">
      <el-table
          :data="masterRecords"
          stripe
          border
          style="width: 100%"
          max-height="500"
          table-layout="auto"
          size="small"
      >
        <!-- show details by expand -->
        <el-table-column type="expand">
          <template v-slot="props">
            <el-form
                label-position="left"
                inline
                class="details_expend"
            >
              <el-form-item
                  v-for="(header, index) in baseHeaders"
                  :key="index"
                  class="expend_form_item"
              >
                <label class="expend_form_label">{{header.label}}: </label>
                <span>{{ props.row[header.prop] }}</span>
              </el-form-item>
              <el-form-item class="expend_form_item">
                <label class="expend_form_label">{{contactInfoHeader.label}}: </label>
                <span>{{ props.row[contactInfoHeader.prop] }}</span>
              </el-form-item>
              <!-- show other engagement -->
              <el-form-item
                  v-if="ifShowOther"
                  v-for="(header, index) in otherHeaders"
                  :key="index"
                  class="expend_form_item"
              >
                <label class="expend_form_label">{{header.label}}: </label>
                <span>{{ props.row[header.prop] }}</span>
              </el-form-item>
              <el-form-item class="expend_form_item">
                <label class="expend_form_label">More information </label>
                <el-checkbox v-model="ifShowOther"> Other Engagement Details </el-checkbox>
                <el-checkbox v-model="ifShowDynamic"> Show Status by selected Years</el-checkbox>
              </el-form-item>
              <!-- show status table -->
              <el-table
                  :data="[props.row]"
                  v-if="ifShowDynamic"
                  size="small"
                  class="expend_table"
              >
                <el-table-column
                  v-for="header in dynamicHeaders"
                  :key="header.prop"
                  :prop="header.prop"
                  :label="header.label"
                  ></el-table-column>
              </el-table>
            </el-form>
          </template>
        </el-table-column>
        <el-table-column
            v-for="header in tableHeaders"
            :key="header.prop"
            :prop="header.prop"
            :label="header.label"
        ></el-table-column>
        <el-table-column
            v-if="isAdmin"
            label="Operation"
            fixed="right"
            width="170"
        >
          <template v-slot="props" style="display: flex; align-items: center; justify-content: flex-start; height: 100%;">
            <el-button @click="handleEdit(props.row)" type="primary" style="width: 60px; margin-right: 10px;">Edit</el-button>
            <el-button @click="handleDelete(props.row.id)" type="danger" style="width: 60px; margin-right: 10px;">Delete</el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from "vue";
import axios from "../../api/axios";
import { ElForm, ElMessage, FormRules } from "element-plus";
import { AxiosError } from "axios";

const isAdmin = localStorage.getItem("User_role") === "admin" || localStorage.getItem("User_role") === "master";
let ifShowOther = ref(false);
let ifShowDynamic = ref(false);

const masterRecords = ref<any[]>([]);
const yearRange = ref<any[]>([]);
const tableHeaders = ref<any[]>([]);

// Year select
const selectedStartYear = ref();
const selectedEndYear = ref();
const currentDate = new Date();
const currentYear = new Date().getFullYear();
const academicYearStartDate = new Date(currentYear,8, 1);
const betweenYears = ref(3);
const lastYearRange = ref(20);
const setDefaultDate = () => {
  if (localStorage.getItem('betweenRange')) {
    betweenYears.value = Number(localStorage.getItem('betweenRange'));
  }
  if (localStorage.getItem('lastYearRange')) {
    lastYearRange.value = Number(localStorage.getItem('lastYearRange'));
  }
  if (currentDate > academicYearStartDate) {
    selectedStartYear.value = currentYear;
  } else {
    selectedStartYear.value = currentYear - 1;
  }
  selectedEndYear.value = selectedStartYear.value + betweenYears.value;
}

const generateYears = (startYear: number, endYear: number) => {
  const years = [];
  for (let year = startYear; year >= endYear; year--) {
    years.push(year);
  }
  return years;
};

const StartYearOptions = computed(() => generateYears(selectedEndYear.value - 1, 2000));
const EndYearOptions = computed(() => generateYears( selectedStartYear.value + lastYearRange.value, selectedStartYear.value + 1))
const handleStartYear = () => {
  if (selectedStartYear.value >= selectedEndYear.value) {
    selectedEndYear.value = selectedStartYear.value + 1;
  }
  getMasterRecords();
}

const handleEndYear = () => {
  getMasterRecords();
}
onMounted(() => {
  setDefaultDate();
  getMasterRecords();
});
// End of year selector part

// Records formation
const formatRecords = (records: any, yearRange : any[]) => {
  return records.map((record: any) => {
    let formattedRecord = {
      id: record.id,
      organisation: record.organisation,
      organisation_sector: record.organisation_sector,
      first_name: record.first_name,
      surname: record.surname,
      job_title: record.job_title,
      email: record.email,
      location: record.location,
      uob_alumni: record.uob_alumni,
      programme_of_study_engaged: record.programme_of_study_engaged,
      ...Object.fromEntries(yearRange.map(year => [
        `mentoring_${year}`, null,
        `industry_${year}`, null,
        `project_${year}`, null
      ]))
    };
    // fill mentoring_periods
    record.mentoring_periods.forEach((period: any) => {
      formattedRecord[`mentoring_${period.academic_year}`] = period.mentoring_status;
    });

    // fill industry_years
    record.industry_years.forEach((industry: any) => {
      formattedRecord[`industry_${industry.academic_year}`] = industry.had_placement_status;
    });

    // fill project_years
    record.project_years.forEach((project: any) => {
      formattedRecord[`project_${project.academic_year}`] = project.project_client;
    });

    // format other data
    if (record.other_engagement) {
      formattedRecord['society_engaged_or_to_engage'] = record.other_engagement.society_engaged_or_to_engage;
      formattedRecord['engagement_type'] = record.other_engagement.engagement_type;
      formattedRecord['engagement_happened'] = record.other_engagement.engagement_happened;
      formattedRecord['engagement_notes'] = record.other_engagement.notes;
    }
    // format contacting info
    if (record.contact_infos) {
      formattedRecord['contacting_info'] = record.contact_infos.contacting_info;
    }

    return formattedRecord;
  });
}

// Handle header generation
const baseHeaders = [
  {label: "Organisation", prop: "organisation"},
  {label: "Organisation Sector", prop: "organisation_sector"},
  {label: "First Name", prop: "first_name"},
  {label: "Surname", prop: "surname"},
  {label: "Job Title", prop: "job_title"},
  {label: "Email Address", prop: "email"},
  {label: "Location", prop: "location"},
  {label: "UoB Alumni", prop: "uob_alumni"},
  {label: "Programme of Study Engaged", prop: "programme_of_study_engaged"},

];
const contactInfoHeader = {label: "Info related to contacting the partner", prop: "contacting_info"};
const otherHeaders = [
  {label: "Engagement Happened", prop: "engagement_happened"},
  {label: "Engagement Type", prop: "engagement_type"},
  {label: "Society Engaged/to Engage", prop: "engagement_happened"},
  {label: "Notes", prop: "notes"},
];
const dynamicHeaders = ref<any[]>([]);
const getTableHeaders = (yearRange: any[]) => {
  let dHeaders = [] as any[];
  dHeaders = dHeaders.concat(
      yearRange.flatMap(year => [
        {
          label: `Mentoring ${year}`,
          prop: `mentoring_${year}`,
          ac_year: `${year}`
        }
      ])
  );
  dHeaders = dHeaders.concat(
      yearRange.flatMap(year => [
        {
          label: `Year in Industry ${year}`,
          prop: `industry_${year}`,
          ac_year: `${year}`
        }
      ])
  );
  dHeaders = dHeaders.concat(
      yearRange.flatMap(year => [
        {
          label: `Project Client ${year}`,
          prop: `project_${year}`,
          ac_year: `${year}`
        }
      ])
  );
  dynamicHeaders.value = dHeaders;
  console.log(dynamicHeaders);
  return [...baseHeaders, ...dHeaders];
}

const getMasterRecords = async () => {
  try {
    const response = await axios.get('/api//records_by_year_range', {
      params: {
        academic_year_start: selectedStartYear.value,
        academic_year_end: selectedEndYear.value,
      },
    });
    const records = response.data.data;
    yearRange.value = response.data.year_range;
    masterRecords.value = formatRecords(records, yearRange.value);
    tableHeaders.value = getTableHeaders(yearRange.value);
    console.log(records);
    console.log(masterRecords.value);
    console.log(tableHeaders.value);
  } catch (error) {
    ElMessage.error("Error in getMasterRecords");
  }
}


const queryForm = reactive({
  // search_all: true, // boolean for search_all
  search_type: "",
  search_term: "",
});

const dialogVisible = ref(false);

const dynamicYears = computed(() => {
  return Array.from({ length: 4 }, (_, i) => {
    const startYear = currentYear - i;
    const endYear = startYear + 1;
    return `${startYear}_${endYear.toString().slice(-2)}`;
  });
});

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
    await axios.delete(`/api/records/${id}`);
    ElMessage.success("Entry deleted successfully");
    await getMasterRecords();
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

.function-buttons {
  margin-bottom: 10px;
  display: flex;
  gap: 10px; /* 设置按钮之间的间距 */
  flex-wrap: wrap; /* 确保按钮在窄屏上换行 */
}

.main_table {
  background-color: #fff;
  padding: 20px;
  border-radius: 4px;
}

.details_expend {
  margin-left: 20px;
  display: flex;
  flex-wrap: wrap;
  gap: 0;
  width: 50vw;
}

.details_expend .expend_form_item {
  flex: 1 1 35%;
}
.details_expend .expend_form_label{
  margin-right: 20px;
  color: #99a9bf;
}

.expend_table {
  flex-wrap: wrap;
  width: 100%;
}

.el-table th {
  background-color: #f5f5f5;
  font-weight: bold;
  text-align: center;
}

.el-table td {
  text-align: center;
}
</style>
