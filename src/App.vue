<template>
  <div class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3 position-relative">
      <!-- เลนวิ่งไล่กันของคำว่า FastJob และตัวตุ่น 2 ตัวคนละสีตรงแถบด้านบน -->
      <div class="mole-chase-track" aria-hidden="true">
        <div class="mole-pair">
          <!-- ตัวที่ 2 (ตัววิ่งไล่หลังสุด - สีเทาอมฟ้า) -->
          <div class="mole-runner chaser">
            <svg width="46" height="36" viewBox="0 0 64 50" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle class="dust-1" cx="7" cy="41" r="2.5" fill="#CFD8DC" />
              <circle class="dust-2" cx="2" cy="37" r="1.8" fill="#ECEFF1" />
              <ellipse class="foot-back" cx="22" cy="44" rx="5" ry="3" fill="#B0BEC5" stroke="#455A64" stroke-width="1.2" />
              <ellipse class="foot-front" cx="40" cy="44" rx="5" ry="3" fill="#CFD8DC" stroke="#455A64" stroke-width="1.2" />

              <g class="mole-run-body">
                <circle cx="20" cy="19" r="4.2" fill="#546E7A" />
                <circle cx="44" cy="19" r="4.2" fill="#546E7A" />
                <path d="M15 41C15 22 22 11 33 11C44 11 50 22 50 41H15Z" fill="#78909C" />
                <path d="M15 41C15 22 22 11 33 11C26 14 21 25 21 41H15Z" fill="#546E7A" opacity="0.45" />
                <ellipse cx="26" cy="28" rx="3" ry="1.8" fill="#FF8A80" opacity="0.7" />
                <g class="mole-eyes">
                  <circle cx="30" cy="22.5" r="2.5" fill="#212121" />
                  <circle cx="30.8" cy="21.7" r="0.85" fill="#FFFFFF" />
                  <circle cx="44" cy="22.5" r="2.5" fill="#212121" />
                  <circle cx="44.8" cy="21.7" r="0.85" fill="#FFFFFF" />
                </g>
                <ellipse cx="38" cy="29" rx="8" ry="5.5" fill="#ECEFF1" />
                <line x1="25" y1="27" x2="31" y2="28.5" stroke="#37474F" stroke-width="1.2" stroke-linecap="round" />
                <line x1="25" y1="30.5" x2="31" y2="30" stroke="#37474F" stroke-width="1.2" stroke-linecap="round" />
                <line x1="49" y1="27" x2="43" y2="28.5" stroke="#37474F" stroke-width="1.2" stroke-linecap="round" />
                <line x1="49" y1="30.5" x2="43" y2="30" stroke="#37474F" stroke-width="1.2" stroke-linecap="round" />
                <ellipse cx="39.5" cy="26.5" rx="3.6" ry="2.4" fill="#FF5252" />
                <ellipse cx="40.3" cy="25.8" rx="1.1" ry="0.65" fill="#FFCDD2" />
                <path d="M36 31.2Q38.5 33.2 41 31.2" stroke="#455A64" stroke-width="1.4" stroke-linecap="round" fill="none" />
                <ellipse class="arm-swing" cx="35" cy="36.5" rx="4.5" ry="3" fill="#CFD8DC" stroke="#546E7A" stroke-width="1.1" />
              </g>
            </svg>
          </div>

          <!-- ตัวที่ 1 (ตัววิ่งตรงกลาง - สีน้ำตาลคาราเมลทอง) -->
          <div class="mole-runner leader">
            <svg width="46" height="36" viewBox="0 0 64 50" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle class="dust-1" cx="7" cy="41" r="2.5" fill="#EFEBE9" />
              <circle class="dust-2" cx="2" cy="37" r="1.8" fill="#D7CCC8" />
              <path d="M13 15C13 15 10.5 18.5 10.5 20C10.5 21.4 11.6 22.5 13 22.5C14.4 22.5 15.5 21.4 15.5 20C15.5 18.5 13 15 13 15Z" fill="#4FC3F7" />
              <ellipse class="foot-back" cx="22" cy="44" rx="5" ry="3" fill="#D7CCC8" stroke="#6D4C41" stroke-width="1.2" />
              <ellipse class="foot-front" cx="40" cy="44" rx="5" ry="3" fill="#EFEBE9" stroke="#6D4C41" stroke-width="1.2" />

              <g class="mole-run-body">
                <circle cx="20" cy="19" r="4.2" fill="#A66D3B" />
                <circle cx="44" cy="19" r="4.2" fill="#A66D3B" />
                <path d="M15 41C15 22 22 11 33 11C44 11 50 22 50 41H15Z" fill="#C88A55" />
                <path d="M15 41C15 22 22 11 33 11C26 14 21 25 21 41H15Z" fill="#A66D3B" opacity="0.45" />
                <ellipse cx="26" cy="28" rx="3" ry="1.8" fill="#FF8A80" opacity="0.7" />
                <g class="mole-eyes">
                  <circle cx="30" cy="22.5" r="2.5" fill="#212121" />
                  <circle cx="30.8" cy="21.7" r="0.85" fill="#FFFFFF" />
                  <circle cx="44" cy="22.5" r="2.5" fill="#212121" />
                  <circle cx="44.8" cy="21.7" r="0.85" fill="#FFFFFF" />
                </g>
                <ellipse cx="38" cy="29" rx="8" ry="5.5" fill="#FFF3E0" />
                <line x1="25" y1="27" x2="31" y2="28.5" stroke="#5D4037" stroke-width="1.2" stroke-linecap="round" />
                <line x1="25" y1="30.5" x2="31" y2="30" stroke="#5D4037" stroke-width="1.2" stroke-linecap="round" />
                <line x1="49" y1="27" x2="43" y2="28.5" stroke="#5D4037" stroke-width="1.2" stroke-linecap="round" />
                <line x1="49" y1="30.5" x2="43" y2="30" stroke="#5D4037" stroke-width="1.2" stroke-linecap="round" />
                <ellipse cx="39.5" cy="26.5" rx="3.6" ry="2.4" fill="#FF7043" />
                <ellipse cx="40.3" cy="25.8" rx="1.1" ry="0.65" fill="#FFCCBC" />
                <path d="M36 31.2Q38.5 33.2 41 31.2" stroke="#6D4C41" stroke-width="1.4" stroke-linecap="round" fill="none" />
                <ellipse class="arm-swing" cx="35" cy="36.5" rx="4.5" ry="3" fill="#FFF3E0" stroke="#8D6E63" stroke-width="1.1" />
              </g>
            </svg>
          </div>

          <!-- คำว่า FastJob ตัวอักษรเว้นระยะ วิ่งนำหน้าตัวตุ่น -->
          <div class="running-fastjob">
            <span
              v-for="(ch, idx) in runningLetters"
              :key="idx"
              :class="['run-char', idx < 4 ? 'text-primary' : 'text-warning-dark']"
              :style="{ animationDelay: `${idx * 0.06}s` }"
            >
              {{ ch }}
            </span>
          </div>
        </div>
      </div>

      <div class="container position-relative" style="z-index: 2;">
        <router-link class="navbar-brand fw-bold fs-4 text-primary d-flex align-items-center" to="/">
          <i class="bi bi-lightning-charge-fill text-warning me-1"></i> FastJob
        </router-link>
        
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
          <ul class="navbar-nav me-auto mb-2 mb-lg-0">
            <li class="nav-item">
              <router-link class="nav-link fw-medium" to="/">ค้นหาฟรีแลนซ์</router-link>
            </li>
          </ul>

          <div class="d-flex align-items-center gap-2 flex-wrap">
            <template v-if="user">
              <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                <i class="bi bi-person-circle text-primary me-1"></i> {{ user.fullname }}
              </span>

              <!-- ปุ่มกล่องข้อความ แสดงทั้งลูกค้าและฟรีแลนซ์ -->
              <router-link
                v-if="user.role === 'customer' || user.role === 'freelance'"
                to="/inbox"
                class="btn btn-outline-primary rounded-pill px-3"
              >
                <i class="bi bi-chat-dots-fill me-1"></i> กล่องข้อความ
              </router-link>
              
              <router-link v-if="user.role === 'freelance'" to="/freelance" class="btn btn-primary rounded-pill px-3">
                <i class="bi bi-plus-circle me-1"></i> โพสต์งาน / จัดการร้าน
              </router-link>
              
              <router-link v-if="user.role === 'admin'" to="/admin" class="btn btn-info text-white rounded-pill px-3">
                <i class="bi bi-shield-check me-1"></i> ระบบแอดมิน
              </router-link>
              
              <button @click="logout" class="btn btn-outline-danger rounded-pill px-3">
                ออกจากระบบ
              </button>
            </template>

            <template v-else>
              <router-link class="btn btn-outline-primary rounded-pill px-4 fw-medium" to="/login">
                เข้าสู่ระบบ / สมัครสมาชิก
              </router-link>
            </template>
          </div>
        </div>
      </div>
    </nav>

    <!-- Main Content -->
    <main class="flex-grow-1">
      <router-view @login-success="checkUser" />
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-white py-4 mt-5">
      <div class="container text-center">
        <p class="mb-1 fw-bold"><i class="bi bi-lightning-charge-fill text-warning"></i> FastJob Marketplace</p>
        <small class="text-secondary">แพลตฟอร์มรวมฟรีแลนซ์คุณภาพ ปลอดภัย มั่นใจได้ทุกการจ้างงาน</small>
      </div>
    </footer>
  </div>
</template>

<script>
export default {
  data() {
    return {
      user: null,
      runningLetters: ['F', 'a', 's', 't', 'J', 'o', 'b']
    }
  },
  watch: {
    $route() {
      this.checkUser();
    }
  },
  mounted() {
    this.checkUser();
  },
  methods: {
    checkUser() {
      const u = localStorage.getItem('user');
      this.user = u ? JSON.parse(u) : null;
    },
    logout() {
      localStorage.removeItem('user');
      this.user = null;
      this.$router.push('/login');
    }
  }
}
</script>

<style scoped>
/* เลนวิ่งของคำว่า FastJob และตัวตุ่น 2 ตัวบน Navbar */
.mole-chase-track {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  height: 38px;
  overflow: hidden;
  pointer-events: none;
  z-index: 1;
}

.mole-pair {
  position: absolute;
  bottom: -2px;
  display: flex;
  align-items: flex-end;
  gap: 14px;
  animation: chaseBackAndForth 20s linear infinite;
}

/* คำว่า FastJob แบบเว้นตัวอักษรวิ่งนำหน้า */
.running-fastjob {
  display: inline-flex;
  align-items: baseline;
  gap: 7px;
  margin-left: 8px;
  padding-bottom: 5px;
  animation: counterFlipText 20s linear infinite;
}

.run-char {
  display: inline-block;
  font-size: 1.15rem;
  font-weight: 800;
  line-height: 1;
  text-shadow: 0 1px 2px rgba(0, 0, 0, 0.12);
  animation: charScamper 0.36s ease-in-out infinite;
}

.text-warning-dark {
  color: #f59e0b;
}

.mole-runner.leader .mole-run-body {
  animation: moleBounce 0.32s ease-in-out infinite;
}

.mole-runner.chaser .mole-run-body {
  animation: moleBounce 0.28s ease-in-out infinite 0.12s;
}

.foot-front {
  transform-origin: 40px 44px;
  animation: stepFront 0.3s ease-in-out infinite;
}

.foot-back {
  transform-origin: 22px 44px;
  animation: stepBack 0.3s ease-in-out infinite;
}

.arm-swing {
  transform-origin: 35px 36.5px;
  animation: swingArm 0.3s ease-in-out infinite;
}

.mole-eyes {
  transform-origin: 37px 22.5px;
  animation: blinkRunner 4.5s infinite;
}

.dust-1 {
  animation: puffDust 0.6s ease-out infinite;
}

.dust-2 {
  animation: puffDust 0.6s ease-out infinite 0.3s;
}

/* วิ่งไล่จากซ้ายไปขวา แล้วกลับตัววิ่งไล่จากขวาไปซ้าย */
@keyframes chaseBackAndForth {
  0% {
    left: -270px;
    transform: scaleX(1);
  }
  48% {
    left: calc(100% + 20px);
    transform: scaleX(1);
  }
  50% {
    left: calc(100% + 20px);
    transform: scaleX(-1);
  }
  98% {
    left: -270px;
    transform: scaleX(-1);
  }
  100% {
    left: -270px;
    transform: scaleX(1);
  }
}

/* กลับด้านข้อความ FastJob ตอนวิ่งขากลับไม่ให้ตัวหนังสือกลับหัวซ้ายขวา */
@keyframes counterFlipText {
  0%, 48% {
    transform: scaleX(1);
  }
  50%, 98% {
    transform: scaleX(-1);
  }
  100% {
    transform: scaleX(1);
  }
}

/* ตัวอักษรเด้งสลับจังหวะเหมือนกำลังวิ่งหนี */
@keyframes charScamper {
  0%, 100% {
    transform: translateY(0) rotate(0deg);
  }
  50% {
    transform: translateY(-4px) rotate(6deg);
  }
}

@keyframes moleBounce {
  0%, 100% {
    transform: translateY(0) rotate(2deg);
  }
  50% {
    transform: translateY(-3.5px) rotate(-1deg);
  }
}

@keyframes stepFront {
  0%, 100% {
    transform: translateX(0) translateY(0);
  }
  50% {
    transform: translateX(4px) translateY(-2px);
  }
}

@keyframes stepBack {
  0%, 100% {
    transform: translateX(0) translateY(-2px);
  }
  50% {
    transform: translateX(-4px) translateY(0);
  }
}

@keyframes swingArm {
  0%, 100% {
    transform: rotate(-12deg);
  }
  50% {
    transform: rotate(15deg);
  }
}

@keyframes blinkRunner {
  0%, 93%, 97%, 100% {
    transform: scaleY(1);
  }
  95%, 99% {
    transform: scaleY(0.1);
  }
}

@keyframes puffDust {
  0% {
    opacity: 0.85;
    transform: translate(0, 0) scale(0.8);
  }
  100% {
    opacity: 0;
    transform: translate(-8px, -5px) scale(1.5);
  }
}
</style>