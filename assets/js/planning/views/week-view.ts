import { shiftCalendarDate } from "../interaction/calendar-scroll";
import { formatWeekRange } from "../core/date-utils";
import { createCalendarGrid } from "../components/calendar-grid";
import { initCalendarZoom } from "../components/calendar-zoom";
import type { PlanningEvent } from "../core/planning-types";

let weekGrid: HTMLElement | null = null;
let weekLabel: HTMLElement | null = null;
let btnPreviousWeek: HTMLElement | null = null;
let btnNextWeek: HTMLElement | null = null;
let weekEvents: PlanningEvent[] = [];
let currentWeekDate: Date = new Date();
let dateSelectedHandler: ((event: Event) => void) | null = null;

export async function initWeekPlanning(root: HTMLElement): Promise<void> {
    weekGrid = root.querySelector<HTMLElement>(".calendar-grid");
    weekLabel = root.querySelector<HTMLElement>("#week-label");
    btnPreviousWeek = root.querySelector<HTMLElement>("#previous-week");
    btnNextWeek = root.querySelector<HTMLElement>("#next-week");
    if (!weekGrid || !weekLabel) {
        console.error("Éléments du planning hebdomadaire introuvables");
        return;
    }
    btnPreviousWeek?.addEventListener("click", previousWeek);
    btnNextWeek?.addEventListener("click", nextWeek);
    initCalendarZoom(root);
    // Stocke le listener pour pouvoir le supprimer lors du changement de vue
    dateSelectedHandler = async (event: Event) => {
        const customEvent = event as CustomEvent;
        const selectedDate = customEvent.detail.date;
        if (!(selectedDate instanceof Date)) {
            return;
        }
        currentWeekDate = new Date(selectedDate);
        await renderWeek();
    };
    document.addEventListener("dateSelected", dateSelectedHandler);
    await renderWeek();
}
async function renderWeek(): Promise<void> {
    if (!weekGrid || !weekLabel) {
        return;
    }
    // Recharge les événements à chaque changement de semaine
    await loadWeekEvents();
    weekLabel.textContent = formatWeekRange(currentWeekDate);
    await createCalendarGrid(weekGrid, currentWeekDate, 7, weekEvents);
}
async function loadWeekEvents(): Promise<void> {
    const date = currentWeekDate.toISOString().split("T")[0];
    try {
        const response = await fetch(`/planning/events/week?date=${date}`);
        if (!response.ok) {
            throw new Error("Impossible de charger les événements");
        }
        weekEvents = await response.json();
    } catch (error) {
        console.error("Erreur lors du chargement des événements", error);
        weekEvents = [];
    }
}
function previousWeek(): void {
    currentWeekDate = shiftCalendarDate(currentWeekDate, -1, 7);
    renderWeek();
}
function nextWeek(): void {
    currentWeekDate = shiftCalendarDate(currentWeekDate, 1, 7);
    renderWeek();
}
export function destroyWeekPlanning(): void {
    if (dateSelectedHandler) {
        document.removeEventListener("dateSelected", dateSelectedHandler);
        dateSelectedHandler = null;
    }
    btnPreviousWeek?.removeEventListener("click", previousWeek);
    btnNextWeek?.removeEventListener("click", nextWeek);
    weekGrid = null;
    weekLabel = null;
    btnPreviousWeek = null;
    btnNextWeek = null;
}
