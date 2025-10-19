import 'bootstrap/dist/js/bootstrap.bundle';

const THEME_STORAGE_KEY = 'donordocs.theme';

const storedTheme = window.localStorage.getItem(THEME_STORAGE_KEY);

const applyTheme = (theme: 'dark' | 'light') => {
  document.documentElement.setAttribute('data-bs-theme', theme);
  window.localStorage.setItem(THEME_STORAGE_KEY, theme);
};

if (storedTheme === 'light' || storedTheme === 'dark') {
  applyTheme(storedTheme);
} else {
  applyTheme('dark');
}

const themeToggle = document.querySelector<HTMLButtonElement>('[data-action="toggle-theme"]');

themeToggle?.addEventListener('click', () => {
  const currentTheme = document.documentElement.getAttribute('data-bs-theme') === 'light' ? 'light' : 'dark';
  applyTheme(currentTheme === 'light' ? 'dark' : 'light');
});
