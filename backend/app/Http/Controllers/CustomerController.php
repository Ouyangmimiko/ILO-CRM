<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    /**
     * Store a newly created customer in storage.
     */
    public function store(Request $request): JsonResponse
    {
        // 验证输入数据
        $validator = Validator::make($request->all(), [
            'organisation' => 'nullable|string|max:255',
            'organisation_sector' => 'nullable|string|max:255',
            'first_name' => 'nullable|string|max:255',
            'surname' => 'nullable|string|max:255',
            'job_title' => 'nullable|string|max:255',
            'email' => 'required|email|unique:customer,email',
            'location' => 'nullable|string|max:255',
            'uob_alumni' => 'nullable|in:yes,no',
            'programme_of_study_engaged' => 'nullable|string|max:255',
            'mentoring_2021_22' => 'nullable|in:yes,no',
            'mentoring_2022_23' => 'nullable|in:yes,no',
            'mentoring_2023_24' => 'nullable|in:yes,no',
            'yii_2021_22' => 'nullable|in:yes,no',
            'yii_2022_23' => 'nullable|in:yes,no',
            'yii_2023_24' => 'nullable|in:yes,no',
            'projects_2021_22' => 'nullable|in:yes,no',
            'projects_2022_23' => 'nullable|in:yes,no',
            'projects_2023_24' => 'nullable|in:yes,no',
            'info_related_to_contacting_the_partner' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 422);
        }

        // 创建新的客户
        $customer = Customer::create($request->all());

        return response()->json([
            'message' => 'Customer created successfully.',
            'customer' => $customer
        ], 201);
    }

    /**
     * Remove the specified customer from storage.
     */
    public function destroy($id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.'
            ], 404);
        }

        $customer->delete();

        return response()->json([
            'message' => 'Customer deleted successfully.'
        ], 200);
    }

    /**
     * Update the specified customer in storage.
     */
    public function update(Request $request, $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'message' => 'Customer not found.'
            ], 404);
        }

        // 验证请求数据
        $validatedData = $request->validate([
            'organisation' => 'string|nullable',
            'organisation_sector' => 'string|nullable',
            'first_name' => 'string|nullable',
            'surname' => 'string|nullable',
            'job_title' => 'string|nullable',
            'email' => 'string|email|unique:customer,email,' . $id,
            'location' => 'string|nullable',
            'uob_alumni' => 'in:yes,no|nullable',
            'programme_of_study_engaged' => 'string|nullable',
            'mentoring_2021_22' => 'in:yes,no|nullable',
            'mentoring_2022_23' => 'in:yes,no|nullable',
            'mentoring_2023_24' => 'in:yes,no|nullable',
            'yii_2021_22' => 'in:yes,no|nullable',
            'yii_2022_23' => 'in:yes,no|nullable',
            'yii_2023_24' => 'in:yes,no|nullable',
            'projects_2021_22' => 'in:yes,no|nullable',
            'projects_2022_23' => 'in:yes,no|nullable',
            'projects_2023_24' => 'in:yes,no|nullable',
            'info_related_to_contacting_the_partner' => 'string|nullable',
        ]);

        // 更新客户信息
        $customer->update($validatedData);

        return response()->json([
            'message' => 'Customer updated successfully.',
            'customer' => $customer
        ], 200);
    }

    /**
     * Search for customers based on different criteria.
     */
    public function search(Request $request): JsonResponse
    {
        //有all选项的搜索
        $query = Customer::query();
        $searchType = $request->input('searchType');
        $searchTerm = $request->input('searchTerm');

        // Handle "all" search
        if ($request->input('search_all') === 'true') {
            if ($searchTerm) {
                $query->where(function ($q) use ($searchTerm) {
                    $q->where('organisation', 'like', '%' . $searchTerm . '%')
                        ->orWhere('organisation_sector', 'like', '%' . $searchTerm . '%')
                        ->orWhere('first_name', 'like', '%' . $searchTerm . '%')
                        ->orWhere('surname', 'like', '%' . $searchTerm . '%')
                        ->orWhere('job_title', 'like', '%' . $searchTerm . '%')
                        ->orWhere('email', 'like', '%' . $searchTerm . '%')
                        ->orWhere('location', 'like', '%' . $searchTerm . '%')
                        ->orWhere('programme_of_study_engaged', 'like', '%' . $searchTerm . '%')
                        ->orWhere('info_related_to_contacting_the_partner', 'like', '%' . $searchTerm . '%');
                });
            }
        } elseif ($searchType && $searchTerm) {
            // Handle search for specific field
            $query->where($searchType, 'like', '%' . $searchTerm . '%');
        }

        $customers = $query->get();

        return response()->json([
            'customers' => $customers
        ], 200);
        /*前端代码
        <template>
  <div>
    <select v-model="searchType" @change="search">
      <option value="all">All</option>
      <option value="organisation">Organisation</option>
      <option value="organisation_sector">Organisation Sector</option>
      <option value="first_name">First Name</option>
      <option value="surname">Surname</option>
      <option value="job_title">Job Title</option>
      <option value="email">Email</option>
      <option value="location">Location</option>
      <option value="programme_of_study_engaged">Programme of Study Engaged</option>
      <option value="info_related_to_contacting_the_partner">Info Related to Contacting the Partner</option>
    </select>

    <input v-model="searchTerm" @input="search" placeholder="Enter search term">
  </div>
</template>

<script>
export default {
  data() {
    return {
      searchType: 'all',
      searchTerm: ''
    }
  },
  methods: {
    async search() {
      const response = await fetch('/api/customers?' + new URLSearchParams({
        search_all: this.searchType === 'all' ? 'true' : 'false',
        searchType: this.searchType !== 'all' ? this.searchType : '',
        searchTerm: this.searchTerm
      }), {
        method: 'GET',
      });
      const data = await response.json();
      console.log(data);
    }
  }
}
</script>
*/
    }

    /**
     * show one customer details.
     */
    public function show($id)
    {
        try {
            // 查找客户记录
            $customer = Customer::findOrFail($id);

            // 返回 JSON 格式的数据
            return response()->json([
                'success' => true,
                'data' => $customer
            ], 200);

        } catch (ModelNotFoundException $e) {
            // 处理找不到客户的情况
            return response()->json([
                'success' => false,
                'message' => 'Customer not found'
            ], 404);
        } catch (\Exception $e) {
            // 处理其他可能的错误
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage()
            ], 500);
        }
    }
}
