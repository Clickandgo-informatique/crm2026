import "./styles/app.css";
import "./js/kanban/index.ts";
import "./js/planning/index.ts";
import { SidebarToggle } from "./js/components/sidebar-toggle.ts";

console.log("APP.JS CHARGE");
console.log("Hello from TypeScript + Vite + Symfony !");

const sidebarToggle = new SidebarToggle();
sidebarToggle.initialize();
