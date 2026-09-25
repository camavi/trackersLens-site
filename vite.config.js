import { defineConfig } from 'vite';
import { cpSync } from 'node:fs';
export default defineConfig({
  root: 'resources/dashboard',
  base: '/build/dashboard/',
  publicDir: false,
  plugins: [{ name: 'dashboard-static-assets', closeBundle() {
    for (const dir of ['src/icons', 'cmswift-fe']) {
      cpSync(`resources/dashboard/${dir}`, `public/build/dashboard/${dir}`, { recursive: true });
    }
  }}],
  build: { outDir: '../../public/build/dashboard', emptyOutDir: true }
});
