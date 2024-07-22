<template>
  <div class="container">
    <!-- form -->
    <el-form :model="queryForm" inline class="query-form">
      <el-form-item label="Name">
        <el-input v-model="queryForm.name" placeholder="Please enter name" />
      </el-form-item>
      <el-form-item label="Address">
        <el-input
          v-model="queryForm.address"
          placeholder="Please enter address"
        />
      </el-form-item>
      <el-form-item>
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
      <el-table-column fixed prop="date" label="Date" width="150" />
      <el-table-column prop="name" label="Name" width="120" />
      <el-table-column prop="state" label="State" width="120" />
      <el-table-column prop="city" label="City" width="120" />
      <el-table-column prop="address" label="Address" width="600" />
      <el-table-column prop="zip" label="Zip" width="120" />
      <el-table-column fixed="right" label="Operations" min-width="120">
        <template #default="scope">
          <el-button size="small" @click="handleEdit(scope.$index, scope.row)">
            Edit
          </el-button>
          <el-button
            size="small"
            type="danger"
            @click="handleDelete(scope.$index, scope.row)"
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
        <el-form-item label="Date">
          <el-input v-model="editForm.date" />
        </el-form-item>
        <el-form-item label="Name">
          <el-input v-model="editForm.name" />
        </el-form-item>
        <el-form-item label="State">
          <el-input v-model="editForm.state" />
        </el-form-item>
        <el-form-item label="City">
          <el-input v-model="editForm.city" />
        </el-form-item>
        <el-form-item label="Address">
          <el-input v-model="editForm.address" />
        </el-form-item>
        <el-form-item label="Zip">
          <el-input v-model="editForm.zip" />
        </el-form-item>
      </el-form>
      <span slot="footer" class="dialog-footer">
        <el-button @click="dialogVisible = false">Cancel</el-button>
        <el-button type="primary" @click="dialogVisible = false"
          >Confirm</el-button
        >
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
  date: string;
  name: string;
  state: string;
  city: string;
  address: string;
  zip: string;
  tag: string;
}

const queryForm = ref({
  name: "",
  address: "",
});

const handleQuery = () => {
  currentPage.value = 1;
  // TODO: Implement query logic
};

const handleReset = () => {
  queryForm.value.name = "";
  queryForm.value.address = "";
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

const tableData = ref([
  {
    date: "2016-05-03",
    name: "Tom",
    state: "California",
    city: "Los Angeles",
    address: "No. 189, Grove St, Los Angeles",
    zip: "CA 90036",
    tag: "Home",
  },
  {
    date: "2016-05-02",
    name: "Tom",
    state: "California",
    city: "Los Angeles",
    address: "No. 189, Grove St, Los Angeles",
    zip: "CA 90036",
    tag: "Office",
  },
  {
    date: "2016-05-04",
    name: "Tom",
    state: "California",
    city: "Los Angeles",
    address: "No. 189, Grove St, Los Angeles",
    zip: "CA 90036",
    tag: "Home",
  },
  {
    date: "2016-05-01",
    name: "Tom",
    state: "California",
    city: "Los Angeles",
    address: "No. 189, Grove St, Los Angeles",
    zip: "CA 90036",
    tag: "Office",
  },
]);

const currentPage = ref(1);
const pageSize = ref(5);
const total = ref(tableData.value.length);
const dialogVisible = ref(false);
const editForm = reactive<ITableData>({
  date: "",
  name: "",
  state: "",
  city: "",
  address: "",
  zip: "",
  tag: "",
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
  editForm.date = "";
  editForm.name = "";
  editForm.state = "";
  editForm.city = "";
  editForm.address = "";
  editForm.zip = "";
  dialogVisible.value = true;
};

const handleEdit = (index: number, row: ITableData) => {
  isEdit.value = true;
  Object.assign(editForm, row);
  dialogVisible.value = true;
};

const handleDelete = (index: number, row: ITableData) => {
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
</script>

<style scoped>
.container {
  padding: 20px;
}

.query-form {
  margin-bottom: 20px;
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
