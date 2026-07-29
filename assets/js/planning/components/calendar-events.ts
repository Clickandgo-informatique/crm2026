import type { PlanningEvent } from "../core/types";
import { getDateFromDateTime } from "../core/datetime-utils";

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

    // Identifiant de l'événement pour le retrouver lors d'un clic
    element.dataset.id = event.id.toString();
    element.dataset.date = getDateFromDateTime(event.startAt);
    element.dataset.start = event.startAt;
    element.dataset.end = event.endAt;

    element.textContent = event.title;
    element.style.top = `${position.top}px`;
    element.style.height = `${position.height}px`;

    layer.appendChild(element);
}
