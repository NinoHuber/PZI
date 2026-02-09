<template>
  <div class="container">
    <RouterLink to="/" class="nazad">Nazad</RouterLink>
    <h1>Raspored</h1>
    <div class="room-selector">
      <label>Filter ucionice:</label>
      <select v-model="selectedRoomId">
        <option v-for="room in rooms" :key="room.id" :value="room.id">
          {{ room.room_number }}
        </option>
      </select>
    </div>

    <div class="schedule-container">
      <table class="schedule-table">
        <thead>
          <tr>
            <th>Time</th>
            <th v-for="day in days" :key="day">{{ day }}</th>
          </tr>
        </thead>
        <tbody>
          <tr v-for="hour in hours" :key="hour">
            <td class="time-column">{{ hour }}:00</td>
            <td v-for="day in days" :key="day" class="slot">
              <div v-if="getSchedule(day, hour)" class="schedule-card">
                <p><strong>{{ getSchedule(day, hour).subject.subject_name }}</strong></p>
                <p>Prof. {{ getSchedule(day, hour).subject.lecturer.last_name }}</p>
                <p class="room-tag">{{ getSchedule(day, hour).room.room_number }}</p>
                <div v-if="userRole === 1 || userRole === 2" class="actions">
                  <button @click="editSchedule(getSchedule(day, hour))">✎</button>
                  <button @click="deleteSchedule(getSchedule(day, hour).id)">🗑</button>
                </div>
              </div>
            </td>
          </tr>
        </tbody>
      </table>
  <div v-if="userRole === 1 || userRole === 2" class="admin-dashboard">
    <form @submit.prevent="submitSchedule" class="dashboard-form">
    
    <div class="input-group">
      <label>Predmet</label>
      <select v-model="newSchedule.subject_id" required>
        <option value="" disabled>Odaberi predmet</option>
        <option v-for="subject in subjects" :key="subject.id" :value="subject.id">
          {{ subject.subject_name }}
        </option>
      </select>
    </div>

    <div class="input-group">
      <label>Ucionica</label>
      <select v-model="selectedRoomId" required>
        <option value="" disabled>Odaberi ucionicu</option>
        <option v-for="room in rooms" :key="room.id" :value="room.id">
          {{ room.room_number }}
        </option>
      </select>
    </div>

    <div class="input-group">
      <label>Dan</label>
      <select v-model="newSchedule.day_of_the_week" required>
        <option value=1>Ponedjeljak</option>
        <option value=2>Utorak</option>
        <option value=3>Srijeda</option>
        <option value=4>Četvrtak</option>
        <option value=5>Petak</option>
      </select>
    </div>

    <div class="input-group">
      <label>Pocetno vrijeme</label>
      <input type="time" v-model="newSchedule.start_time" required>
    </div>

    <div class="input-group">
      <label>Zavrsno vrijeme</label>
      <input type="time" v-model="newSchedule.end_time" required>
    </div>

    <button type="submit" class="add-btn">Dodaj u raspored</button>
  </form>
  
        <p v-if="feedbackMessage" :class="feedbackType">{{ feedbackMessage }}</p>
      </div>

    <div v-if="userRole === 1" class="admin-dashboard">
      <form @submit.prevent="addRoom" class="dashboard-form">
    
      <div class="input-group">
        <label>Naziv</label>
        <input type="text" v-model="roomName">
      </div>

      <button type="submit" class="add-btn">Dodaj ucionicu</button>
      </form>
    </div>

    <div v-if="userRole === 1" class="admin-dashboard">
      <form @submit.prevent="addSubject" class="dashboard-form">
    
      <div class="input-group">
        <label>Naziv</label>
        <input type="text" v-model="subjectName">
      </div>

      <div class="input-group">
      <label>Predstavnik predmeta</label>
      <select v-model="newLecturer" required>
        <option value="" disabled>Odaberi profesora</option>
        <option v-for="professor in professors" :key="professor.id" :value="professor.id">
          {{ professor.first_name }} {{ professor.last_name }}
        </option>
      </select>
    </div>

      <button type="submit" class="add-btn">Dodaj predmet</button>
      </form>
    </div>

    <div v-if="userRole === 1" class="admin-dashboard">
    <div class="management-form">
    
    <select v-model="selectedUserId">
      <option value="" disabled>Select User</option>
      <option v-for="user in allUsers" :key="user.id" :value="user.id">
        {{ user.first_name }} {{ user.last_name }} (Current: {{ user.occupation?.occupation }})
      </option>
    </select>

    <select v-model="newOccupationId">
      <option v-for="occ in occupations" :key="occ.id" :value="occ.id">
        {{ occ.occupation }}
      </option>
    </select>

    <select v-model="newPermissionId">
      <option v-for="perm in permissions" :key="perm.id" :value="perm.id">
        {{ perm.permission }}
      </option>
    </select>

    <button @click="saveUserChanges">Update User</button>
  </div>
</div>

  </div>
</div>

  <div v-if="showEditModal" class="modal-overlay">
  <div class="modal-content">
    <h3>Edit Schedule</h3>
    <form @submit.prevent="updateSchedule">
      
      <div class="form-group">
        <label>Predmet</label>
        <select v-model="editingSchedule.subject_id" required>
          <option v-for="s in subjects" :key="s.id" :value="s.id">{{ s.subject_name }}</option>
        </select>
      </div>

      <div class="form-group">
        <label>Ucionica</label>
        <select v-model="editingSchedule.room_id" required>
          <option v-for="r in rooms" :key="r.id" :value="r.id">{{ r.room_number }}</option>
        </select>
      </div>

      <div class="form-group">
        <label>Dan</label>
        <select v-model="editingSchedule.day_of_the_week" required>
          <option v-for="(name, index) in days" :key="index" :value="index + 1">{{ name }}</option>
        </select>
      </div>

      <div class="form-group">
        <label>Vrijeme</label>
        <div class="time-inputs">
          <input type="time" v-model="editingSchedule.start_time" required>
          <span>to</span>
          <input type="time" v-model="editingSchedule.end_time" required>
        </div>
      </div>

      <div class="modal-actions">
        <button type="submit" class="save-btn">Save Changes</button>
        <button type="button" @click="showEditModal = false" class="cancel-btn">Cancel</button>
      </div>
    </form>
  </div>
</div>
</template>

<script setup>
import { ref, onMounted } from "vue"
import axios from 'axios'

const apiURL = import.meta.env.VITE_API

const userRole = ref()

onMounted(async () => {
  userRole.value = parseInt(localStorage.getItem('userRole')) || 3
  getSchedules()
  await getRooms()
  selectedRoomId.value = rooms.value[0].id
  fetchSubjects()
  fetchProfessors()
  fetchManagementData()
})

async function getRooms() {
  let response = await axios.get(apiURL + "/rooms")
  rooms.value = response.data
}

async function getSchedules() {
  let response = await axios.get(apiURL + "/schedules")
  schedules.value = response.data
}

const days = ['Ponedjeljak', 'Utorak', 'Srijeda', 'Četvrtak', 'Petak']
const hours = [8, 9, 10, 11, 12, 13, 14, 15, 16, 17]
const schedules = ref([])

const getSchedule = (dayName, hour) => {
  const dayNumber = days.indexOf(dayName) + 1;

  return schedules.value.find(s => {

    const startHour = parseInt(s.start_time.split(':')[0]);

    const endHour = parseInt(s.end_time.split(':')[0]);
    
    const isSameDay = parseInt(s.day_of_the_week) === dayNumber;
    const isSameRoom = parseInt(s.room_id) === parseInt(selectedRoomId.value);
    
    const isWithinTimeRange = hour >= startHour && hour < endHour;

    return isSameDay && isWithinTimeRange && isSameRoom;
  });
};


const subjects = ref([]); 
const feedbackMessage = ref('');
const feedbackType = ref('');
const selectedRoomId = ref(0)

const newSchedule = ref({
  subject_id: '',
  day_of_the_week: 1,
  start_time: '09:00',
  end_time: '10:00',
  room_id: selectedRoomId.value
});

const fetchSubjects = async () => {
  const res = await axios.get(apiURL + '/subjects');
  subjects.value = res.data;
};

const submitSchedule = async () => {
  try {
    newSchedule.value.room_id = selectedRoomId.value;
    newSchedule.value.day_of_the_week = parseInt(newSchedule.value.day_of_the_week)
    await axios.post(apiURL + '/schedules', newSchedule.value, 
    {headers: {Authorization: `Bearer ${localStorage.getItem("token")}`}});

    feedbackMessage.value = "Schedule added successfully!";
    feedbackType.value = "success";
    
    getSchedules()
    
    newSchedule.value.subject_id = '';
  } catch (error) {
    feedbackMessage.value = error.response?.data?.error || "Failed to add schedule.";
    feedbackType.value = "error";
  }
};
async function deleteSchedule(id) {
    await axios.delete(apiURL + "/schedule/" + id, 
    {headers: {Authorization: "Bearer " + localStorage.getItem("token")}})
    getSchedules()
  }


const showEditModal = ref(false);
const editingSchedule = ref({});
const rooms = ref();

const editSchedule = (schedule) => {
  console.log(schedule)
  editingSchedule.value = { 
    ...schedule,
    start_time: schedule.start_time.substring(0, 5),
    end_time: schedule.end_time.substring(0, 5)
  };
  showEditModal.value = true;
};

const updateSchedule = async () => {
  try {
    const id = editingSchedule.value.id;
    await axios.put(apiURL + `/schedules/${id}`, editingSchedule.value, 
      {headers: {Authorization: "Bearer " + localStorage.getItem("token")}}
    );
    
    showEditModal.value = false;
    getSchedules()
  } catch (error) {
    alert(error.response?.data?.error || "Overlap detected or invalid data.");
  }
}

const roomName = ref()
const newRoom = ref()
async function addRoom() {
  newRoom.value = {
    room_number: roomName.value
  }
  console.log(newRoom.value)
  await axios.post(apiURL + "/rooms", newRoom.value,
    {headers: {Authorization: "Bearer " + localStorage.getItem("token")}}
  )
  getRooms()
}

const subjectName = ref()
const newSubject = ref()
async function addSubject() {
  newSubject.value = {
    subject_name: subjectName,
    lecturer: newLecturer
  }
  await axios.post(apiURL + "/subjects", newSubject.value,
    {headers: {Authorization: "Bearer " + localStorage.getItem("token")}}
  )
  fetchSubjects()
}
const professors = ref([]);
const newLecturer = ref();

const fetchProfessors = async () => {
  try {
    const response = await axios.get(apiURL + '/professors');
    professors.value = response.data;
  } catch (error) {
    console.error("Failed to fetch professors:", error);
  }
};

const allUsers = ref([]);
const occupations = ref([]);
const permissions = ref([]);

const selectedUserId = ref('');
const newOccupationId = ref('');
const newPermissionId = ref('');

const fetchManagementData = async () => {
  const [u, o, p] = await Promise.all([
    axios.get(apiURL + '/users'),
    axios.get(apiURL + '/occupations'),
    axios.get(apiURL + '/permissions')
  ]);
  allUsers.value = u.data;
  occupations.value = o.data;
  permissions.value = p.data;
};

const saveUserChanges = async () => {
  try {
    await axios.put(apiURL + `/users/${selectedUserId.value}/role`, {
      occupation_id: newOccupationId.value,
      permission_id: newPermissionId.value
    }, {
      headers: { Authorization: `Bearer ${localStorage.getItem('token')}` }
    });
    alert("User updated!");
    fetchProfessors()
    fetchManagementData();
  } catch (e) {
    alert("Update failed.");
  }
};
</script>

<style scoped>
.container h1 {
  margin-bottom: 30px;
}

.grid {
  display: grid;
  grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
  gap: 24px;
}

.card {
  background: white;
  padding: 24px;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  cursor: pointer;
  transition: transform 0.2s, box-shadow 0.2s;
}

.card h2 {
  margin: 0 0 10px 0;
}

.card p {
  color: #475569;
}

.card:hover {
  transform: translateY(-4px);
  box-shadow: 0 15px 30px rgba(0, 0, 0, 0.08);
}

.schedule-table {
  width: 100%;
  border-collapse: collapse;
}
.schedule-table th, .schedule-table td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
  height: 40px;
  width: 14%;
}
.time-column {
  width: 10%;
  background-color: #f4f4f4;
  font-weight: bold;
}
.schedule-card {
  background-color: #e3f2fd;
  border-left: 4px solid #2196f3;
  padding: 5px;
  font-size: 0.8rem;
  border-radius: 4px;
}
.nazad {
  display: inline-block;
  background: #2563eb;
  color: white;
  padding: 14px 26px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 500;
}

.admin-dashboard {
  margin-top: 30px;
  padding: 20px;
  background: #f9f9f9;
  border-top: 2px solid #3498db;
  border-radius: 0 0 8px 8px;
}

.dashboard-form {
  display: flex;
  gap: 15px;
  flex-wrap: wrap;
  align-items: flex-end;
}

.input-group {
  display: flex;
  flex-direction: column;
  gap: 5px;
}

.input-group label {
  font-size: 0.8rem;
  font-weight: bold;
  color: #555;
}

.add-btn {
  background: #27ae60;
  color: white;
  padding: 10px 20px;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}

.success { color: green; margin-top: 10px; }
.error { color: red; margin-top: 10px; }

.modal-overlay {
  position: fixed;
  top: 0; left: 0; width: 100%; height: 100%;
  background: rgba(0, 0, 0, 0.5);
  display: flex;
  justify-content: center;
  align-items: center;
  z-index: 1000;
}
.modal-content {
  background: white;
  padding: 2rem;
  border-radius: 8px;
  width: 400px;
  box-shadow: 0 4px 15px rgba(0,0,0,0.2);
}
.form-group { margin-bottom: 1rem; display: flex; flex-direction: column; }
.time-inputs { display: flex; align-items: center; gap: 10px; }
.modal-actions { display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px; }
.save-btn { background: #27ae60; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
.cancel-btn { background: #95a5a6; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
</style>
