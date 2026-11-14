<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <!-- Header แชท -->
          <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0 fw-bold"><i class="bi bi-chat-dots-fill me-2"></i>ห้องสนทนาระหว่างลูกค้ากับผู้รับจ้าง</h5>
              <small class="text-white-50">บริการ: {{ serviceTitle }}</small>
            </div>
            <button @click="$router.go(-1)" class="btn btn-sm btn-light rounded-pill px-3">ย้อนกลับ</button>
          </div>

          <!-- กล่องข้อความแชท -->
          <div class="card-body bg-light p-4" style="height: 450px; overflow-y: auto;" ref="chatBox">
            <div v-if="messages.length === 0" class="text-center text-muted py-5">
              <i class="bi bi-chat-square-text fs-1"></i>
              <p class="mt-2">ยังไม่มีข้อความสนทนา เริ่มทักทายพูดคุยรายละเอียดงานกันได้เลย</p>
            </div>

            <div v-for="msg in messages" :key="msg.id" :class="['mb-3 d-flex', msg.sender_id == currentUser.id ? 'justify-content-end' : 'justify-content-start']">
              <div :class="['p-3 rounded-4 shadow-sm', msg.sender_id == currentUser.id ? 'bg-primary text-white' : 'bg-white text-dark border']" style="max-width: 75%;">
                <small class="d-block fw-bold mb-1" :class="msg.sender_id == currentUser.id ? 'text-white-50' : 'text-primary'">
                  {{ msg.sender_name }}
                </small>
                <p class="mb-1" style="word-break: break-word; white-space: pre-line;">{{ msg.message }}</p>
                <div class="text-end">
                  <small style="font-size: 10px;" :class="msg.sender_id == currentUser.id ? 'text-white-50' : 'text-muted'">{{ msg.created_at }}</small>
                </div>
              </div>
            </div>
          </div>

          <!-- ฟอร์มพิมพ์ข้อความ -->
          <div class="card-footer bg-white p-3 border-top">
            <form @submit.prevent="sendMessage" class="input-group">
              <input v-model="newMessage" type="text" class="form-control rounded-pill px-3" placeholder="พิมพ์ข้อความรายละเอียดงาน..." required>
              <button type="submit" class="btn btn-primary rounded-pill px-4 ms-2 fw-bold">
                <i class="bi bi-send-fill me-1"></i> ส่ง
              </button>
            </form>
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
      serviceId: null,
      customerId: null,
      serviceTitle: '',
      currentUser: {},
      messages: [],
      newMessage: '',
      pollInterval: null
    }
  },
  mounted() {
    const u = localStorage.getItem('user');
    if (!u) {
      this.$router.push('/login');
      return;
    }
    this.currentUser = JSON.parse(u);
    this.serviceId = this.$route.params.serviceId;
    
    // กำหนด customerId แยกตามสิทธิ์ผู้ใช้งาน
    if (this.currentUser.role === 'customer') {
      this.customerId = this.currentUser.id;
    } else {
      this.customerId = this.$route.query.customer_id;
    }

    this.serviceTitle = this.$route.query.title || 'รายละเอียดงาน';

    this.fetchMessages();
    // ทำระบบ Auto-Refresh ข้อความแชททุกๆ 3 วินาที
    this.pollInterval = setInterval(() => {
      this.fetchMessages(false);
    }, 3000);
  },
  unmounted() {
    clearInterval(this.pollInterval);
  },
  methods: {
    async fetchMessages(scrollToBottomFlag = true) {
      if (!this.customerId) return;
      try {
        const res = await fetch(`http://localhost/fastjob/freelance_api/chat.php?service_id=${this.serviceId}&customer_id=${this.customerId}`);
        const data = await res.json();
        this.messages = Array.isArray(data) ? data : [];
        
        if (this.messages.length > 0) {
          const lastMsg = this.messages[this.messages.length - 1];
          localStorage.setItem('last_read_msg_' + this.serviceId + '_' + this.customerId, lastMsg.id);
        }

        if (scrollToBottomFlag) {
          this.scrollToBottom();
        }
      } catch (err) {
        console.error('Error fetching chats:', err);
      }
    },
    async sendMessage() {
      if (!this.newMessage.trim()) return;

      try {
        const res = await fetch('http://localhost/fastjob/freelance_api/chat.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            service_id: this.serviceId,
            customer_id: this.customerId,
            sender_id: this.currentUser.id,
            message: this.newMessage
          })
        });
        const data = await res.json();
        if (data.status === 'success') {
          this.newMessage = '';
          this.fetchMessages(true);
        } else {
          alert('ส่งข้อความไม่สำเร็จ: ' + (data.message || ''));
        }
      } catch (err) {
        alert('เกิดข้อผิดพลาดในการเชื่อมต่อเซิร์ฟเวอร์');
      }
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const box = this.$refs.chatBox;
        if (box) {
          box.scrollTop = box.scrollHeight;
        }
      });
    }
  }
}
</script>