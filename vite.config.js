import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from '@tailwindcss/vite';
import { networkInterfaces } from 'os';

// Helper: ambil IPv4 lokal
function getLocalIP() {
    const interfaces = networkInterfaces();
    // Prioritaskan interface WiFi
    const preferred = ['Wi-Fi', 'wlan0', 'en0', 'wlp3s0'];
    
    for (const name of preferred) {
        if (interfaces[name]) {
            const ipv4 = interfaces[name].find(
                i => i.family === 'IPv4' && !i.internal
            );
            if (ipv4) return ipv4.address;
        }
    }
    
    // Fallback: ambil yang pertama
    for (const name of Object.keys(interfaces)) {
        for (const iface of interfaces[name]) {
            if (iface.family === 'IPv4' && !iface.internal) {
                return iface.address;
            }
        }
    }
    return 'localhost';
}

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        host: '0.0.0.0',
        port: 5173,
        hmr: {
            host: getLocalIP(), // ← otomatis, misal: 192.168.18.145
        },
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});