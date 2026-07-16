import { setCalendarZoom, getCalendarZoom } from "../core/planning-state";

export function initCalendarZoom(root: HTMLElement): void {
    const zoomRange = root.querySelector<HTMLInputElement>(".zoom-range");

    const calendarGrid = root.querySelector<HTMLElement>(".calendar-grid");

    if (!zoomRange || !calendarGrid) {
        return;
    }

    zoomRange.value = getCalendarZoom().toString();

    applyZoom(calendarGrid);

    zoomRange.addEventListener("input", (event) => {
        const input = event.target as HTMLInputElement;

        setCalendarZoom(Number(input.value));

        applyZoom(calendarGrid);
    });
}

function applyZoom(grid: HTMLElement): void {
    grid.style.setProperty("--planning-zoom", getCalendarZoom().toString());
}
