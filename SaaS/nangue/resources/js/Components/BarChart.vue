<script setup>
import { ref, onMounted, watch } from 'vue';
import { Chart, registerables } from 'chart.js';

Chart.register(...registerables);

const props = defineProps({
  data: { type: Array, default: () => [] },
  labelKey: { type: String, default: 'label' },
  valueKey: { type: String, default: 'percent' },
  color: { type: String, default: '#10b981' },
  label: { type: String, default: 'Valeur' },
  height: { type: String, default: '200px' },
});

const canvasRef = ref(null);
let chart = null;

const render = () => {
  if (!canvasRef.value) return;
  if (chart) chart.destroy();
  const ctx = canvasRef.value.getContext('2d');
  chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: props.data.map(d => d[props.labelKey]),
      datasets: [{
        label: props.label,
        data: props.data.map(d => d[props.valueKey]),
        backgroundColor: props.color + '80',
        borderColor: props.color,
        borderWidth: 2,
        borderRadius: 6,
      }],
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: { legend: { display: false } },
      scales: {
        y: { beginAtZero: true, grid: { color: '#e2e8f020' }, ticks: { callback: v => Math.round(v) + '%' } },
        x: { grid: { display: false } },
      },
    },
  });
};

onMounted(render);
watch(() => props.data, render, { deep: true });
</script>

<template>
  <div :style="{ height }">
    <canvas ref="canvasRef" />
  </div>
</template>
