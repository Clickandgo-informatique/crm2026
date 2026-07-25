import { formatDate, getMondayOfWeek } from "../core/date-utils";
import { getDateFromDateTime } from "../core/datetime-utils";
import type { PlanningEvent, PlanningResource } from "../core/types";

export class ResourceRenderer {
    public render(
        container: HTMLElement,
        referenceDate: Date,
        resources: PlanningResource[],
        events: PlanningEvent[],
    ): void {
        container.innerHTML = "";

        const monday = getMondayOfWeek(referenceDate);

        this.renderHeaders(container, monday);

        for (const resource of resources) {
            this.renderResourceHeader(container, resource);

            for (let i = 0; i < 7; i++) {
                const day = new Date(monday);
                day.setDate(monday.getDate() + i);

                const dayEvents = events.filter(
                    (event) =>
                        event.resourceId === resource.id &&
                        this.eventBelongsToDay(event, day),
                );

                this.renderDayCell(container, dayEvents);
            }
        }
    }

    private eventBelongsToDay(event: PlanningEvent, day: Date): boolean {
        const eventDate = getDateFromDateTime(event.startAt);

        return eventDate === formatDate(day);
    }

    private renderHeaders(container: HTMLElement, monday: Date): void {
        container.appendChild(document.createElement("div"));

        for (let i = 0; i < 7; i++) {
            const day = new Date(monday);
            day.setDate(monday.getDate() + i);

            const header = document.createElement("div");

            header.className = "resource-day-header";

            header.textContent = day.toLocaleDateString("fr-FR", {
                weekday: "short",
                day: "numeric",
            });

            container.appendChild(header);
        }
    }

    private renderResourceHeader(
        container: HTMLElement,
        resource: PlanningResource,
    ): void {
        const header = document.createElement("div");

        header.className = "resource-header";
        header.textContent = resource.label;

        container.appendChild(header);
    }

    private renderDayCell(
        container: HTMLElement,
        events: PlanningEvent[],
    ): void {
        const cell = document.createElement("div");

        cell.className = "resource-cell";

        for (const event of events) {
            const card = document.createElement("div");

            card.className = "resource-event";

            card.textContent = event.title;

            cell.appendChild(card);
        }

        container.appendChild(cell);
    }
}
