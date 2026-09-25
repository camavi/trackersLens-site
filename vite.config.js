import { defineConfig } from 'vite';
import { cpSync } from 'node:fs';
import { resolve } from 'node:path';
import { fileURLToPath } from 'node:url';
const projectRoot = fileURLToPath(new URL('.', import.meta.url));
const fromRoot = (...parts) => resolve(projectRoot, ...parts);
export default defineConfig({
  root: fromRoot('resources/dashboard'),
  base: '/build/dashboard/',
  publicDir: false,
  plugins: [{ name: 'dashboard-static-assets', closeBundle() {
    for (const dir of ['src/icons', 'cmswift-fe']) {
      cpSync(fromRoot(`resources/dashboard/${dir}`), fromRoot(`public/build/dashboard/${dir}`), { recursive: true });
    }
  }}],
  build: { outDir: fromRoot('public/build/dashboard'), emptyOutDir: true }
});
