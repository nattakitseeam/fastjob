<template>
  <div class="container py-4">
    <div class="row justify-content-center">
      <div class="col-lg-8">
        <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
          <!-- Header แชท -->
          <div class="card-header bg-primary text-white py-3 d-flex justify-content-between align-items-center">
            <div>
              <h5 class="mb-0 fw-bold">
                <i class="bi bi-chat-dots-fill me-2"></i>
                <span v-if="roomInfo">
                  คุยกับ: {{ currentUser.role === 'customer' ? roomInfo.freelance_name : roomInfo.customer_name }}
                </span>
                <span v-else>ห้องสนทนา</span>
              </h5>
              <small class="text-white-50" v-if="roomInfo">
                บริการ: {{ roomInfo.service_title }} (฿{{ Number(roomInfo.service_price).toLocaleString() }})
              </small>
            </div>
            <button @click="$router.push('/inbox')" class="btn btn-sm btn-light rounded-pill px-3 fw-medium">
              <i class="bi bi-inbox me-1"></i> กล่องข้อความ
            </button>
          </div>

          <!-- กล่องข้อความแชท -->
          <div class="card-body bg-light p-4" style="height: 460px; overflow-y: auto;" ref="chatBox">
            <div v-if="isLoading" class="text-center py-5 text-muted">
              <div class="spinner-border text-primary spinner-border-sm me-2"></div> กำลังโหลดบทสนทนา...
            </div>

            <div v-else-if="messages.length === 0" class="text-center text-muted py-5">
              <i class="bi bi-chat-square-text fs-1"></i>
              <p class="mt-2 mb-0">ยังไม่มีข้อความสนทนา</p>
              <small>พิมพ์ข้อความด้านล่างเพื่อเริ่มพูดคุยรายละเอียดงานได้เลย</small>
            </div>

            <div
              v-for="msg in messages"
              :key="msg.id"
              :class="['mb-3 d-flex', Number(msg.sender_id) === Number(currentUser.id) ? 'justify-content-end' : 'justify-content-start']"
            >
              <div
                :class="[
                  'p-3 rounded-4 shadow-sm',
                  Number(msg.sender_id) === Number(currentUser.id) ? 'bg-primary text-white' : 'bg-white text-dark border'
                ]"
                style="max-width: 75%;"
              >
                <small
                  class="d-block fw-bold mb-1"
                  :class="Number(msg.sender_id) === Number(currentUser.id) ? 'text-white-50' : 'text-primary'"
                >
                  {{ msg.sender_name }}
                </small>
                <p class="mb-1" style="word-break: break-word; white-space: pre-line;">{{ msg.message }}</p>
                <div class="text-end">
                  <small
                    style="font-size: 10px;"
                    :class="Number(msg.sender_id) === Number(currentUser.id) ? 'text-white-50' : 'text-muted'"
                  >
                    {{ msg.created_at }}
                  </small>
                </div>
              </div>
            </div>
          </div>

          <!-- ฟอร์มพิมพ์ข้อความ -->
          <div class="card-footer bg-white p-3 border-top">
            <form @submit.prevent="sendMessage" class="input-group">
              <input
                v-model="newMessage"
                type="text"
                class="form-control rounded-pill px-3"
                placeholder="พิมพ์ข้อความรายละเอียดงาน..."
                :disabled="isSending"
                required
              >
              <button type="submit" class="btn btn-primary rounded-pill px-4 ms-2 fw-bold" :disabled="isSending || !newMessage.trim()">
                <i class="bi bi-send-fill me-1"></i> {{ isSending ? 'กำลังส่ง...' : 'ส่ง' }}
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
      serviceId: 0,
      customerId: 0,
      currentUser: {},
      roomInfo: null,
      messages: [],
      newMessage: '',
      isLoading: true,
      isSending: false,
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
    this.serviceId = Number(this.$route.params.serviceId);
    this.customerId = Number(this.$route.params.customerId);

    // ป้องกันลูกค้าเข้าห้องแชทของลูกค้ารายอื่น
    if (this.currentUser.role === 'customer' && Number(this.currentUser.id) !== this.customerId) {
      this.customerId = Number(this.currentUser.id);
    }

    this.fetchMessages(true);
    this.pollInterval = setInterval(() => {
      this.fetchMessages(false);
    }, 2500);
  },
  unmounted() {
    if (this.pollInterval) clearInterval(this.pollInterval);
  },
  methods: {
    async fetchMessages(scrollFlag = false) {
      if (!this.serviceId || !this.customerId) return;
      try {
        const res = await fetch(`/api/chat.php?action=messages&service_id=${this.serviceId}&customer_id=${this.customerId}`);
        const data = await res.json();

        const oldCount = this.messages.length;
        this.roomInfo = data.room || null;
        this.messages = Array.isArray(data.messages) ? data.messages : [];
        this.isLoading = false;

        if (scrollFlag || this.messages.length > oldCount) {
          this.scrollToBottom();
        }
      } catch (err) {
        this.isLoading = false;
        console.error('Fetch chat error:', err);
      }
    },
    async sendMessage() {
      const text = this.newMessage.trim();
      if (!text || this.isSending) return;

      this.isSending = true;
      try {
        const res = await fetch('/api/chat.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            service_id: this.serviceId,
            customer_id: this.customerId,
            sender_id: Number(this.currentUser.id),
            message: text
          })
        });
        const data = await res.json();

        if (data.status === 'success') {
          this.newMessage = '';
          await this.fetchMessages(true);
        } else {
          alert('ส่งข้อความไม่สำเร็จ: ' + (data.message || 'ไม่ทราบสาเหตุ'));
        }
      } catch (err) {
        console.error('Send message error:', err);
        alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาตรวจสอบ XAMPP (Apache/MySQL)');
      } finally {
        this.isSending = false;
      }
    },
    scrollToBottom() {
      this.$nextTick(() => {
        const box = this.$refs.chatBox;
        if (box) box.scrollTop = box.scrollHeight;
      });
    }
  }
}
</script>