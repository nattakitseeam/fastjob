<template>
  <div class="d-flex flex-column min-vh-100">
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top py-3">
      <div class="container">
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

          <div class="d-flex align-items-center gap-2">
            <template v-if="user">
              <span class="badge bg-light text-dark border px-3 py-2 rounded-pill">
                <i class="bi bi-person-circle text-primary me-1"></i> {{ user.fullname }}
              </span>
              
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
    return { user: null }
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