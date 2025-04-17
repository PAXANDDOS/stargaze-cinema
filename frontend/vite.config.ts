import react from '@vitejs/plugin-react'
import { defineConfig } from 'vite'
import { cwd, env } from 'process'
import { join } from 'path'

export default defineConfig({
    mode: env.NODE_ENV,
    root: cwd(),
    base: '/',
    plugins: [react()],
    resolve: {
        alias: {
            '@': join(cwd(), 'src'),
        },
    },
    css: {
        preprocessorOptions: {
            scss: {
                additionalData:
                    '@import "src/assets/styles/index"; \
                    @import "src/assets/styles/animations";',
            },
        },
    },
    server: {
        host: '0.0.0.0',
        port: 3000,
        strictPort: true,
    },
    preview: {
        host: '0.0.0.0',
        port: 80,
        strictPort: true,
    },
    envDir: './',
    envPrefix: 'APP_',
})
