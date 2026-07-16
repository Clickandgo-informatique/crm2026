import type { PlanningEvent } from "../core/planning-types";
import { shiftCalendarDate } from "../interaction/calendar-scroll";
import { formatLongDate } from "../core/date-utils";
import { createCalendarGrid } from "../components/calendar-grid";
import { initCalendarZoom } from "../components/calendar-zoom";

let dayGrid: HTMLElement | null = null;
let dayLabel: HTMLElement | null = null;
let btnPreviousDay: HTMLElement | null = null;
let btnNextDay: HTMLElement | null = null;
let dayEvents: PlanningEvent[] = [];
let currentDayDate: Date = new Date();
let dateSelectedHandler: ((event: Event) => void) | null = null;

export async function initDayPlanning(root: HTMLElement): Promise<void> {
    dayGrid = root.querySelector<HTMLElement>(".calendar-grid");
    dayLabel = root.querySelector<HTMLElement>("#day-label");
    btnPreviousDay = root.querySelector<HTMLElement>("#previous-day");
    btnNextDay = root.querySelector<HTMLElement>("#next-day");
    if (!dayGrid || !dayLabel) {
        console.error("Éléments du planning jour introuvables");
        return;
    }
    btnPreviousDay?.addEventListener("click", previousDay);
    btnNextDay?.addEventListener("click", nextDay);
    initCalendarZoom(root);
    // Stocke le listener pour pouvoir le supprimer lors du changement de vue
    dateSelectedHandler = async (event: Event) => {
        const customEvent = event as CustomEvent;
        const selectedDate = customEvent.detail.date;
        if (!(selectedDate instanceof Date)) {
            return;
        }
        currentDayDate = new Date(selectedDate);
        await renderDay();
    };
    document.addEventListener("dateSelected", dateSelectedHandler);
    await renderDay();
}
async function renderDay(): Promise<void> {
    if (!dayGrid || !dayLabel) {
        return;
    }
    await loadDayEvents();
    dayLabel.textContent = formatLongDate(currentDayDate);
    await createCalendarGrid(dayGrid, currentDayDate, 1, dayEvents);
}
async function loadDayEvents(): Promise<void> {
    const date = currentDayDate.toISOString().split("T")[0];
    try {
        const response = await fetch(`/planning/events/day?date=${date}`);
        if (!response.ok) {
            throw new Error("Impossible de charger les événements");
        }
        dayEvents = await response.json();
    } catch (error) {
        console.error(
            "Erreur lors du chargement des événements du jour",
            error,
        );
        dayEvents = [];
    }
}
function previousDay(): void {
    currentDayDate = shiftCalendarDate(currentDayDate, -1, 1);
    renderDay();
}
function nextDay(): void {
    currentDayDate = shiftCalendarDate(currentDayDate, 1, 1);
    renderDay();
}
export function destroyDayPlanning(): void {
    if (dateSelectedHandler) {
        document.removeEventListener("dateSelected", dateSelectedHandler);
        dateSelectedHandler = null;
    }
    btnPreviousDay?.removeEventListener("click", previousDay);
    btnNextDay?.removeEventListener("click", nextDay);
    dayGrid = null;
    dayLabel = null;
    btnPreviousDay = null;
    btnNextDay = null;
}
