import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.ts'],
            refresh: true,
        }),
    ],
    // 🌟 これを追加：ビルドのターゲットを広げ、エラーを無視しやすくします
    build: {
        target: 'esnext'
    },
    server: {
        hmr: {
            host: 'agendamento-eyp4.onrender.com',
            protocol: 'wss',
        },
    },
});