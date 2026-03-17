import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // Renderの本番環境（HTTPS/WSS）でJavaScriptを正しく読み込ませる設定
    server: {
        hmr: {
            host: 'agendamento-eyp4.onrender.com',
            protocol: 'wss',
        },
    },
});