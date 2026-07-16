import { formatLongDate } from "../core/date-utils";
import { getSelectedDate } from "../core/planning-state";

let currentListDate: Date = getSelectedDate();
let listContainer: HTMLElement | null = null;
let listLabel: HTMLElement | null = null;
let previousButton: HTMLElement | null = null;
let nextButton: HTMLElement | null = null;
let dateSelectedHandler: ((event: Event) => void) | null = null;

export function initListPlanning(root: HTMLElement): void {
    listContainer = root.querySelector<HTMLElement>(".event-list");
    listLabel = root.querySelector<HTMLElement>("#list-label");
    previousButton = root.querySelector<HTMLElement>("#previous-list");
    nextButton = root.querySelector<HTMLElement>("#next-list");
    if (!listContainer || !listLabel) {
        return;
    }
    console.log("Vue liste initialisée");
    previousButton?.addEventListener("click", previousList);
    nextButton?.addEventListener("click", nextList);
    // Stocke le listener pour éviter les doublons lors des changements de vue
    dateSelectedHandler = async (event: Event) => {
        const customEvent = event as CustomEvent;
        const selectedDate = customEvent.detail.date;
        if (!(selectedDate instanceof Date)) {
            return;
        }
        currentListDate = new Date(selectedDate);
        await renderList();
    };
    document.addEventListener("dateSelected", dateSelectedHandler);
    renderList();
}
async function renderList(): Promise<void> {
    if (!listLabel || !listContainer) {
        return;
    }
    listLabel.textContent = formatLongDate(currentListDate);
    await loadEvents();
}
async function loadEvents(): Promise<void> {
    if (!listContainer) {
        return;
    }
    const date = formatRequestDate(currentListDate);
    try {
        const response = await fetch(`/planning/events/list?date=${date}`);
        if (!response.ok) {
            throw new Error("Impossible de charger les événements");
        }
        listContainer.innerHTML = await response.text();
    } catch (error) {
        console.error("Erreur chargement événements liste", error);
        listContainer.innerHTML = "";
    }
}
function previousList(): void {
    currentListDate.setDate(currentListDate.getDate() - 1);
    renderList();
}
function nextList(): void {
    currentListDate.setDate(currentListDate.getDate() + 1);
    renderList();
}
function formatRequestDate(date: Date): string {
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, "0");
    const day = String(date.getDate()).padStart(2, "0");
    return `${year}-${month}-${day}`;
}
export function destroyListPlanning(): void {
    if (dateSelectedHandler) {
        document.removeEventListener("dateSelected", dateSelectedHandler);
        dateSelectedHandler = null;
    }
    previousButton?.removeEventListener("click", previousList);
    nextButton?.removeEventListener("click", nextList);
    listContainer = null;
    listLabel = null;
    previousButton = null;
    nextButton = null;
}
