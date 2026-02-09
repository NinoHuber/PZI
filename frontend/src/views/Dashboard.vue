<template>
  <div class="dashboard">
    <div class="hero">
      <h1>SUMIT</h1>
      <p>Informatički centar – pregled rasporeda učionica</p>

      <router-link to="/raspored" class="primary-btn">
        Pogledaj raspored
      </router-link>
    </div>

    <div class="cards">
      <div class="info-card">
        <h3>Učionice</h3>
        <p>Pregled svih dostupnih učionica u SUMIT centru</p>
      </div>

      <div class="info-card">
        <h3>Profesori</h3>
        <p>Raspored profesora po učionicama i terminima</p>
      </div>

      <div class="info-card">
        <h3>Raspored</h3>
        <p>Jasan tjedni pregled zauzetosti prostora</p>
      </div>
    </div>
  </div>
</template>

<style scoped>
.dashboard {
  display: flex;
  flex-direction: column;
  gap: 60px;
}

.hero {
  background: linear-gradient(135deg, #0f172a, #1e293b);
  color: white;
  padding: 60px;
  border-radius: 24px;
}

.hero h1 {
  margin: 0;
  font-size: 48px;
}

.hero p {
  margin: 16px 0 32px;
  font-size: 18px;
  color: #cbd5f5;
}

.primary-btn {
  display: inline-block;
  background: #2563eb;
  color: white;
  padding: 14px 26px;
  border-radius: 12px;
  text-decoration: none;
  font-weight: 500;
}

.primary-btn:hover {
  background: #1d4ed8;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
  gap: 24px;
}

.info-card {
  background: white;
  padding: 28px;
  border-radius: 20px;
  box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05);
}

.info-card h3 {
  margin-top: 0;
}
</style>

