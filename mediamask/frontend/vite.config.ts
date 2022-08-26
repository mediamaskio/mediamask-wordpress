import { defineConfig } from 'vite'
import vue from '@vitejs/plugin-vue'

// https://vitejs.dev/config/
export default defineConfig({
  root: "frontend",
  build: {
    manifest: true,
    target: "es2015",
    rollupOptions: {
      // make sure to externalize deps that shouldn't be bundled
      // into your library
      input: "src/main.ts",
      output: {
        dir: "dist",
      },
    },
  },
  plugins: [vue()]
})
