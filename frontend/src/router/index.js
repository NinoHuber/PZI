import { createRouter, createWebHistory } from 'vue-router'
import Dashboard from '../views/Dashboard.vue'
import ScheduleView from '../views/ScheduleView.vue'
import RoomDetails from '../views/RoomDetails.vue'
import RegisterView from '../views/RegisterView.vue'
import LoginView from '../views/LoginView.vue'

const routes = [
  { path: '/', component: Dashboard },
  { path: '/raspored', component: ScheduleView },
  { path: '/register', component: RegisterView },
  { path: '/login', component: LoginView}
]

const router = createRouter({
  history: createWebHistory(),
  routes
})

export default router

