<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold mb-0"><i class="bi bi-chat-square-text-fill text-primary me-2"></i>กล่องข้อความลูกค้า (Inbox)</h3>
        <small class="text-muted">รายการลูกค้าที่ติดต่อสอบถามและจ้างงานบริการของคุณ</small>
      </div>
      <button @click="fetchInbox" class="btn btn-sm btn-outline-secondary rounded-pill">
        <i class="bi bi-arrow-clockwise"></i> รีเฟรช
      </button>
    </div>

    <!-- กำลังโหลด -->
    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="mt-2 text-muted">กำลังโหลดข้อความ...</p>
    </div>

    <!-- รายการแชทลูกค้า -->
    <div v-else-if="chatList.length > 0" class="row g-3">
      <div class="col-md-12" v-for="chat in chatList" :key="chat.service_id + '-' + chat.customer_id">
        <div class="card border-0 shadow-sm rounded-4 p-3 service-card" @click="openChat(chat)" style="cursor: pointer;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fs-4" style="width: 55px; height: 55px;">
                <i class="bi bi-person-fill"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1 text-dark">ลูกค้า: {{ chat.customer_name }}</h5>
                <p class="text-muted mb-1 small"><i class="bi bi-briefcase-fill text-primary me-1"></i> บริการ: <b>{{ chat.service_title }}</b></p>
                <p class="text-secondary mb-0 text-truncate" style="max-width: 500px;"><small>ข้อความล่าสุด: {{ chat.last_message }}</small></p>
              </div>
            </div>
            <div>
              <button class="btn btn-primary rounded-pill px-4 shadow-sm">
                <i class="bi bi-chat-dots-fill me-1"></i> ตอบแชท
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ถ้ายังไม่มีใครทักมา -->
    <div v-else class="text-center py-5 bg-white rounded shadow-sm">
      <i class="bi bi-inbox fs-1 text-muted"></i>
      <p class="mt-2 text-muted">ยังไม่มีลูกค้าทักเข้ามาพูดคุยในขณะนี้</p>
    </div>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: {},
      chatList: [],
      isLoading: true
    }
  },
  mounted() {
    const u = localStorage.getItem('user');
    if (!u) {
      this.$router.push('/login');
      return;
    }
    this.user = JSON.parse(u);
    this.fetchInbox();
  },
  methods: {
    async fetchInbox() {
      this.isLoading = true;
      try {
        // ดึงรายการแชททั้งหมดที่เกี่ยวข้องกับฟรีแลนซ์คนนี้
        const res = await fetch(`http://localhost/fastjob/freelance_api/chat.php?freelance_inbox=${this.user.id}`);
        const data = await res.json();
        this.chatList = Array.isArray(data) ? data : [];
        this.isLoading = false;
      } catch (err) {
        console.error('Error fetching inbox:', err);
        this.isLoading = false;
      }
    },
    openChat(chat) {
      // พาเข้าห้องแชทแบบระบุตัวตนลูกค้าคนนั้นๆ
      this.$router.push({
        name: 'chat',
        params: { serviceId: chat.service_id },
        query: { 
          title: chat.service_title,
          customer_id: chat.customer_id 
        }
      });
    }
  }
}
</script>