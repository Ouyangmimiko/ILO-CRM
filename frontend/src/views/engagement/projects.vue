<template>
  <div class="container">
    <!-- form -->
    <el-form :model="queryForm" inline class="query-form styled-form">
      <el-form-item label="ORGANISATION">
        <el-input
          v-model="queryForm.ORGANISATION"
          placeholder="Please enter name"
        />
      </el-form-item>
      <el-form-item label="ORGANISATION SECTOR">
        <el-input
          v-model="queryForm['ORGANISATION SECTOR']"
          placeholder="Please enter address"
        />
      </el-form-item>
      <el-form-item label="FIRST NAME">
        <el-input
          v-model="queryForm['FIRST NAME']"
          placeholder="Please enter name"
        />
      </el-form-item>
      <el-form-item label="SURNAME">
        <el-input
          v-model="queryForm['SURNAME']"
          placeholder="Please enter name"
        />
      </el-form-item>
      <el-form-item label="JOB TITLE">
        <el-input
          v-model="queryForm['JOB TITLE']"
          placeholder="Please enter name"
        />
      </el-form-item>

      <el-form-item label="EMAIL ADDRESS">
        <el-input
          v-model="queryForm['EMAIL ADDRESS']"
          placeholder="Please enter name"
        />
      </el-form-item>
      <el-form-item label="LOCATION">
        <el-input
          v-model="queryForm['LOCATION']"
          placeholder="Please enter name"
        />
      </el-form-item>
      <el-form-item label="UoB ALUMNI">
        <el-input
          v-model="queryForm['UoB ALUMNI']"
          placeholder="Please enter name"
        />
      </el-form-item>
      <el-form-item label="Programme of study engaged">
        <el-input
          v-model="queryForm['Programme of study engaged']"
          placeholder="Please enter name"
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
      <el-upload action="" :before-upload="handleUpload" :auto-upload="false">
        <el-button>Upload Excel</el-button>
      </el-upload>
      <el-button @click="exportExcel">Export</el-button>
    </div>

    <!-- table -->
    <el-table
      :data="pagedData"
      stripe
      border
      style="width: 100%"
      max-height="500"
      table-layout="fixed"
    >
      <el-table-column
        fixed
        prop="ORGANISATION"
        label="ORGANISATION"
        width="150"
      />
      <el-table-column
        prop="ORGANISATION SECTOR"
        label="ORGANISATION SECTOR"
        width="120"
      />
      <el-table-column prop="FIRST NAME" label="FIRST NAME" width="120" />
      <el-table-column prop="SURNAME" label="SURNAME" width="120" />
      <el-table-column prop="JOB TITLE" label="JOB TITLE" width="120" />
      <el-table-column prop="EMAIL ADDRESS" label="EMAIL ADDRESS" width="120" />
      <el-table-column prop="LOCATION" label="LOCATION" width="120" />
      <el-table-column
        prop="Programme of study engaged"
        label="Programme of study engaged"
        width="300"
      />
      <el-table-column fixed="right" label="Operations" min-width="150">
        <template #default="scope">
          <el-button size="small" @click="handleEdit(scope.row)">
            Edit
          </el-button>
          <el-button
            size="small"
            type="danger"
            @click="handleDelete(scope.$index)"
          >
            Delete
          </el-button>
        </template>
      </el-table-column>
    </el-table>

    <!-- pagination -->
    <div class="pagination-wrapper">
      <el-pagination
        @size-change="handleSizeChange"
        @current-change="handleCurrentChange"
        :current-page="currentPage"
        :page-size="pageSize"
        layout="total, sizes, prev, pager, next, jumper"
        :total="total"
        :page-sizes="[5, 10, 20, 50]"
      />
    </div>

    <!-- dialog for add/edit -->
    <el-dialog
      v-model="dialogVisible"
      title="Add/Edit Data"
      :before-close="handleClose"
    >
      <el-form :model="editForm">
        <el-form-item label="ORGANISATION">
          <el-input v-model="editForm.ORGANISATION" />
        </el-form-item>
        <el-form-item label="ORGANISATION SECTOR">
          <el-input v-model="editForm['ORGANISATION SECTOR']" />
        </el-form-item>
        <el-form-item label="FIRST NAME">
          <el-input v-model="editForm['FIRST NAME']" />
        </el-form-item>
        <el-form-item label="SURNAME">
          <el-input v-model="editForm.SURNAME" />
        </el-form-item>
        <el-form-item label="JOB TITLE">
          <el-input v-model="editForm['JOB TITLE']" />
        </el-form-item>
        <el-form-item label="EMAIL ADDRESS">
          <el-input v-model="editForm['EMAIL ADDRESS']" />
        </el-form-item>
        <el-form-item label="LOCATION">
          <el-input v-model="editForm.LOCATION" />
        </el-form-item>
        <el-form-item label="UoB ALUMNI">
          <el-input v-model="editForm['UoB ALUMNI']" />
        </el-form-item>
        <el-form-item label="Programme of study engaged">
          <el-input v-model="editForm['Programme of study engaged']" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancel</el-button>
        <el-button type="primary" @click="confirmEdit">Confirm</el-button>
      </span>
    </el-dialog>
  </div>
</template>

<script setup lang="ts">
import { ref, reactive, computed } from "vue";
import {
  ElButton,
  ElTable,
  ElTableColumn,
  ElPagination,
  ElDialog,
  ElForm,
  ElFormItem,
  ElInput,
  ElMessageBox,
} from "element-plus";

interface ITableData {
  ORGANISATION: string;
  "ORGANISATION SECTOR": string;
  "FIRST NAME": string;
  SURNAME: string;
  "JOB TITLE": string;
  "EMAIL ADDRESS": string;
  LOCATION: string;
  "UoB ALUMNI": boolean;
  "Programme of study engaged": string;
}

const queryForm = reactive<ITableData>({
  ORGANISATION: "",
  "ORGANISATION SECTOR": "",
  "FIRST NAME": "",
  SURNAME: "",
  "JOB TITLE": "",
  "EMAIL ADDRESS": "",
  LOCATION: "",
  "UoB ALUMNI": false,
  "Programme of study engaged": "",
});

const handleQuery = () => {
  currentPage.value = 1;
  // TODO: Implement query logic
};

const handleReset = () => {
  queryForm.ORGANISATION = "";
  queryForm["ORGANISATION SECTOR"] = "";
  queryForm["FIRST NAME"] = "";
  queryForm.SURNAME = "";
  queryForm["JOB TITLE"] = "";
  queryForm["EMAIL ADDRESS"] = "";
  queryForm.LOCATION = "";
  queryForm["UoB ALUMNI"] = false;
  queryForm["Programme of study engaged"] = "";
  handleQuery();
};

const handleUpload = (file: Blob) => {
  const reader = new FileReader();
  reader.onload = (_e) => {
    // TODO: Implement upload logic
    alert("TODO: Upload file");
  };
  reader.readAsArrayBuffer(file);
  return false; // 阻止默认的上传行为
};

const exportExcel = () => {
  // TODO: Implement export logic
  alert("TODO: Export Data");
};

const tableData = ref<ITableData[]>([
  {
    ORGANISATION: "Actisense – Active Research Limited",
    "ORGANISATION SECTOR": "ICT",
    "FIRST NAME": "test",
    SURNAME: "test",
    "JOB TITLE": "test",
    "EMAIL ADDRESS": "test",
    LOCATION: "test",
    "UoB ALUMNI": false,
    "Programme of study engaged": "test",
  },
  {
    ORGANISATION: "Actisense – Active Research Limited",
    "ORGANISATION SECTOR": "ICT",
    "FIRST NAME": "test",
    SURNAME: "test",
    "JOB TITLE": "test",
    "EMAIL ADDRESS": "test",
    LOCATION: "test",
    "UoB ALUMNI": false,
    "Programme of study engaged": "test",
  },
  {
    ORGANISATION: "Actisense – Active Research Limited",
    "ORGANISATION SECTOR": "ICT",
    "FIRST NAME": "test",
    SURNAME: "test",
    "JOB TITLE": "test",
    "EMAIL ADDRESS": "test",
    LOCATION: "test",
    "UoB ALUMNI": false,
    "Programme of study engaged": "test",
  },
  {
    ORGANISATION: "Actisense – Active Research Limited",
    "ORGANISATION SECTOR": "ICT",
    "FIRST NAME": "test",
    SURNAME: "test",
    "JOB TITLE": "test",
    "EMAIL ADDRESS": "test",
    LOCATION: "test",
    "UoB ALUMNI": false,
    "Programme of study engaged": "test",
  },
]);

const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(tableData.value.length);
const dialogVisible = ref(false);
const editForm = reactive<ITableData>({
  ORGANISATION: "",
  "ORGANISATION SECTOR": "",
  "FIRST NAME": "",
  SURNAME: "",
  "JOB TITLE": "",
  "EMAIL ADDRESS": "",
  LOCATION: "",
  "UoB ALUMNI": false,
  "Programme of study engaged": "",
});
let isEdit = ref(false);

const pagedData = computed(() => {
  total.value = tableData.value.length;
  const start = (currentPage.value - 1) * pageSize.value;
  const end = start + pageSize.value;
  return tableData.value.slice(start, end);
});

const handleAdd = () => {
  isEdit.value = false;
  editForm.ORGANISATION = "";
  editForm["ORGANISATION SECTOR"] = "";
  editForm["FIRST NAME"] = "";
  editForm.SURNAME = "";
  editForm["JOB TITLE"] = "";
  editForm["EMAIL ADDRESS"] = "";
  editForm.LOCATION = "";
  editForm["UoB ALUMNI"] = false;
  editForm["Programme of study engaged"] = "";
  dialogVisible.value = true;
};

const handleEdit = (row: ITableData) => {
  isEdit.value = true;
  Object.assign(editForm, row);
  dialogVisible.value = true;
};

const handleDelete = (index: number) => {
  tableData.value.splice(index, 1); // 从数据中删除指定的项
  total.value = tableData.value.length; // 更新总数以反映更改
  alert("TODO: Delete Data -> wait backend api");
};

const handleSizeChange = (size: number) => {
  pageSize.value = size;
};

const handleCurrentChange = (page: number) => {
  currentPage.value = page;
};

const handleClose = (done: () => void) => {
  ElMessageBox.confirm("Are you sure to close this dialog?")
    .then(() => {
      done();
    })
    .catch(() => {
      // catch error
    });
};

const confirmEdit = () => {
  if (isEdit.value) {
    // TODO: Implement edit logic
    alert("TODO: Edit Data -> wait backend api");
  } else {
    // TODO: Implement add logic
    alert("TODO: Add Data -> wait backend api");
  }
  dialogVisible.value = false;
};
</script>

<style scoped>
.container {
  padding: 20px;
}

.query-form {
  margin-bottom: 20px;
}

.styled-form {
  display: flex;
  flex-wrap: wrap;
  gap: 10px; /* Adjusted gap to fit more compactly */
}

.styled-form .el-form-item {
  flex: 1 1 calc(25% - 10px); /* Adjusted width for compact layout */
  display: flex;
  align-items: center; /* Aligns label and input vertically centered */
}

.styled-form .el-form-item .el-form-item__label {
  text-align: right; /* Right-aligns the label */
  width: 150px; /* Adjust width as needed */
  margin-right: 10px; /* Space between label and input */
}

.styled-form .el-form-item .el-form-item__content {
  flex: 1;
}

.form-actions {
  flex-basis: 100%;
  display: flex;
  justify-content: flex-end;
  gap: 10px;
}

.function-buttons {
  display: flex;
  gap: 10px;
  margin-bottom: 20px;
}

.pagination-wrapper {
  display: flex;
  justify-content: flex-end;
  margin-top: 20px;
}

.dialog-footer {
  text-align: right;
}
</style>
