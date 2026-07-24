import type { PlanningEvent } from "../core/types";

export function renderCalendarEvent(
    layer: HTMLElement,
    event: PlanningEvent,
    position: {
        top: number;
        height: number;
    },
): void {
    const element = document.createElement("div");
    element.classList.add("calendar-event");
    element.textContent = event.title;
    element.style.top = `${position.top}px`;
    element.style.height = `${position.height}px`;
    layer.appendChild(element);
}
