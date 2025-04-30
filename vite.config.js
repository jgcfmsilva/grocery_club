import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                // CSS
                "resources/css/app.css",
                "resources/css/partials/header.css",
                "resources/css/pages/auth/register.css",
                "resources/css/pages/my-account/main.css",
                "resources/css/pages/my-account/personal-data.css",
                "resources/css/pages/my-account/orders.css",
                "resources/css/pages/my-account/virtual-card.css",

                // JS
                "resources/js/app.js",
                "resources/js/pages/auth/register.js",
                "resources/js/pages/auth/login.js",
                "resources/js/pages/auth/reset-password.js",
                "resources/js/pages/my-account/personal-data.js",
                "resources/js/pages/my-account/orders.js",
                "resources/js/pages/home.js",
                "resources/js/pages/my-account/virtual-card.js",
            ],
            refresh: [`resources/views/**/*`],
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
    },
});
