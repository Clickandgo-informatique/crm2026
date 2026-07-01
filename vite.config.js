import { defineConfig } from "vite";

export default defineConfig({
    root: "assets",
    build: {
        outDir: "../public/build",
        emptyOutDir: true,
    },
    server: {
        host: true,
        port: 5173,
    },
});
