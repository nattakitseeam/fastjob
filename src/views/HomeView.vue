<template>
  <div>
    <!-- Hero Banner สไตล์ Fastwork -->
    <div class="bg-primary text-white py-5 mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #003d99 100%);">
      <div class="container py-3 text-center">
        <h1 class="fw-bold display-5 mb-3">จ้างฟรีแลนซ์มืออาชีพ งานเสร็จไว ได้คุณภาพ</h1>
        <p class="lead mb-4 text-white-50">รวมผู้เชี่ยวชาญกว่า 100+ สาขาอาชีพ พร้อมดูแลคุณทุกขั้นตอน</p>
        
        <!-- ช่องค้นหา -->
        <div class="row justify-content-center">
          <div class="col-md-7">
            <div class="input-group input-group-lg shadow">
              <span class="input-group-text bg-white border-0"><i class="bi bi-search text-primary"></i></span>
              <input v-model="searchQuery" type="text" class="form-control border-0" placeholder="ค้นหางานที่ต้องการ เช่น ออกแบบโลโก้, ตัดต่อวิดีโอ, ทำเว็บไซต์...">
              <button class="btn btn-warning fw-bold px-4 text-dark" type="button">ค้นหา</button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div class="container">
      <!-- แถบเลือกหมวดหมู่ -->
      <div class="d-flex flex-wrap gap-2 justify-content-center mb-4">
        <button 
          @click="selectedCategory = ''" 
          :class="['btn rounded-pill px-4', selectedCategory === '' ? 'btn-primary shadow-sm' : 'btn-white bg-white border']">
          ทั้งหมด
        </button>
        <button 
          v-for="cat in categories" :key="cat"
          @click="selectedCategory = cat"
          :class="['btn rounded-pill px-4', selectedCategory === cat ? 'btn-primary shadow-sm' : 'btn-white bg-white border']">
          {{ cat }}
        </button>
      </div>

      <!-- แสดงรายการบริการ -->
      <div class="d-flex justify-content-between align-items-center mb-3">
        <h4 class="fw-bold mb-0">
          {{ selectedCategory ? `หมวดหมู่: ${selectedCategory}` : 'บริการแนะนำทั้งหมด' }}
        </h4>
        <span class="text-muted">พบ {{ filteredServices.length }} บริการ</span>
      </div>

      <!-- สถานะกำลังโหลดข้อมูล -->
      <div v-if="isLoading" class="text-center py-5">
        <div class="spinner-border text-primary" role="status">
          <span class="visually-hidden">Loading...</span>
        </div>
        <p class="mt-2 text-muted">กำลังโหลดรายการบริการ...</p>
      </div>

      <!-- กรณีไม่พบข้อมูล -->
      <div v-else-if="filteredServices.length === 0" class="text-center py-5 bg-white rounded shadow-sm">
        <i class="bi bi-inbox fs-1 text-muted"></i>
        <p class="mt-2 text-muted">ไม่พบบริการในหมวดหมู่นี้ หรือยังไม่มีงานที่ผ่านการอนุมัติ</p>
      </div>

      <!-- รายการ Card งาน -->
      <div v-else class="row g-4">
        <div class="col-md-6 col-lg-3" v-for="item in filteredServices" :key="item.id">
          <div class="card h-100 border-0 shadow-sm service-card" @click="openDetail(item)" style="cursor: pointer;">
            <!-- รูปปกงาน -->
            <div class="position-relative">
              <img :src="item.image_url || 'https://placehold.co/400x250/e9ecef/495057?text=FastJob+Service'" class="card-img-top" style="height: 170px; object-fit: cover;">
              <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2">{{ item.category }}</span>
            </div>

            <div class="card-body d-flex flex-column">
              <!-- ข้อมูลฟรีแลนซ์ -->
              <div class="d-flex align-items-center mb-2">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 12px;">
                  {{ item.freelance_name ? item.freelance_name.charAt(0) : 'F' }}
                </div>
                <small class="text-muted fw-medium text-truncate">{{ item.freelance_name }}</small>
                <span class="ms-auto text-warning small"><i class="bi bi-star-fill"></i> 5.0</span>
              </div>

              <!-- ชื่องาน -->
              <h6 class="card-title fw-bold text-dark line-clamp-2 mb-2">{{ item.title }}</h6>
              <p class="card-text text-muted small flex-grow-1 text-truncate">{{ item.description }}</p>
              
              <hr class="my-2 opacity-25">
              
              <!-- ราคา -->
              <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">เริ่มต้น</small>
                <span class="text-primary fw-bold fs-5">฿{{ Number(item.price).toLocaleString() }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Modal ดูรายละเอียดงานก่อนจ้าง -->
    <div class="modal fade" id="serviceModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content border-0 shadow" v-if="selectedItem">
          <div class="modal-header border-0">
            <span class="badge bg-primary">{{ selectedItem.category }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body pt-0">
            <img :src="selectedItem.image_url || 'https://placehold.co/800x400/e9ecef/495057?text=FastJob+Service'" class="w-100 rounded mb-3" style="max-height: 350px; object-fit: cover;">
            <h4 class="fw-bold">{{ selectedItem.title }}</h4>
            <p class="text-muted mb-3">
              <i class="bi bi-person-badge text-primary"></i> ผู้รับจ้าง: <b>{{ selectedItem.freelance_name }}</b>
            </p>
            <div class="bg-light p-3 rounded mb-3">
              <h6 class="fw-bold">รายละเอียดบริการ:</h6>
              <p class="mb-0" style="white-space: pre-line;">{{ selectedItem.description }}</p>
            </div>
            <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 p-3 rounded">
              <div>
                <span class="text-muted d-block small">ค่าบริการเริ่มต้น</span>
                <span class="text-primary fw-bold fs-3">฿{{ Number(selectedItem.price).toLocaleString() }}</span>
              </div>
              <button @click="confirmHire(selectedItem)" class="btn btn-primary btn-lg px-5 rounded-pill shadow-sm" data-bs-dismiss="modal">
                <i class="bi bi-chat-dots-fill me-2"></i> สนใจจ้างงานนี้ (คุยแชท)
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      services: [],
      isLoading: true,
      searchQuery: '',
      selectedCategory: '',
      categories: ['กราฟิก & ดีไซน์', 'ตัดต่อ & วิดีโอ', 'โปรแกรม & ไอที', 'การตลาด & โฆษณา', 'เขียนและแปลภาษา'],
      selectedItem: null,
      bsModal: null
    }
  },
  computed: {
    filteredServices() {
      return this.services.filter(item => {
        const matchCat = this.selectedCategory === '' || item.category === this.selectedCategory;
        const matchSearch = item.title.toLowerCase().includes(this.searchQuery.toLowerCase()) || 
                            item.description.toLowerCase().includes(this.searchQuery.toLowerCase());
        return matchCat && matchSearch;
      });
    }
  },
  mounted() {
    this.fetchServices();
  },
  methods: {
    async fetchServices(retries = 3) {
      this.isLoading = true;
      try {
        const res = await fetch('http://localhost/fastjob/freelance_api/services.php?public=1');
        if (!res.ok) throw new Error('Network response was not ok');
        const data = await res.json();
        this.services = Array.isArray(data) ? data : [];
        this.isLoading = false;
      } catch (error) {
        if (retries > 0) {
          setTimeout(() => this.fetchServices(retries - 1), 1000);
        } else {
          this.isLoading = false;
          console.error('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้:', error);
        }
      }
    },
    openDetail(item) {
      this.selectedItem = item;
      const modalElement = document.getElementById('serviceModal');
      this.bsModal = new window.bootstrap.Modal(modalElement);
      this.bsModal.show();
    },
    confirmHire(item) {
      const u = localStorage.getItem('user');
      if (!u) {
        alert('กรุณาเข้าสู่ระบบก่อนติดต่อจ้างงาน');
        this.$router.push('/login');
        return;
      }
      const currentUser = JSON.parse(u);

      // ตรวจสอบสิทธิ์ (ให้เฉพาะลูกค้าที่กดจ้างได้)
      if (currentUser.role !== 'customer') {
        alert('เฉพาะบัญชีลูกค้าที่สามารถกดติดต่อจ้างงานได้');
        return;
      }
      
      if (this.bsModal) {
        this.bsModal.hide();
      }

      // พุ่งเข้าห้องแชท 1-1 แยกตามไอดีลูกค้าคนนั้นๆ
      this.$router.push({
        name: 'chat',
        params: { serviceId: item.id },
        query: { 
          title: item.title,
          customer_id: currentUser.id 
        }
      });
    }
  }
}
</script>

<style scoped>
.service-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
}
</style>