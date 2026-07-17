import { PlanningEngine } from './core/planning-engine';
console.log("planning index loaded");

document.addEventListener('DOMContentLoaded', async () => {
    const planning = new PlanningEngine();
    await planning.initialize();
});