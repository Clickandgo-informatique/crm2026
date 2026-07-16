import type { PlanningEvent } from "../core/planning-types";
import { createCalendarGrid } from "../components/calendar-grid";
import { shiftCalendarDate } from "../interaction/calendar-scroll";
import { formatWeekRange } from "../core/date-utils";
import { initCalendarZoom } from "../components/calendar-zoom";

let threeDaysGrid: HTMLElement | null = null;
let threeDaysLabel: HTMLElement | null = null;
let btnPrevious: HTMLElement | null = null;
let btnNext: HTMLElement | null = null;
let threeDaysEvents: PlanningEvent[] = [];
let currentThreeDaysDate: Date = new Date();
let dateSelectedHandler: ((event: Event) => void) | null = null;

export async function initThreeDaysPlanning(root: HTMLElement): Promise<void> {
    threeDaysGrid = root.querySelector<HTMLElement>(".calendar-grid");
    threeDaysLabel = root.querySelector<HTMLElement>("#three-days-label");
    btnPrevious = root.querySelector<HTMLElement>("#previous-three-days");
    btnNext = root.querySelector<HTMLElement>("#next-three-days");
    if (!threeDaysGrid) {
        console.error("Grille planning 3 jours introuvable");
        return;
    }
    btnPrevious?.addEventListener("click", previousThreeDays);
    btnNext?.addEventListener("click", nextThreeDays);
    initCalendarZoom(root);
    // Stocke le listener pour éviter les abonnements multiples lors des changements de vue
    dateSelectedHandler = async (event: Event) => {
        const customEvent = event as CustomEvent;
        const selectedDate = customEvent.detail.date;
        if (!(selectedDate instanceof Date)) {
            return;
        }
        currentThreeDaysDate = new Date(selectedDate);
        await renderThreeDays();
    };
    document.addEventListener("dateSelected", dateSelectedHandler);
    await renderThreeDays();
}
async function renderThreeDays(): Promise<void> {
    if (!threeDaysGrid) {
        return;
    }
    await loadThreeDaysEvents();
    await createCalendarGrid(
        threeDaysGrid,
        currentThreeDaysDate,
        3,
        threeDaysEvents,
    );
    if (threeDaysLabel) {
        threeDaysLabel.textContent = formatWeekRange(currentThreeDaysDate);
    }
}
function previousThreeDays(): void {
    currentThreeDaysDate = shiftCalendarDate(currentThreeDaysDate, -1, 3);
    renderThreeDays();
}
function nextThreeDays(): void {
    currentThreeDaysDate = shiftCalendarDate(currentThreeDaysDate, 1, 3);
    renderThreeDays();
}
async function loadThreeDaysEvents(): Promise<void> {
    const date = currentThreeDaysDate.toISOString().split("T")[0];
    try {
        const response = await fetch(
            `/planning/events/three-days?date=${date}`,
        );
        if (!response.ok) {
            throw new Error("Impossible de charger les événements");
        }
        threeDaysEvents = await response.json();
    } catch (error) {
        console.error(
            "Erreur lors du chargement des événements 3 jours",
            error,
        );
        threeDaysEvents = [];
    }
}
export function destroyThreeDaysPlanning(): void {
    if (dateSelectedHandler) {
        document.removeEventListener("dateSelected", dateSelectedHandler);
        dateSelectedHandler = null;
    }
    btnPrevious?.removeEventListener("click", previousThreeDays);
    btnNext?.removeEventListener("click", nextThreeDays);
    threeDaysGrid = null;
    threeDaysLabel = null;
    btnPrevious = null;
    btnNext = null;
}
