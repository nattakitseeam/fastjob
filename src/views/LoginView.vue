<template>
  <div class="container py-5">
    <div class="row justify-content-center">
      <div class="col-md-5">
        <div class="card border-0 shadow-lg rounded-4 overflow-hidden">
          <div class="card-header bg-primary text-white text-center py-4 border-0">
            <h4 class="fw-bold mb-1"><i class="bi bi-lightning-charge-fill text-warning"></i> FastJob</h4>
            <p class="mb-0 small text-white-50">เข้าสู่ระบบเพื่อเริ่มจ้างงาน หรือเปิดรับงานฟรีแลนซ์</p>
          </div>

          <div class="card-body p-4">
            <!-- แท็บสลับ Login / Register -->
            <ul class="nav nav-pills nav-fill mb-4 bg-light p-1 rounded-pill">
              <li class="nav-item">
                <button @click="isRegister = false" :class="['nav-link rounded-pill fw-medium', !isRegister ? 'active' : '']">เข้าสู่ระบบ</button>
              </li>
              <li class="nav-item">
                <button @click="isRegister = true" :class="['nav-link rounded-pill fw-medium', isRegister ? 'active' : '']">สมัครสมาชิกใหม่</button>
              </li>
            </ul>

            <!-- ฟอร์มเข้าสู่ระบบ -->
            <form v-if="!isRegister" @submit.prevent="handleLogin">
              <div class="mb-3">
                <label class="form-label fw-medium">ชื่อผู้ใช้งาน (Username)</label>
                <input v-model="loginForm.username" type="text" class="form-control form-control-lg" required placeholder="กรอก Username">
              </div>
              <div class="mb-4">
                <label class="form-label fw-medium">รหัสผ่าน (Password)</label>
                <input v-model="loginForm.password" type="password" class="form-control form-control-lg" required placeholder="กรอกรหัสผ่าน">
              </div>
              <button type="submit" class="btn btn-primary btn-lg w-100 rounded-pill fw-bold shadow-sm">เข้าสู่ระบบ</button>
              
              <div class="mt-4 p-3 bg-light rounded small text-muted">
                <b>บัญชีทดสอบระบบ:</b><br>
                • แอดมิน: <code>admin</code> / รหัส <code>1234</code><br>
                • ฟรีแลนซ์: <code>freelance1</code> / รหัส <code>1234</code><br>
                • ผู้จ้าง: <code>customer1</code> / รหัส <code>1234</code>
              </div>
            </form>

            <!-- ฟอร์มสมัครสมาชิก -->
            <form v-else @submit.prevent="handleRegister">
              <div class="mb-3">
                <label class="form-label fw-medium">ชื่อ-นามสกุล (หรือชื่อร้านค้า)</label>
                <input v-model="regForm.fullname" type="text" class="form-control" required placeholder="เช่น สมชาย รับออกแบบ">
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">ชื่อผู้ใช้งาน (Username)</label>
                <input v-model="regForm.username" type="text" class="form-control" required placeholder="ภาษาอังกฤษหรือตัวเลข">
              </div>
              <div class="mb-3">
                <label class="form-label fw-medium">รหัสผ่าน (Password)</label>
                <input v-model="regForm.password" type="password" class="form-control" required>
              </div>
              <div class="mb-4">
                <label class="form-label fw-medium">คุณต้องการสมัครในฐานะอะไร?</label>
                <select v-model="regForm.role" class="form-select" required>
                  <option value="customer">ผู้จ้างงาน (ค้นหาและจ้างฟรีแลนซ์)</option>
                  <option value="freelance">ฟรีแลนซ์ (เปิดรับงานและโพสต์บริการ)</option>
                </select>
              </div>
              <button type="submit" class="btn btn-success btn-lg w-100 rounded-pill fw-bold shadow-sm">ยืนยันการสมัครสมาชิก</button>
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
      isRegister: false,
      loginForm: { username: '', password: '' },
      regForm: { fullname: '', username: '', password: '', role: 'customer' }
    }
  },
  methods: {
    async handleLogin() {
      try {
        const res = await fetch('/api/login.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.loginForm)
        });
        const data = await res.json();
        if (data.status === 'success') {
          localStorage.setItem('user', JSON.stringify(data.user));
          this.$emit('login-success');
          if (data.user.role === 'admin') this.$router.push('/admin');
          else if (data.user.role === 'freelance') this.$router.push('/freelance');
          else this.$router.push('/');
        } else {
          alert(data.message);
        }
      } catch (err) {
        console.error('Login error:', err);
        alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาตรวจสอบ XAMPP (Apache/MySQL)');
      }
    },
    async handleRegister() {
      try {
        const res = await fetch('/api/register.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(this.regForm)
        });
        const data = await res.json();
        alert(data.message);
        if (data.status === 'success') {
          this.isRegister = false;
          this.loginForm.username = this.regForm.username;
        }
      } catch (err) {
        console.error('Register error:', err);
        alert('ไม่สามารถเชื่อมต่อเซิร์ฟเวอร์ได้ กรุณาตรวจสอบ XAMPP (Apache/MySQL)');
      }
    }
  }
}
</script>