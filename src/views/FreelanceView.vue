<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold mb-0">จัดการบริการรับจ้างของคุณ</h3>
        <small class="text-muted">สร้างโพสต์บริการเพื่อให้แอดมินตรวจสอบและแสดงบนหน้าเว็บไซต์</small>
      </div>
    </div>
    
    <div class="row g-4">
      <!-- ฟอร์มสร้างโพสต์บริการ -->
      <div class="col-lg-5">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-primary text-white py-3 rounded-top-4">
            <h5 class="mb-0 fw-bold"><i class="bi bi-plus-circle me-2"></i>เปิดรับบริการใหม่</h5>
          </div>
          <div class="card-body p-4">
            <form @submit.prevent="createService">
              <div class="mb-3">
                <label class="form-label fw-medium">ชื่อบริการของคุณ</label>
                <input v-model="form.title" type="text" class="form-control" required placeholder="เช่น รับออกแบบโลโก้มินิมอล">
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">หมวดหมู่</label>
                <select v-model="form.category" class="form-select" required>
                  <option value="" disabled>-- เลือกหมวดหมู่ --</option>
                  <option value="กราฟิก & ดีไซน์">กราฟิก & ดีไซน์</option>
                  <option value="ตัดต่อ & วิดีโอ">ตัดต่อ & วิดีโอ</option>
                  <option value="โปรแกรม & ไอที">โปรแกรม & ไอที</option>
                  <option value="การตลาด & โฆษณา">การตลาด & โฆษณา</option>
                  <option value="เขียนและแปลภาษา">เขียนและแปลภาษา</option>
                </select>
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">รายละเอียดบริการและขั้นตอนการทำงาน</label>
                <textarea v-model="form.description" class="form-control" rows="4" required placeholder="อธิบายสิ่งที่ลูกค้าจะได้รับ..."></textarea>
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">ราคาเริ่มต้น (บาท)</label>
                <input v-model="form.price" type="number" class="form-control" required placeholder="500">
              </div>
              <div class="mb-4">
                <label class="form-label fw-medium">รูปภาพหน้าปกผลงาน</label>
                <input type="file" class="form-control" accept="image/*" @change="handleFile">
              </div>
              <button type="submit" class="btn btn-primary w-100 py-2 rounded-pill fw-bold shadow-sm" :disabled="isSubmitting">
                <span v-if="isSubmitting" class="spinner-border spinner-border-sm me-2"></span>
                {{ isSubmitting ? 'กำลังบันทึก...' : 'ส่งตรวจสอบ (รอ Admin อนุมัติ)' }}
              </button>
            </form>
          </div>
        </div>
      </div>

      <!-- รายการบริการที่เคยโพสต์ -->
      <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4">
          <div class="card-header bg-white py-3 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="mb-0 fw-bold">รายการบริการของคุณ ({{ myServices.length }})</h5>
            <button @click="fetchMyServices" class="btn btn-sm btn-outline-secondary rounded-pill">
              <i class="bi bi-arrow-clockwise"></i> รีเฟรช
            </button>
          </div>
          <div class="card-body p-0">
            <!-- สถานะกำลังโหลด -->
            <div v-if="isLoading" class="text-center py-5">
              <div class="spinner-border text-primary" role="status"></div>
              <p class="mt-2 text-muted small">กำลังโหลดข้อมูล...</p>
            </div>

            <!-- ตารางแสดงข้อมูล -->
            <div v-else-if="myServices.length > 0" class="table-responsive">
              <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                  <tr>
                    <th class="ps-4">บริการ</th>
                    <th>ราคา</th>
                    <th>สถานะงาน</th>
                    <th class="text-end pe-4">จัดการ / แชท</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="item in myServices" :key="item.id">
                    <td class="ps-4">
                      <div class="d-flex align-items-center">
                        <img :src="item.image_url || 'https://placehold.co/60x60/e9ecef/495057?text=No+Img'" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        <div>
                          <div class="fw-bold">{{ item.title }}</div>
                          <small class="text-muted">{{ item.category }}</small>
                          <!-- Badge แจ้งเตือนสีเหลือง เมื่อมีลูกค้าทักมาใหม่ -->
                          <div v-if="hasUnread(item.id)" class="mt-1">
                            <span class="badge bg-warning text-dark shadow-sm">
                              <i class="bi bi-chat-dots-fill me-1"></i> มีลูกค้าทักมาใหม่!
                            </span>
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="fw-bold text-primary">฿{{ Number(item.price).toLocaleString() }}</td>
                    <td><span :class="statusBadge(item.status)">{{ item.status }}</span></td>
                    <td class="text-end pe-4">
                      <button @click="openChat(item)" class="btn btn-warning btn-sm rounded-pill me-1 mb-1 fw-bold text-dark position-relative">
                        <i class="bi bi-chat-dots-fill"></i> แชท
                        <span v-if="hasUnread(item.id)" class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle">
                          <span class="visually-hidden">New alerts</span>
                        </span>
                      </button>
                      <button @click="deleteService(item.id)" class="btn btn-outline-danger btn-sm rounded-pill mb-1">
                        <i class="bi bi-trash"></i> ลบ
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- กรณีไม่มีข้อมูล -->
            <div v-else class="text-center py-5 text-muted">
              <i class="bi bi-folder2-open fs-1"></i>
              <p class="mt-2 mb-0">ยังไม่มีรายการบริการที่คุณโพสต์</p>
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
      user: {},
      myServices: [],
      serviceMessages: {},
      isLoading: true,
      isSubmitting: false,
      form: { title: '', category: '', description: '', price: '', image: null },
      pollInterval: null
    }
  },
  mounted() {
    const u = localStorage.getItem('user');
    if (!u) { 
      this.$router.push('/login'); 
      return; 
    }
    this.user = JSON.parse(u);
    this.fetchMyServices();

    this.pollInterval = setInterval(() => {
      this.checkChatNotifications();
    }, 5000);
  },
  unmounted() {
    clearInterval(this.pollInterval);
  },
  methods: {
    async fetchMyServices(retries = 3) {
      this.isLoading = true;
      try {
        const res = await fetch(`http://localhost/fastjob/freelance_api/services.php?freelance_id=${this.user.id}`);
        if (!res.ok) throw new Error('Network response error');
        const data = await res.json();
        this.myServices = Array.isArray(data) ? data : [];
        this.isLoading = false;
        this.checkChatNotifications();
      } catch (error) {
        if (retries > 0) {
          setTimeout(() => this.fetchMyServices(retries - 1), 1000);
        } else {
          this.isLoading = false;
          console.error('ไม่สามารถโหลดข้อมูลบริการได้:', error);
        }
      }
    },
    async checkChatNotifications() {
      let updatedMessages = { ...this.serviceMessages };
      for (let item of this.myServices) {
        try {
          const res = await fetch(`http://localhost/fastjob/freelance_api/chat.php?service_id=${item.id}`);
          const msgs = await res.json();
          if (Array.isArray(msgs)) {
            updatedMessages[item.id] = msgs;
          }
        } catch (err) {
          console.error('Check chat error:', err);
        }
      }
      this.serviceMessages = updatedMessages;
    },
    hasUnread(itemId) {
      const msgs = this.serviceMessages[itemId] || [];
      if (msgs.length === 0) return false;

      const lastMsg = msgs[msgs.length - 1];
      const lastReadId = parseInt(localStorage.getItem('last_read_msg_' + itemId) || 0);

      // แปลงเป็น Number ทั้งหมดเพื่อป้องกันบั๊กเปรียบเทียบแบบ String
      const lastMsgIdNum = Number(lastMsg.id);
      const lastReadIdNum = Number(lastReadId);
      const senderIdNum = Number(lastMsg.sender_id);
      const userIdNum = Number(this.user.id);

      // เช็กว่าไอดีข้อความใหม่กว่าที่เคยอ่าน และผู้ส่งไม่ใช่ตัวฟรีแลนซ์เอง
      return lastMsgIdNum > lastReadIdNum && senderIdNum !== userIdNum;
    },
    handleFile(e) {
      this.form.image = e.target.files[0];
    },
    async createService() {
      this.isSubmitting = true;
      const fd = new FormData();
      fd.append('freelance_id', this.user.id);
      fd.append('title', this.form.title);
      fd.append('category', this.form.category);
      fd.append('description', this.form.description);
      fd.append('price', this.form.price);
      if (this.form.image) fd.append('image', this.form.image);

      try {
        const res = await fetch('http://localhost/fastjob/freelance_api/services.php', { method: 'POST', body: fd });
        const data = await res.json();
        if (data.status === 'success') {
          alert('สร้างโพสต์สำเร็จ! กรุณารอ Admin อนุมัติ');
          this.form = { title: '', category: '', description: '', price: '', image: null };
          this.fetchMyServices();
        } else {
          alert('เกิดข้อผิดพลาด: ' + (data.message || 'ไม่สามารถบันทึกได้'));
        }
      } catch (err) {
        alert('ไม่สามารถเชื่อมต่อกับเซิร์ฟเวอร์ได้');
      } finally {
        this.isSubmitting = false;
      }
    },
    openChat(item) {
      this.$router.push({
        name: 'chat',
        params: { serviceId: item.id },
        query: { title: item.title }
      });
    },
    async deleteService(id) {
      if (confirm('ยืนยันลบโพสต์นี้?')) {
        try {
          await fetch(`http://localhost/fastjob/freelance_api/services.php?id=${id}`, { method: 'DELETE' });
          this.fetchMyServices();
        } catch (err) {
          alert('ลบไม่สำเร็จ กรุณาลองใหม่อีกครั้ง');
        }
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