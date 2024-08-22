<template>
  <div class="container">
    <!-- Search-->
    <div class="search">
      <el-select
      v-model="searchMode"
      placeholder="Search Mode"
      size="default"
      style="width: 6em; margin-right: 10px"
      >
        <el-option
          v-for="item in searchOptions"
          :key="item.value"
          :label="item.label"
          :value="item.value"/>
      </el-select>
      <el-input
          v-if="searchMode === 'all'"
          v-model="searchTerm"
          title="Term"
          placeholder="Enter search term"
          clearable
          style="display: flex; width: 30%"
      ></el-input>
      <el-button
          type="primary"
          @click="handleSearch"
          size="default"
          style="margin-left: 10px"
      >Search</el-button>
    </div>
    <!-- Year Selector-->
    <div class="YearSelector">
      <el-select
          v-model="selectedStartYear"
          placeholder="Start Year"
          :style="{ width: '5em' }"
          @change="handleStartYear"
          style="margin-right: 10px"
      >
        <el-option
            v-for="year in StartYearOptions"
            :key="year"
            :label="year"
            :value="year"
        />
       </el-select>
      <span > To </span>
      <el-select
          v-model="selectedEndYear"
          placeholder="End Year"
          :style="{ width: '5em' }"
          @change="handleEndYear"
          style="margin-left: 10px"
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
      <el-button type="primary" @click="handleAdd" v-if="isAdmin">Add</el-button>
<!--      <el-upload-->
<!--        :action="uploadUrl"-->
<!--        :before-upload="handleBeforeUpload"-->
<!--        :auto-upload="true"-->
<!--        :file-list="fileList"-->
<!--        :on-remove="handleRemove"-->
<!--        v-if="isAdmin"-->
<!--      >-->
<!--        <el-button>Upload Excel <el-icon style="margin-left: 1em"><UploadFilled /></el-icon></el-button>-->
<!--      </el-upload>-->

<!--      <el-button @click="exportExcel">Export <el-icon style="margin-left: 1em"><Download /></el-icon></el-button>-->
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
            <el-container style="border: 1px solid #888888; width: 70vw; display: flex; flex-wrap: wrap; margin-left: 10px;">
              <el-descriptions
                  :column="2"
                  label-position="left"
                  size="small"
                  border
                  style="margin-left: 10px; width: 100%; max-width: 100%"
              >
                <el-descriptions-item
                    v-for="(header, index) in baseHeaders"
                    :key="index"
                    :label="header.label"
                >
                  {{ props.row[header.prop] }}
                </el-descriptions-item>
                <el-descriptions-item
                    class="expend_form_item"
                    :label="contactInfoHeader.label"
                >
                  {{ props.row[contactInfoHeader.prop] }}
                </el-descriptions-item>

                <!-- show other engagement -->
                <el-descriptions-item
                    v-if="ifShowOther"
                    v-for="(header, index) in otherHeaders"
                    :key="index"
                    :label="header.label"
                >
                  {{ props.row[header.prop] }}
                </el-descriptions-item>
              </el-descriptions>
              <div style="margin-left: 20px">
                <el-checkbox v-model="ifShowOther"> Other Engagement Details </el-checkbox>
                <el-checkbox v-model="ifShowDynamic"> Show Status by selected Years </el-checkbox>
              </div>
              <!-- show status table -->
              <el-table
                  :data="[props.row]"
                  size="small"
                  class="expend_table"
                  style="max-width: 100%; overflow-x: auto;"
                  v-if="ifShowDynamic"
              >
                <el-table-column
                    v-for="header in dynamicHeaders"
                    :key="header.prop"
                    :prop="header.prop"
                    :label="header.label"
                />
              </el-table>
            </el-container>

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
            <el-button
                @click="handleEdit(props.row)" link style="width: 60px; margin-right: 10px; color: #337ecc"
            >
              Edit
              <el-icon style="margin-left: 2px"><Edit /></el-icon>
            </el-button>
            <el-button
                @click="handleDelete(props.row.id)"
                link
                style="width: 60px; margin-right: 10px; color: #99a9bf;"
            >
              Delete
              <el-icon style="margin-left: 2px"><Delete /></el-icon>
            </el-button>
          </template>
        </el-table-column>
      </el-table>
    </div>
    <div>
      <el-dialog
          v-model="isDialogVisible"
          :before-close="handleClose"
          class="function-dialog"
          width="50%"
      >
        <div v-if="functionMode === 'add'">
          <h3>Add a new record</h3>
        </div>
        <div v-if="functionMode === 'edit'">
          <h3>Edit</h3>
        </div>
        <div v-if="functionMode === 'search' && searchMode === 'specific'">
          <h3>Specific Search</h3>
        </div>
        <div v-if="functionMode === 'delete'">
          <h3>Delete this record ?</h3>
        </div>
        <el-form
            ref="formRef"
            :model="formRecordToUse"
            :rules="formRules"
            v-if="ifShowForm"
            scroll-to-error
        >
          <div style="display: flex; justify-content: space-between;  margin-bottom: 20px; flex-wrap: wrap; margin-top: 20px; width: 100%">
            <el-form-item v-for="header in baseHeaders" :key="header.prop" :label="header.label" :prop="header.prop"
                          style="flex: 1 1 40%;" label-position="top"
            >
              <el-input v-model="formRecordToUse[header.prop]" style="width: 300px"></el-input>
            </el-form-item>
            <el-form-item  :key="contactInfoHeader.label" :label="contactInfoHeader.label" :prop="contactInfoHeader.prop"
                           style="flex: 1 1 100%;" label-position="top"
            >
              <el-input v-model="formRecordToUse[contactInfoHeader.prop]" style="width: 80%"></el-input>
            </el-form-item>
            <el-container style="flex: 1 1 100%; display: flex; flex-wrap: wrap; border: 1px solid #888888; margin-bottom: 10px;">
              <h3 style="flex: 1 1 100%; margin: 5px 0 5px 5px">Mentoring status <el-checkbox v-model="checkMentoring"></el-checkbox></h3>
              <el-form-item v-for="header in mentoringHeaders" :key="header.prop" :label="header.label" :prop="header.prop"
                            style="flex: 1 1 40%; margin: 5px 0 10px 5px" label-position="top" v-show="checkMentoring"
              >
                <el-checkbox v-model="activatedFields[header.prop]" style="margin-right: 10%" @change="handleItemCheck"></el-checkbox>
                <el-switch
                    v-model="formRecordToUse[header.prop]"
                    :disabled="!activatedFields[header.prop]"
                    active-value = 'yes'
                    inactive-value = 'no'
                    active-text="yes"
                    inactive-text="no"
                ></el-switch>
              </el-form-item>
            </el-container>
            <el-container style="flex: 1 1 100%; display: flex; flex-wrap: wrap; border: 1px solid #888888; margin-bottom: 10px;">
                <h3 style="flex: 1 1 100%; margin: 5px 0 5px 5px">Year in Industry status <el-checkbox v-model="checkIndustry"></el-checkbox></h3>
                <el-form-item v-for="header in yearInIndustryHeaders" :key="header.prop" :label="header.label" :prop="header.prop"
                              style="flex: 1 1 40%; margin: 5px 0 10px 5px" label-position="top" v-show="checkIndustry"
                >
                  <el-checkbox v-model="activatedFields[header.prop]" style="margin-right: 10%" @change="handleItemCheck"></el-checkbox>
                  <el-switch
                      v-model="formRecordToUse[header.prop]"
                      :disabled="!activatedFields[header.prop]"
                      active-value = 'yes'
                      inactive-value = 'no'
                      active-text="yes"
                      inactive-text="no"
                  ></el-switch>
                </el-form-item>
            </el-container>
            <el-container style="flex: 1 1 100%; display: flex; flex-wrap: wrap; border: 1px solid #888888; margin-bottom: 10px;">
              <h3 style="flex: 1 1 100%; margin: 5px 0 5px 5px">Project Client status <el-checkbox v-model="checkProject"></el-checkbox></h3>
              <el-form-item v-for="header in projectClientHeaders" :key="header.prop" :label="header.label" :prop="header.prop"
                            style="flex: 1 1 40%; margin: 5px 0 10px 5px" label-position="top" v-show="checkProject"
              >
                <el-checkbox v-model="activatedFields[header.prop]" style="margin-right: 5px" @change="handleItemCheck"></el-checkbox>
                <el-input v-model="formRecordToUse[header.prop]" style="width: 80%" :disabled="!activatedFields[header.prop]"></el-input>
              </el-form-item>
            </el-container>
            <el-container style="flex: 1 1 100%; display: flex; flex-wrap: wrap; border: 1px solid #888888; margin-bottom: 10px;">
              <h3 style="flex: 1 1 100%; margin: 5px 0 5px 5px">Other Engagement <el-checkbox v-model="checkOther"></el-checkbox></h3>
              <el-form-item v-for="header in otherHeaders" :key="header.prop" :label="header.label" :prop="header.prop"
                            style="flex: 1 1 40%; margin: 5px 0 10px 5px" label-position="top" v-show="checkOther"
              >
                <el-input v-model="formRecordToUse[header.prop]" style="width: 80%"></el-input>
              </el-form-item>
            </el-container>
          </div>
        </el-form>
        <template #footer>
          <el-button v-if="functionMode !== 'delete'" @click="handleReset" link>Reset</el-button>
          <el-button @click="handleClose">Cancel</el-button>
          <el-button type="primary" @click="handleConfirm">{{ confirmButtonText }}</el-button>
        </template>
      </el-dialog>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, onMounted, computed } from "vue";
import axios from "../../api/axios";
import { ElForm, ElMessage, FormRules } from "element-plus";
import {AxiosError} from "axios";
// import { AxiosError } from "axios";

// if show edit and delete in table
const isAdmin = localStorage.getItem("User_role") === "admin" || localStorage.getItem("User_role") === "master";
// show more info in expend detail
let ifShowOther = ref(false);
let ifShowDynamic = ref(false);

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
      formattedRecord['notes'] = record.other_engagement.notes;
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
  {label: "Society Engaged/to Engage", prop: "society_engaged_or_to_engage"},
  {label: "Notes", prop: "notes"},
];
const dynamicHeaders = ref<any[]>([]);
const mentoringHeaders = ref<any[]>([]);
const yearInIndustryHeaders = ref<any[]>([]);
const projectClientHeaders = ref<any[]>([]);
const getTableHeaders = (yearRange: any[]) => {
  let dHeaders: any[];
  mentoringHeaders.value = [];
  yearInIndustryHeaders.value = [];
  projectClientHeaders.value = [];
  mentoringHeaders.value = mentoringHeaders.value.concat(
      yearRange.flatMap(year => [
        {
          label: `Mentoring ${year}`,
          prop: `mentoring_${year}`,
          ac_year: `${year}`
        }
      ])
  );
  yearInIndustryHeaders.value = yearInIndustryHeaders.value.concat(
      yearRange.flatMap(year => [
        {
          label: `Year in Industry ${year}`,
          prop: `industry_${year}`,
          ac_year: `${year}`
        }
      ])
  );
  projectClientHeaders.value = projectClientHeaders.value.concat(
      yearRange.flatMap(year => [
        {
          label: `Project Client ${year}`,
          prop: `project_${year}`,
          ac_year: `${year}`
        }
      ])
  );
  dHeaders = [...mentoringHeaders.value, ...yearInIndustryHeaders.value, ...projectClientHeaders.value ];
  dynamicHeaders.value = dHeaders;
  console.log(dynamicHeaders);
  return [...baseHeaders, ...dHeaders];
}

// GET records
const masterRecords = ref<any[]>([]);
const yearRange = ref<any[]>([]);
const tableHeaders = ref<any[]>([]);
const getMasterRecords = async () => {
  try {
    const response = await axios.get('/api/records_by_year_range', {
      params: {
        academic_year_start: selectedStartYear.value,
        academic_year_end: selectedEndYear.value,
      },
    });
    const records = response.data.data;
    yearRange.value = response.data.year_range;
    masterRecords.value = formatRecords(records, yearRange.value);
    tableHeaders.value = getTableHeaders(yearRange.value);
    // Generate complete form headers
    formHeaders.value = [...tableHeaders.value, ...otherHeaders, contactInfoHeader];
    console.log(records);
    console.log(masterRecords.value);
    console.log(tableHeaders.value);
  } catch (error) {
    ElMessage.error("Error in getMasterRecords");
  }
}

// Form
const ifShowForm = ref(false);
const formRecordToUse = reactive<Record<string, any>>({});
const originalForm = reactive<Record<string, any>>({});
const formHeaders = ref<Record<string, any>[]>([]);
const formRef = ref<InstanceType<typeof ElForm> | null>(null);
const formRules = reactive<FormRules>({});
const checkMentoring = ref(false);
const checkIndustry = ref(false);
const checkProject = ref(false);
const checkOther = ref(false);

const baseRules = {
  organisation: [{required: true, message: 'Please input organisation', trigger: 'blur'}],
  first_name: [{required: true, message: 'Please input first name', trigger: 'blur'}],
  surname: [{required: true, message: 'Please input surname', trigger: 'blur'}],
  email: [{required: true, message: 'Please input valid email', trigger: 'blur', type: 'email'}],
};

// set fields having value as activated
const activatedFields = ref<Record<string, boolean>>({});
const initialiseActivatedFields = (record = {}) => {
  formHeaders.value.forEach(header => {
    // Activated while this field has value, fields without value are unactivated
    activatedFields.value[header.prop] = !!record[header.prop as keyof typeof record];
  })
};

// Generate form model
const initialiseFormModel = (record = {}) => {
  formHeaders.value.forEach((header) => {
    // initial form data, store exist data in form record
    formRecordToUse[header.prop as keyof typeof record] = record[header.prop as keyof typeof record] || '';
  });
  Object.assign(originalForm, formRecordToUse);
}

const setRules = () => {
  Object.keys(formRules).forEach(key => delete formRules[key]);
  Object.assign(formRules, baseRules);
  dynamicHeaders.value.forEach((header) => {
    if(activatedFields.value[header.prop]) {
      formRules[header.prop] = [{required: true, message: 'Please input activated field', trigger: 'blur'}];
    }
  })
  console.log(formRules);
};

const handleItemCheck = () => {
  if (!(functionMode.value === 'search')) {
    setRules();
  }
}

const handleReset = () => {
  if (!formRef.value) return;
  formRef.value.resetFields();
  Object.assign(formRecordToUse, originalForm);
  if (functionMode.value === 'edit') {
    initialiseActivatedFields(originalForm);
  } else {
    initialiseActivatedFields();
  }
  handleItemCheck();
  console.log(formRecordToUse);
}

// Search
const searchMode = ref<string>('all');
const searchTerm = ref<string>(''); 
const searchOptions = [
  {label:'All', value:'all'},
  {label:'Specific', value:'specific'},
];
const handleSearch = () => {
  if (searchMode.value === 'specific') {
    initialiseActivatedFields();
    initialiseFormModel();
    isDialogVisible.value = true;
    functionMode.value = 'search';
    confirmButtonText.value = 'Search';
    ifShowForm.value = true;
  }
  if (searchTerm.value === '') {
    getMasterRecords();
  } else {
    searchRecords();
  }
};
const searchRecords = async () => {
  if (searchMode.value === 'all') {
    try {
      const response = await axios.get('/api/records/search/all', {
        params: {
          'year_range': yearRange.value,
          'search_term': searchTerm.value,
        }
      });
      const searchedRecords = response.data.data;
      masterRecords.value = formatRecords(searchedRecords, yearRange.value);
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
        ElMessage.error('An unexpected error occurred while search.');
      }
    }
  } else if (searchMode.value === 'specific') {
    formatFormToRecord(formRecordToUse);
    try {
      const response = await axios.get(`/api/records/search/specific`, {
        params: {
          ...reformatedRecord.value,
          'year_range': yearRange.value,
        }
      });
      const searchedRecords = response.data.data;
      masterRecords.value = formatRecords(searchedRecords, yearRange.value);
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
        ElMessage.error('An unexpected error occurred while search.');
      }
    }
    handleClose();
  }
};

// Dialog
const functionMode = ref<string>(''); //'add', 'edit', or 'search'
const idToOperate = ref<string>('');
const isDialogVisible = ref<boolean>(false);
const confirmButtonText = ref<string>('');

const handleClose = () => {
  isDialogVisible.value = false;
  ifShowForm.value = false;
  idToOperate.value = '';
  functionMode.value = '';
  initialiseActivatedFields();
  initialiseFormModel();
  Object.keys(formRules).forEach(key => {
    delete formRules[key];
  });
  confirmButtonText.value = 'no mode';
}

const handleAdd = () => {
  ifShowForm.value = true;
  functionMode.value = 'add';
  confirmButtonText.value = 'Confirm Add'
  initialiseActivatedFields();
  initialiseFormModel();
  setRules();
  isDialogVisible.value = true;
}

const handleEdit = (row: any) => {
  ifShowForm.value = true;
  functionMode.value = 'edit';
  idToOperate.value = row.id;
  confirmButtonText.value = 'Confirm Edit';
  initialiseActivatedFields(row);
  initialiseFormModel(row);
  setRules();
  console.log(formRecordToUse);
  isDialogVisible.value = true;
};

const handleDelete = (id: any) => {
  isDialogVisible.value = true;
  functionMode.value = 'delete';
  idToOperate.value = id;
  confirmButtonText.value = 'Confirm Delete';
}

const handleConfirm = () => {
  if (searchMode.value === 'all' && functionMode.value === 'search') {
    searchRecords();
    return;
  } else if (functionMode.value === 'delete') {
    deleteRecord(idToOperate.value);
  }
  if (formRef.value) {
    formRef.value.validate(valid => {
      if (valid) {
        switch (functionMode.value) {
          case 'edit':
            updateRecord(idToOperate.value);
            break;
          case 'add':
            storeRecord();
            break;
          case 'search':
            searchRecords();
            break;
        }
      } else {
        ElMessage.error("Failed to submit.")
      }
    });
  }
}

const reformatedRecord = ref<any>({});
const yearRangeReg = /(\d{4}-\d{4})$/;

const extractYearRange = (field: string) => {
  const match = field.match(yearRangeReg);
  let yearRange = '';
  if (match) {
    yearRange = match[0];
  }
  return yearRange;
}

const formatFormToRecord = (record: any = {}) => {
  reformatedRecord.value = {};
  const isSpecificSearch = searchMode.value === 'specific' && functionMode.value === 'search';
  baseHeaders.forEach(header => {
    if (!isSpecificSearch || record[header.prop] !== '') {
      reformatedRecord.value[header.prop] = record[header.prop];
    }
  });
  reformatedRecord.value['other_engagement'] = {};
  otherHeaders.forEach(header => {
    if (!isSpecificSearch || record[header.prop] !== '') {
      reformatedRecord.value['other_engagement'][header.prop] = record[header.prop];
    }
  });
  reformatedRecord.value['contact_infos'] = {};
  if (record[contactInfoHeader.prop] !== null && record[contactInfoHeader.prop].length > 0) {
    reformatedRecord.value['contact_infos'][contactInfoHeader.prop] = record[contactInfoHeader.prop];
  }
  reformatedRecord.value['mentoring_periods'] = [];
  reformatedRecord.value['industry_years'] = [];
  reformatedRecord.value['project_years'] = [];
  mentoringHeaders.value.forEach(header => {
    if (activatedFields.value[header.prop]) {
      let academicYear = extractYearRange(header.prop);
      if (academicYear !== '') {
        reformatedRecord.value['mentoring_periods'].push({
          'academic_year': academicYear,
          'mentoring_status': record[header.prop],
        });
      }
    }
  });
  yearInIndustryHeaders.value.forEach(header => {
    if (activatedFields.value[header.prop]) {
      let academicYear = extractYearRange(header.prop);
      if (academicYear !== '') {
        reformatedRecord.value['industry_years'].push({
          'academic_year': academicYear,
          'had_placement_status': record[header.prop],
        });
      }
    }
  });
  projectClientHeaders.value.forEach(header => {
    if (activatedFields.value[header.prop]) {
      let academicYear = extractYearRange(header.prop);
      if (academicYear !== '') {
        reformatedRecord.value['project_years'].push({
          'academic_year': academicYear,
          'project_client': record[header.prop],
        });
      }
    }
  })
  console.log(reformatedRecord.value);
}

const storeRecord = async () => {
  formatFormToRecord(formRecordToUse);
  try {
    const response = await axios.post('/api/records/add', reformatedRecord.value);
    ElMessage.success(response.data.message);
    await getMasterRecords();
    console.log(response.data.data);
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
      ElMessage.error('An unexpected error occurred while add.');
    }
  }
  handleClose();
}

const updateRecord = async (id: any) => {
  formatFormToRecord(formRecordToUse);
  try {
    const response = await axios.put(`/api/records/${id}`, reformatedRecord.value);
    ElMessage.success(response.data.message);
    await getMasterRecords();
    console.log(response.data.data);
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
  handleClose();
}

const deleteRecord = async (id: any) => {
  try {
    await axios.delete(`/api/records/${id}`);
    ElMessage.success("Entry deleted successfully");
    await getMasterRecords();
    handleClose();
  } catch (error) {
    ElMessage.error("Error deleting entry");
  }
};

// Handle file upload
// const handleBeforeUpload = (file: string | Blob) => {
//   // Create FormData object
//   const formData = new FormData();
//   formData.append("file", file);
//
//   // Upload the file
//   axios
//     .post(uploadUrl, formData, {
//       headers: {
//         "Content-Type": "multipart/form-data",
//       },
//     })
//     .then(() => {
//       ElMessage.success("File uploaded successfully");
//       handleQuery(); // Refresh data after successful upload
//     })
//     .catch(() => {
//       ElMessage.error("Error uploading file");
//     });
//
//   // Prevent the default upload behavior
//   return false;
// };

// Handle file remove
// const handleRemove = () => {
//   // Handle file removal logic if necessary
// };
</script>

<style scoped>
.container {
  display: flex;
  flex-wrap: wrap;
  justify-content: space-between;
  align-items: center;
  padding: 20px;
  background-color: #fff;
}

.search{
  display: flex;
  align-items: center;
  width: 50%;
}

.YearSelector{
  display: flex;
  align-items: center;
}

.function-buttons {
  margin-top: 20px;
  margin-bottom: 10px;
  display: flex;
  flex-basis: 100%;
  gap: 10px; /* 设置按钮之间的间距 */
  flex-wrap: wrap; /* 确保按钮在窄屏上换行 */
}

.main_table {
  flex-basis: 100%;
  background-color: #fff;
  padding: 0;
  border-radius: 4px;
  width: 100%;
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
