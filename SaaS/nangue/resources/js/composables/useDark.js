import { ref, watch } from 'vue';

const isDark = ref(localStorage.getItem('immo-dark-mode') === 'true');

const applyTheme = (dark) => {
  document.documentElement.classList.toggle('dark', dark);
};

applyTheme(isDark.value);

watch(isDark, (val) => {
  localStorage.setItem('immo-dark-mode', val);
  applyTheme(val);
});

const toggleDark = () => {
  isDark.value = !isDark.value;
};

export function useDark() {
  return { isDark, toggleDark };
}
