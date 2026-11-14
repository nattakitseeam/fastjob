<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h3 class="fw-bold mb-0"><i class="bi bi-shield-check text-primary me-2"></i>ระบบจัดการและอนุมัติงาน (Admin)</h3>
      <button @click="fetchAllServices" class="btn btn-outline-secondary btn-sm rounded-pill">
        <i class="bi bi-arrow-clockwise"></i> รีเฟรชข้อมูล
      </button>
    </div>

    <!-- การ์ดสรุปสถิติ -->
    <div class="row g-3 mb-4">
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-warning bg-opacity-10">
          <div class="card-body">
            <h6 class="text-muted">งานที่รออนุมัติ</h6>
            <h2 class="fw-bold text-warning mb-0">{{ pendingCount }} รายการ</h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-success bg-opacity-10">
          <div class="card-body">
            <h6 class="text-muted">อนุมัติแล้ว (แสดงบนเว็บ)</h6>
            <h2 class="fw-bold text-success mb-0">{{ approvedCount }} รายการ</h2>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
          <div class="card-body">
            <h6 class="text-muted">งานทั้งหมดในระบบ</h6>
            <h2 class="fw-bold text-primary mb-0">{{ services.length }} รายการ</h2>
          </div>
        </div>
      </div>
    </div>

    <!-- ตารางรายการอนุมัติ -->
    <div class="card border-0 shadow-sm rounded-4">
      <div class="card-body p-0">
        <!-- สถานะกำลังโหลด -->
        <div v-if="isLoading" class="text-center py-5">
          <div class="spinner-border text-primary" role="status"></div>
          <p class="mt-2 text-muted small">กำลังโหลดรายการทั้งหมด...</p>
        </div>

        <!-- ตารางข้อมูล -->
        <div v-else-if="services.length > 0" class="table-responsive">
          <table class="table table-hover align-middle mb-0">
            <thead class="table-light">
              <tr>
                <th class="ps-4">ผู้รับจ้าง</th>
                <th>บริการ / รายละเอียด</th>
                <th>หมวดหมู่</th>
                <th>ราคา</th>
                <th>รูปภาพ</th>
                <th>สถานะ</th>
                <th class="text-end pe-4">การอนุมัติ</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="item in services" :key="item.id">
                <td class="ps-4 fw-medium">{{ item.freelance_name }}</td>
                <td>
                  <div class="fw-bold">{{ item.title }}</div>
                  <small class="text-muted d-block text-truncate" style="max-width: 250px;">{{ item.description }}</small>
                </td>
                <td><span class="badge bg-light text-dark border">{{ item.category }}</span></td>
                <td class="fw-bold text-primary">฿{{ Number(item.price).toLocaleString() }}</td>
                <td>
                  <a v-if="item.image_url" :href="item.image_url" target="_blank" class="btn btn-sm btn-outline-primary rounded-pill">
                    <i class="bi bi-image"></i> ดูรูป
                  </a>
                  <span v-else class="text-muted small">ไม่มีรูป</span>
                </td>
                <td><span :class="statusBadge(item.status)">{{ item.status }}</span></td>
                <td class="text-end pe-4">
                  <button @click="updateStatus(item.id, 'อนุมัติ')" class="btn btn-success btn-sm rounded-pill me-1 px-3">
                    <i class="bi bi-check-lg"></i> อนุมัติ
                  </button>
                  <button @click="updateStatus(item.id, 'ไม่อนุมัติ')" class="btn btn-danger btn-sm rounded-pill px-3">
                    <i class="bi bi-x-lg"></i> ปฏิเสธ
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- กรณีไม่มีข้อมูล -->
        <div v-else class="text-center py-5 text-muted">
          <i class="bi bi-inbox fs-1"></i>
          <p class="mt-2 mb-0">ยังไม่มีรายการโพสต์บริการในระบบ</p>
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
      isLoading: true
    }
  },
  computed: {
    pendingCount() {
      return this.services.filter(s => s.status === 'รออนุมัติ').length;
    },
    approvedCount() {
      return this.services.filter(s => s.status === 'อนุมัติ').length;
    }
  },
  mounted() {
    const u = localStorage.getItem('user');
    if (!u || JSON.parse(u).role !== 'admin') { 
      this.$router.push('/login'); 
      return; 
    }
    this.fetchAllServices();
  },
  methods: {
    async fetchAllServices(retries = 3) {
      this.isLoading = true;
      try {
        const res = await fetch('http://localhost/fastjob/freelance_api/services.php');
        if (!res.ok) throw new Error('Network response error');
        const data = await res.json();
        this.services = Array.isArray(data) ? data : [];
        this.isLoading = false;
      } catch (error) {
        if (retries > 0) {
          setTimeout(() => this.fetchAllServices(retries - 1), 1000);
        } else {
          this.isLoading = false;
          console.error('ไม่สามารถโหลดข้อมูลแอดมินได้:', error);
        }
      }
    },
    async updateStatus(id, status) {
      try {
        await fetch('http://localhost/fastjob/freelance_api/services.php', {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ id: id, status: status })
        });
        this.fetchAllServices();
      } catch (err) {
        alert('อัปเดตสถานะไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
      }
    },
    statusBadge(status) {
      if (status === 'อนุมัติ') return 'badge bg-success rounded-pill px-3';
      if (status === 'ไม่อนุมัติ') return 'badge bg-danger rounded-pill px-3';
      return 'badge bg-warning text-dark rounded-pill px-3';
    }
  }
}
</script>