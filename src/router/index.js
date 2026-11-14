import { createRouter, createWebHistory } from 'vue-router'
import HomeView from '../views/HomeView.vue'
import LoginView from '../views/LoginView.vue'
import FreelanceView from '../views/FreelanceView.vue'
import AdminView from '../views/AdminView.vue'
import ChatView from '../views/ChatView.vue' // <-- 1. นำเข้าหน้าแชท
import InboxView from '../views/InboxView.vue' // นำเข้าหน้า Inbox

const routes = [
  { path: '/', name: 'home', component: HomeView },
  { path: '/login', name: 'login', component: LoginView },
  { path: '/freelance', name: 'freelance', component: FreelanceView },
  { path: '/admin', name: 'admin', component: AdminView },
  { path: '/chat/:serviceId', name: 'chat', component: ChatView },
  { path: '/inbox', name: 'inbox', component: InboxView }// <-- 2. เพิ่มพาร์ทแชท
]

const router = createRouter({
  history: createWebHistory(process.env.BASE_URL),
  routes
})

export default router