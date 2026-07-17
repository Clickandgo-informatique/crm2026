import { setCalendarZoom, getCalendarZoom } from "../core/planning-state";

export class CalendarZoom {
    public initialize(root: Element | null): void {
        if (!root) {
            return;
        }

        const zoomRange = root.querySelector<HTMLInputElement>(".zoom-range");

        const calendarGrid =
            document.querySelector<HTMLElement>(".calendar-grid");

        if (!zoomRange || !calendarGrid) {
            return;
        }

        zoomRange.value = getCalendarZoom().toString();

        this.applyZoom(calendarGrid);

        zoomRange.addEventListener("input", (event) => {
            const input = event.target as HTMLInputElement;

            setCalendarZoom(Number(input.value));

            this.applyZoom(calendarGrid);
        });
    }

    private applyZoom(grid: HTMLElement): void {
        grid.style.setProperty("--planning-zoom", getCalendarZoom().toString());
    }
}
