import { ref, watch } from 'vue';
import { usePage } from '@inertiajs/vue3';

const TEST_EMAIL = 'brevelnangue3@gmail.com';
const STORAGE_KEY = 'immo_test_user';

const isTestUser = ref(false);

export function useTestUser() {
  const page = usePage();
  const user = page.props.auth?.user;

  if (user?.email === TEST_EMAIL) {
    localStorage.setItem(STORAGE_KEY, 'true');
    isTestUser.value = true;
  } else {
    const stored = localStorage.getItem(STORAGE_KEY);
    if (stored === 'true' && user?.email !== TEST_EMAIL) {
      localStorage.removeItem(STORAGE_KEY);
    }
    isTestUser.value = stored === 'true';
  }

  return { isTestUser, testEmail: TEST_EMAIL };
}
