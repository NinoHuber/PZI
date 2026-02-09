<template>
  <div class="container">
    <h1>{{ room.name }}</h1>
    <p><strong>Profesor:</strong> {{ room.professor }}</p>

    <h2>Raspored</h2>
    <ul>
      <li v-for="slot in room.schedule" :key="slot">
        {{ slot }}
      </li>
    </ul>

    <router-link to="/raspored" class="back">
      ← Nazad na raspored
    </router-link>
  </div>
</template>

<script setup>
import { useRoute } from 'vue-router'

const route = useRoute()

const rooms = {
  1: {
    name: 'Učionica A1',
    professor: 'prof. Volarić',
    schedule: ['Ponedjeljak 08–10', 'Srijeda 10–12']
  },
  2: {
    name: 'Učionica A2',
    professor: 'prof. Rozić',
    schedule: ['Utorak 12–14', 'Četvrtak 08–10']
  },
  3: {
    name: 'Učionica B1',
    professor: 'prof. Crnokić',
    schedule: ['Ponedjeljak 14–16']
  },
  4: {
    name: 'Učionica B2',
    professor: 'prof. Ljubić',
    schedule: ['Petak 10–12']
  },
  5: {
    name: 'Učionica C1',
    professor: 'prof. Vasić',
    schedule: ['Srijeda 08–10']
  }
}

const room = rooms[route.params.id]
</script>

<style scoped>
.container {
  background: white;
  padding: 32px;
  border-radius: 16px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
  max-width: 600px;
}

h2 {
  margin-top: 30px;
}

ul {
  padding-left: 20px;
}

li {
  margin-bottom: 8px;
}

.back {
  display: inline-block;
  margin-top: 30px;
  color: #2563eb;
  text-decoration: none;
  font-weight: 500;
}

</style>

