export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    // 🌟 ここを追加
    server: {
        hmr: {
            host: 'agendamento-eyp4.onrender.com',
            protocol: 'wss',
        },
    },
});