<template>
  <AuthenticatedLayout>
    <template #header>
      <h2 class="font-semibold text-2xl text-gray-900 leading-tight">Lịch sử In</h2>
    </template>

    <div class="py-12">
      <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
        <div class="bg-white overflow-hidden shadow-lg sm:rounded-lg">
          <div class="p-6 text-gray-800">
            <div v-if="!printHistories || !printHistories.data || !printHistories.data.length" class="text-center">
              <p class="text-lg">Không có lịch sử in nào để hiển thị.</p>
            </div>
            <div v-else>
              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <div
                  v-for="history in printHistories.data"
                  :key="history.id"
                  class="history-card transition-transform transform hover:scale-105 p-4 border border-gray-300 rounded-lg shadow-md bg-white"
                >
                  <h4 class="text-xl font-bold text-gray-800">{{ history.name }}</h4>
                  <p class="text-gray-600">{{ history.phone }}</p>
                  <p class="text-gray-500">In bởi: <span class="font-semibold">{{ history.printer }}</span></p>
                  <p class="text-gray-500">Thời gian: <span class="font-semibold">{{ history.time }}</span></p>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="pagination flex justify-center mt-6">
          <button class="pagination-button" :disabled="!prevPage" @click="prevPageHandler">Trước</button>
          <span class="mx-4">Trang {{ currentPage }} của {{ totalPages }}</span>
          <button class="pagination-button" :disabled="!nextPage" @click="nextPageHandler">Sau</button>
        </div>
      </div>
    </div>
  </AuthenticatedLayout>
</template>

<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ref, computed } from 'vue';
import { usePage, router } from '@inertiajs/vue3';

const page = usePage();
const printHistories = ref(page.props.printHistories);

// Sử dụng phản hồi để cung cấp giá trị cho currentPage và totalPages
const currentPage = ref(printHistories.value.current_page || 1);
const totalPages = ref(printHistories.value.last_page || 1);

// Tính toán cho các trang trước và sau
const prevPage = computed(() => currentPage.value > 1);
const nextPage = computed(() => currentPage.value < totalPages.value);

// Hàm gửi yêu cầu và cập nhật dữ liệu
const fetchPage = (pageNumber) => {
  console.log(`Fetching page: ${pageNumber}`); // Kiểm tra trang đang tải
  router.get('/api/print-history', { page: pageNumber }, {
    preserveState: true,
    preserveScroll: true,
    onSuccess: (response) => {
      console.log("Dữ liệu mới từ server:", response.props.printHistories);
      printHistories.value = response.props.printHistories || { current_page: 1, last_page: 1, data: [] };
      currentPage.value = printHistories.value.current_page;
      totalPages.value = printHistories.value.last_page;
    },
    onError: (errors) => {
      console.error("Lỗi khi lấy dữ liệu:", errors);
    }
  });
};

// Hàm xử lý sự kiện cho nút "Trước"
const prevPageHandler = () => {
  if (prevPage.value) {
    fetchPage(currentPage.value - 1);
  }
};

// Hàm xử lý sự kiện cho nút "Sau"
const nextPageHandler = () => {
  if (nextPage.value) {
    fetchPage(currentPage.value + 1);
  }
};
</script>

<style scoped>
.history-card {
  background: #ffffff;
  border: 1px solid #eaeaea;
  border-radius: 8px;
  padding: 20px;
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.history-card h4 {
  font-size: 1.25rem;
  color: #3b3f45;
  margin-bottom: 15px;
}

.history-card p {
  font-size: 1rem;
  margin: 5px 0;
}

.pagination {
  display: flex;
  justify-content: center;
  align-items: center;
  margin-top: 20px;
}

.pagination-button {
  background-color: #4caf50;
  color: white;
  border: none;
  padding: 10px 20px;
  margin: 0 5px;
  border-radius: 5px;
  cursor: pointer;
  transition: background-color 0.3s;
}

.pagination-button:disabled {
  background-color: #ccc;
  cursor: not-allowed;
}

.pagination-button:hover:not(:disabled) {
  background-color: #45a049;
}
</style>