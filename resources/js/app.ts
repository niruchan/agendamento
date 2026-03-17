import './bootstrap';
import Alpine from 'alpinejs';

// 🌟 TypeScriptに「windowの中にAlpineがあるよ」と教えてあげる
interface Window {
    Alpine?: typeof Alpine;
}

declare const window: Window & typeof globalThis;

// Alpineをグローバルに登録
window.Alpine = Alpine;

// Alpineを開始
Alpine.start();