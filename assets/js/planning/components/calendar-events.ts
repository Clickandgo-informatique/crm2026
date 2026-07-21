import type { PlanningEvent } from "../core/types";

export function renderCalendarEvent(
    cell: HTMLElement,
    event: PlanningEvent,
): void {
    const element = document.createElement("div");
    element.className = "calendar-event";
    element.textContent = event.title;
    cell.appendChild(element);
}
