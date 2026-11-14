<template>
  <div>
    <!-- Hero Banner สไตล์ Fastwork -->
    <div class="bg-primary text-white py-5 mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #003d99 100%);">
      <div class="container py-3 text-center">
        <!-- ข้อความหลักค้างไว้ -->
        <h1 class="fw-bold display-4 mb-2">หาผู้ช่วย</h1>
        
        <!-- ข้อความวิ่งพิมพ์ทีละตัววน 4 รายการ -->
        <div class="display-5 fw-bold text-warning mb-3 typing-line">
          <span>{{ displayedWord }}</span><span class="typing-cursor">|</span>
        </div>

        <p class="lead mb-4 text-white-50">รวมผู้เชี่ยวชาญกว่า 100+ สาขาอาชีพ พร้อมดูแลคุณทุกขั้นตอน</p>
        
        <!-- ช่องค้นหา -->
        <div class="row justify-content-center mt-4">
          <div class="col-md-7">
            <div class="input-group input-group-lg shadow search-group">
              <span class="input-group-text bg-white border-0"><i class="bi bi-search text-primary"></i></span>
              <input v-model="searchQuery" type="text" class="form-control border-0" placeholder="ค้นหางานที่ต้องการ เช่น ออกแบบโลโก้, ตัดต่อวิดีโอ, ทำเว็บไซต์...">
              <button class="btn btn-warning fw-bold px-4 text-dark position-relative search-btn" type="button">
                <!-- ตัวตุ่นน้อยเกาะตรงคำว่าค้นหา -->
                <div class="mole-mascot" aria-hidden="true">
                  <svg width="48" height="38" viewBox="0 0 64 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g class="mole-body">
                      <circle cx="17" cy="21" r="4.5" fill="#5D4037"/>
                      <circle cx="47" cy="21" r="4.5" fill="#5D4037"/>
                      <path d="M14 42C14 23 21 12 32 12C43 12 50 23 50 42H14Z" fill="#6D4C41"/>
                      <path d="M32 12C43 12 50 23 50 42H43C43 26 38 15 32 12Z" fill="#5D4037" opacity="0.45"/>
                      <ellipse cx="20" cy="29" rx="3.2" ry="2" fill="#FF8A80" opacity="0.65"/>
                      <ellipse cx="44" cy="29" rx="3.2" ry="2" fill="#FF8A80" opacity="0.65"/>
                      <g class="mole-eyes">
                        <circle cx="23" cy="24" r="2.7" fill="#212121"/>
                        <circle cx="23.8" cy="23.2" r="0.9" fill="#FFFFFF"/>
                        <circle cx="41" cy="24" r="2.7" fill="#212121"/>
                        <circle cx="41.8" cy="23.2" r="0.9" fill="#FFFFFF"/>
                      </g>
                      <ellipse cx="32" cy="30" rx="8.5" ry="6" fill="#D7CCC8"/>
                      <line x1="15" y1="28" x2="22" y2="29.5" stroke="#3E2723" stroke-width="1.3" stroke-linecap="round"/>
                      <line x1="15" y1="32" x2="22" y2="31.5" stroke="#3E2723" stroke-width="1.3" stroke-linecap="round"/>
                      <line x1="49" y1="28" x2="42" y2="29.5" stroke="#3E2723" stroke-width="1.3" stroke-linecap="round"/>
                      <line x1="49" y1="32" x2="42" y2="31.5" stroke="#3E2723" stroke-width="1.3" stroke-linecap="round"/>
                      <ellipse class="mole-nose" cx="32" cy="27.5" rx="3.8" ry="2.6" fill="#FF7043"/>
                      <ellipse cx="33" cy="26.8" rx="1.2" ry="0.7" fill="#FFCCBC"/>
                      <path d="M29.5 32.5Q32 34.5 34.5 32.5" stroke="#5D4037" stroke-width="1.4" stroke-linecap="round" fill="none"/>
                    </g>
                    <g class="mole-paws">
                      <ellipse cx="20" cy="42.5" rx="5.5" ry="3.8" fill="#D7CCC8" stroke="#5D4037" stroke-width="1.2"/>
                      <line x1="18" y1="41" x2="18" y2="44.5" stroke="#8D6E63" stroke-width="1.1" stroke-linecap="round"/>
                      <line x1="20.5" y1="41" x2="20.5" y2="44.5" stroke="#8D6E63" stroke-width="1.1" stroke-linecap="round"/>
                      <ellipse cx="44" cy="42.5" rx="5.5" ry="3.8" fill="#D7CCC8" stroke="#5D4037" stroke-width="1.2"/>
                      <line x1="43.5" y1="41" x2="43.5" y2="44.5" stroke="#8D6E63" stroke-width="1.1" stroke-linecap="round"/>
                      <line x1="46" y1="41" x2="46" y2="44.5" stroke="#8D6E63" stroke-width="1.1" stroke-linecap="round"/>
                    </g>
                  </svg>
                </div>
                ค้นหา
              </button>
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
              <img :src="fixImageUrl(item.image_url) || 'https://placehold.co/400x250/e9ecef/495057?text=FastJob+Service'" class="card-img-top" style="height: 170px; object-fit: cover;">
              <span class="badge bg-dark bg-opacity-75 position-absolute top-0 end-0 m-2">{{ item.category }}</span>
            </div>

            <div class="card-body d-flex flex-column">
              <!-- ข้อมูลฟรีแลนซ์ + คะแนนรีวิว -->
              <div class="d-flex align-items-center mb-2">
                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 28px; height: 28px; font-size: 12px;">
                  {{ item.freelance_name ? item.freelance_name.charAt(0) : 'F' }}
                </div>
                <small class="text-muted fw-medium text-truncate">{{ item.freelance_name }}</small>
                <span class="ms-auto text-warning small fw-bold text-nowrap">
                  <i class="bi bi-star-fill"></i>
                  {{ getServiceRatingText(item.id) }}
                </span>
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

    <!-- Modal ดูรายละเอียดงาน + รีวิวฟรีแลนซ์ -->
    <div class="modal fade" id="serviceModal" tabindex="-1">
      <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content border-0 shadow" v-if="selectedItem">
          <div class="modal-header border-0 pb-2">
            <span class="badge bg-primary">{{ selectedItem.category }}</span>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
          </div>
          <div class="modal-body pt-0">
            <img :src="fixImageUrl(selectedItem.image_url) || 'https://placehold.co/800x400/e9ecef/495057?text=FastJob+Service'" class="w-100 rounded mb-3" style="max-height: 320px; object-fit: cover;">
            
            <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
              <div>
                <h4 class="fw-bold mb-1">{{ selectedItem.title }}</h4>
                <p class="text-muted mb-0">
                  <i class="bi bi-person-badge text-primary"></i> ผู้รับจ้าง: <b>{{ selectedItem.freelance_name }}</b>
                </p>
              </div>
              <!-- สรุปคะแนนรีวิวใน Modal -->
              <div class="bg-warning bg-opacity-10 border border-warning rounded-3 px-3 py-2 text-center">
                <div class="fw-bold text-warning fs-5">
                  <i class="bi bi-star-fill"></i> {{ averageModalRating }}
                </div>
                <small class="text-muted">{{ serviceReviews.length }} รีวิว</small>
              </div>
            </div>

            <div class="bg-light p-3 rounded mb-3">
              <h6 class="fw-bold">รายละเอียดบริการ:</h6>
              <p class="mb-0" style="white-space: pre-line;">{{ selectedItem.description }}</p>
            </div>

            <div class="d-flex justify-content-between align-items-center bg-primary bg-opacity-10 p-3 rounded mb-4">
              <div>
                <span class="text-muted d-block small">ค่าบริการเริ่มต้น</span>
                <span class="text-primary fw-bold fs-3">฿{{ Number(selectedItem.price).toLocaleString() }}</span>
              </div>
              <button @click="confirmHire(selectedItem)" type="button" class="btn btn-primary btn-lg px-4 rounded-pill shadow-sm">
                <i class="bi bi-chat-dots-fill me-2"></i> สนใจจ้างงานนี้ (คุยแชท)
              </button>
            </div>

            <hr class="my-4">

            <!-- ส่วนรีวิวของฟรีแลนซ์ -->
            <div>
              <h5 class="fw-bold mb-3">
                <i class="bi bi-star-fill text-warning me-1"></i>
                รีวิวจากผู้จ้างงาน ({{ serviceReviews.length }})
              </h5>

              <!-- ฟอร์มเขียนรีวิว (แสดงเมื่อเข้าสู่ระบบเป็นลูกค้า) -->
              <div v-if="currentUser && currentUser.role === 'customer'" class="card bg-light border-0 rounded-3 p-3 mb-4">
                <h6 class="fw-bold mb-2">ให้คะแนนและเขียนรีวิวบริการนี้</h6>
                <div class="mb-2 d-flex align-items-center gap-1">
                  <span class="small text-muted me-2">คะแนนความพึงพอใจ:</span>
                  <button
                    v-for="star in 5"
                    :key="star"
                    type="button"
                    @click="reviewForm.rating = star"
                    class="btn btn-link p-0 border-0 fs-4 text-decoration-none"
                  >
                    <i :class="['bi', star <= reviewForm.rating ? 'bi-star-fill text-warning' : 'bi-star text-secondary']"></i>
                  </button>
                  <span class="ms-2 badge bg-warning text-dark">{{ reviewForm.rating }} / 5 ดาว</span>
                </div>
                <div class="mb-2">
                  <textarea
                    v-model="reviewForm.comment"
                    class="form-control"
                    rows="2"
                    placeholder="เพิ่มประสบการณ์ความพึงพอใจของคุณ"
                  ></textarea>
                </div>
                <div class="text-end">
                  <button
                    @click="submitReview"
                    type="button"
                    class="btn btn-warning fw-bold text-dark rounded-pill px-4"
                    :disabled="isSubmittingReview"
                  >
                    <span v-if="isSubmittingReview" class="spinner-border spinner-border-sm me-1"></span>
                    ส่งรีวิว
                  </button>
                </div>
              </div>

              <!-- กรณียังไม่ล็อกอิน -->
              <div v-else-if="!currentUser" class="alert alert-light border small text-muted mb-3">
                <i class="bi bi-info-circle me-1"></i> กรุณา <router-link to="/login" @click="closeModalCleanly">เข้าสู่ระบบ</router-link> ในฐานะผู้จ้างงานเพื่อเขียนรีวิวให้ฟรีแลนซ์
              </div>

              <!-- รายการรีวิว -->
              <div v-if="isLoadingReviews" class="text-center py-3">
                <div class="spinner-border spinner-border-sm text-primary"></div>
                <span class="ms-2 small text-muted">กำลังโหลดรีวิว...</span>
              </div>

              <div v-else-if="serviceReviews.length === 0" class="text-center py-4 bg-light rounded-3 text-muted">
                <i class="bi bi-chat-square-heart fs-3 d-block mb-1"></i>
                <small>ยังไม่มีรีวิวสำหรับบริการนี้ เป็นคนแรกที่รีวิวเลย!</small>
              </div>

              <div v-else class="d-flex flex-column gap-3">
                <div v-for="rev in serviceReviews" :key="rev.id" class="border rounded-3 p-3 bg-white shadow-sm">
                  <div class="d-flex justify-content-between align-items-center mb-1">
                    <div class="d-flex align-items-center gap-2">
                      <div class="bg-secondary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 30px; height: 30px; font-size: 13px;">
                        {{ rev.customer_name ? rev.customer_name.charAt(0) : 'U' }}
                      </div>
                      <div>
                        <div class="fw-bold small">{{ rev.customer_name || 'ผู้ใช้งาน FastJob' }}</div>
                        <div class="text-warning small">
                          <i v-for="s in 5" :key="s" :class="['bi', s <= Number(rev.rating) ? 'bi-star-fill' : 'bi-star']"></i>
                        </div>
                      </div>
                    </div>
                    <small class="text-muted">{{ formatReviewDate(rev.created_at) }}</small>
                  </div>
                  <p class="mb-0 mt-2 text-dark small" style="white-space: pre-line;">{{ rev.comment }}</p>
                </div>
              </div>
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
      // ข้อมูลสำหรับเอฟเฟกต์พิมพ์ทีละตัว
      typingWords: ['บัญชี', 'เรียนพิเศษ', 'ออกแบบกราฟฟิก', 'ช่าง'],
      currentWordIndex: 0,
      currentCharIndex: 0,
      displayedWord: '',
      isDeleting: false,
      typingTimer: null,

      // ข้อมูลรายการบริการและรีวิว
      currentUser: null,
      services: [],
      reviewSummary: {},
      serviceReviews: [],
      isLoading: true,
      isLoadingReviews: false,
      isSubmittingReview: false,
      searchQuery: '',
      selectedCategory: '',
      categories: ['กราฟิก & ดีไซน์', 'ตัดต่อ & วิดีโอ', 'โปรแกรม & ไอที', 'การตลาด & โฆษณา', 'เขียนและแปลภาษา'],
      selectedItem: null,
      bsModal: null,
      reviewForm: {
        rating: 5,
        comment: ''
      }
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
    },
    averageModalRating() {
      if (this.serviceReviews.length === 0) return 'ใหม่';
      const sum = this.serviceReviews.reduce((acc, r) => acc + Number(r.rating), 0);
      return (sum / this.serviceReviews.length).toFixed(1);
    }
  },
  mounted() {
    const u = localStorage.getItem('user');
    this.currentUser = u ? JSON.parse(u) : null;

    this.startTypingEffect();
    this.fetchServices();
    this.fetchReviewSummary();
  },
  unmounted() {
    if (this.typingTimer) {
      clearTimeout(this.typingTimer);
    }
  },
  methods: {
    getGraphemes(text) {
      if (typeof Intl !== 'undefined' && Intl.Segmenter) {
        const segmenter = new Intl.Segmenter('th', { granularity: 'grapheme' });
        return Array.from(segmenter.segment(text), s => s.segment);
      }
      return Array.from(text);
    },
    startTypingEffect() {
      const currentFullWord = this.typingWords[this.currentWordIndex];
      const chars = this.getGraphemes(currentFullWord);

      if (!this.isDeleting) {
        this.currentCharIndex++;
        this.displayedWord = chars.slice(0, this.currentCharIndex).join('');

        if (this.currentCharIndex === chars.length) {
          this.isDeleting = true;
          this.typingTimer = setTimeout(() => this.startTypingEffect(), 1500);
          return;
        }
        this.typingTimer = setTimeout(() => this.startTypingEffect(), 120);
      } else {
        this.currentCharIndex--;
        this.displayedWord = chars.slice(0, this.currentCharIndex).join('');

        if (this.currentCharIndex === 0) {
          this.isDeleting = false;
          this.currentWordIndex = (this.currentWordIndex + 1) % this.typingWords.length;
          this.typingTimer = setTimeout(() => this.startTypingEffect(), 300);
          return;
        }
        this.typingTimer = setTimeout(() => this.startTypingEffect(), 60);
      }
    },
    async fetchServices(retries = 3) {
      this.isLoading = true;
      try {
        const res = await fetch('/api/services.php?public=1');
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
    async fetchReviewSummary() {
      try {
        const res = await fetch('/api/reviews.php');
        if (res.ok) {
          const data = await res.json();
          this.reviewSummary = data && typeof data === 'object' ? data : {};
        }
      } catch (err) {
        console.error('โหลดสรุปคะแนนรีวิวไม่สำเร็จ:', err);
      }
    },
    getServiceRatingText(serviceId) {
      const info = this.reviewSummary[serviceId];
      if (!info || info.total_reviews === 0) return 'ใหม่';
      return `${Number(info.avg_rating).toFixed(1)} (${info.total_reviews})`;
    },
    async fetchServiceReviews(serviceId) {
      this.isLoadingReviews = true;
      try {
        const res = await fetch(`/api/reviews.php?service_id=${serviceId}`);
        const data = await res.json();
        this.serviceReviews = Array.isArray(data) ? data : [];
      } catch (err) {
        console.error('โหลดรีวิวไม่สำเร็จ:', err);
        this.serviceReviews = [];
      } finally {
        this.isLoadingReviews = false;
      }
    },
    async submitReview() {
      if (!this.currentUser || this.currentUser.role !== 'customer') {
        alert('กรุณาเข้าสู่ระบบในฐานะผู้จ้างงานก่อนส่งรีวิว');
        return;
      }
      if (!this.reviewForm.comment.trim()) {
        alert('กรุณาพิมพ์ข้อความรีวิวก่อนกดส่ง');
        return;
      }

      this.isSubmittingReview = true;
      try {
        const res = await fetch('/api/reviews.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({
            service_id: this.selectedItem.id,
            freelance_id: this.selectedItem.freelance_id || 0,
            customer_id: this.currentUser.id,
            rating: this.reviewForm.rating,
            comment: this.reviewForm.comment.trim()
          })
        });
        const data = await res.json();
        if (data.status === 'success') {
          this.reviewForm = { rating: 5, comment: '' };
          await this.fetchServiceReviews(this.selectedItem.id);
          await this.fetchReviewSummary();
        } else {
          alert(data.message || 'ส่งรีวิวไม่สำเร็จ');
        }
      } catch (err) {
        alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้');
      } finally {
        this.isSubmittingReview = false;
      }
    },
    formatReviewDate(dateStr) {
      if (!dateStr) return '';
      const d = new Date(dateStr.replace(' ', 'T'));
      if (isNaN(d.getTime())) return dateStr;
      return d.toLocaleDateString('th-TH', { year: 'numeric', month: 'short', day: 'numeric' });
    },
    fixImageUrl(url) {
      if (!url) return '';
      return url.replace('http://localhost/fastjob/freelance_api', '/api');
    },
    openDetail(item) {
      this.selectedItem = item;
      this.serviceReviews = [];
      this.reviewForm = { rating: 5, comment: '' };
      this.fetchServiceReviews(item.id);

      const modalElement = document.getElementById('serviceModal');
      this.bsModal = new window.bootstrap.Modal(modalElement);
      this.bsModal.show();
    },
    confirmHire(item) {
      const u = localStorage.getItem('user');
      if (!u) {
        this.closeModalCleanly();
        alert('กรุณาเข้าสู่ระบบก่อนติดต่อจ้างงาน');
        this.$router.push('/login');
        return;
      }
      const currentUser = JSON.parse(u);

      if (currentUser.role !== 'customer') {
        alert('เฉพาะบัญชีลูกค้าที่สามารถกดติดต่อจ้างงานได้');
        return;
      }
      
      this.closeModalCleanly();

      this.$router.push({
        name: 'chat',
        params: {
          serviceId: item.id,
          customerId: currentUser.id
        }
      });
    },
    closeModalCleanly() {
      if (this.bsModal) {
        this.bsModal.hide();
      }
      document.querySelectorAll('.modal-backdrop').forEach(el => el.remove());
      document.body.classList.remove('modal-open');
      document.body.style.removeProperty('overflow');
      document.body.style.removeProperty('padding-right');
    }
  }
}
</script>

<style scoped>
.typing-line {
  min-height: 3.8rem;
  display: flex;
  justify-content: center;
  align-items: center;
}

.typing-cursor {
  display: inline-block;
  margin-left: 4px;
  font-weight: 400;
  color: #ffffff;
  animation: blinkCursor 0.75s infinite;
}

@keyframes blinkCursor {
  0%, 49% {
    opacity: 1;
  }
  50%, 100% {
    opacity: 0;
  }
}

/* สไตล์และแอนิเมชันตัวตุ่นเกาะปุ่มค้นหา */
.search-group {
  overflow: visible !important;
}

.search-btn {
  overflow: visible !important;
  z-index: 3;
}

.mole-mascot {
  position: absolute;
  top: -31px;
  left: 50%;
  transform: translateX(-50%);
  pointer-events: none;
  z-index: 5;
  filter: drop-shadow(0 2px 3px rgba(0, 0, 0, 0.25));
  transition: transform 0.25s ease;
}

.mole-body {
  transform-origin: 32px 42px;
  animation: moleWiggle 2.8s ease-in-out infinite;
}

.mole-eyes {
  transform-origin: 32px 24px;
  animation: moleBlink 4s infinite;
}

.mole-nose {
  transform-origin: 32px 27.5px;
  animation: noseTwitch 1.8s ease-in-out infinite;
}

.search-btn:hover .mole-mascot {
  transform: translateX(-50%) translateY(-4px);
}

@keyframes moleWiggle {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  25% {
    transform: translateY(-2.5px) rotate(-4deg);
  }
  50% {
    transform: translateY(1.5px) rotate(0deg);
  }
  75% {
    transform: translateY(-2.5px) rotate(4deg);
  }
}

@keyframes moleBlink {
  0%, 92%, 96%, 100% {
    transform: scaleY(1);
  }
  94%, 98% {
    transform: scaleY(0.1);
  }
}

@keyframes noseTwitch {
  0%, 100% {
    transform: scale(1);
  }
  50% {
    transform: scale(1.12, 0.92);
  }
}

.service-card {
  transition: transform 0.2s ease, box-shadow 0.2s ease;
}
.service-card:hover {
  transform: translateY(-5px);
  box-shadow: 0 10px 20px rgba(0,0,0,0.12) !important;
}
</style>