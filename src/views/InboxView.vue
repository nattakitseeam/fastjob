<template>
  <div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <div>
        <h3 class="fw-bold mb-0"><i class="bi bi-chat-square-text-fill text-primary me-2"></i>กล่องข้อความ (Inbox)</h3>
        <small class="text-muted">
          {{ user.role === 'freelance' ? 'รายการลูกค้าที่ติดต่อสอบถามบริการของคุณ' : 'รายการบริการที่คุณติดต่อพูดคุยกับผู้รับจ้าง' }}
        </small>
      </div>
      <button @click="fetchInbox" class="btn btn-sm btn-outline-primary rounded-pill px-3">
        <i class="bi bi-arrow-clockwise me-1"></i> รีเฟรช
      </button>
    </div>

    <div v-if="isLoading" class="text-center py-5">
      <div class="spinner-border text-primary" role="status"></div>
      <p class="mt-2 text-muted">กำลังโหลดรายการแชท...</p>
    </div>

    <div v-else-if="chatList.length > 0" class="row g-3">
      <div class="col-12" v-for="chat in chatList" :key="chat.service_id + '-' + chat.customer_id">
        <div class="card border-0 shadow-sm rounded-4 p-3 chat-item-card" @click="openChat(chat)" style="cursor: pointer;">
          <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
              <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center me-3 fs-4" style="width: 55px; height: 55px;">
                <i class="bi bi-person-fill"></i>
              </div>
              <div>
                <h5 class="fw-bold mb-1 text-dark">
                  {{ user.role === 'freelance' ? 'ลูกค้า: ' : 'ผู้รับจ้าง: ' }}{{ chat.partner_name }}
                </h5>
                <p class="text-muted mb-1 small">
                  <i class="bi bi-briefcase-fill text-primary me-1"></i> บริการ: <b>{{ chat.service_title }}</b>
                </p>
                <p class="text-secondary mb-0 text-truncate" style="max-width: 500px;">
                  <small>ข้อความล่าสุด: {{ chat.last_message }}</small>
                </p>
              </div>
            </div>
            <div class="text-end">
              <small class="d-block text-muted mb-2" style="font-size: 11px;">{{ chat.last_time }}</small>
              <button class="btn btn-primary btn-sm rounded-pill px-4 shadow-sm">
                <i class="bi bi-chat-dots-fill me-1"></i> เปิดแชท
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <div v-else class="text-center py-5 bg-white rounded-4 shadow-sm">
      <i class="bi bi-inbox fs-1 text-muted"></i>
      <p class="mt-2 text-muted">ยังไม่มีรายการสนทนาในขณะนี้</p>
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
        const res = await fetch(`/api/chat.php?action=inbox&user_id=${this.user.id}&role=${this.user.role}`);
        const data = await res.json();
        this.chatList = Array.isArray(data) ? data : [];
      } catch (err) {
        console.error('Error fetching inbox:', err);
      } finally {
        this.isLoading = false;
      }
    },
    openChat(chat) {
      this.$router.push({
        name: 'chat',
        params: {
          serviceId: chat.service_id,
          customerId: chat.customer_id
        }
      });
    }
  }
}
</script>

<style scoped>
.chat-item-card {
  transition: transform 0.15s ease, box-shadow 0.15s ease;
}
.chat-item-card:hover {
  transform: translateY(-2px);
  box-shadow: 0 6px 16px rgba(0, 0, 0, 0.08) !important;
}
</style>