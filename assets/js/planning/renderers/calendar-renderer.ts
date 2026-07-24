import { formatDate, getMondayOfWeek } from "../core/date-utils";
import { getSelectedDate } from "../core/planning-state";
import {
    START_HOUR,
    END_HOUR,
    SLOT_DURATION,
    PIXELS_PER_HOUR,
} from "../core/planning-config";
import type { CalendarRenderContext } from "../core/calendar-context";
import { renderCalendarEvent } from "../components/calendar-events";
import type { PlanningEvent } from "../core/types";
import { getDateFromDateTime } from "../core/datetime-utils";

export class CalendarRenderer {
    /**
     * Construit la grille complète du calendrier.
     *
     * La grille contient :
     * - une colonne de timeline ;
     * - une colonne par jour ;
     * - une couche indépendante pour les événements positionnés en absolu.
     */
    public async render(
        grid: HTMLElement,
        context: CalendarRenderContext,
    ): Promise<void> {
        const { referenceDate, numberOfDays } = context;
        console.log("[CalendarRenderer] render", {
            date: formatDate(referenceDate),
            days: numberOfDays,
            events: context.events.length,
        });
        grid.innerHTML = "";
        grid.style.setProperty("--calendar-days", numberOfDays.toString());
        const startDate =
            numberOfDays === 7
                ? getMondayOfWeek(referenceDate)
                : new Date(referenceDate);
        this.createTimelineColumn(grid);
        for (let i = 0; i < numberOfDays; i++) {
            const date = new Date(startDate);
            date.setDate(startDate.getDate() + i);
            const dayEvents = context.events.filter((event) => {
                return getDateFromDateTime(event.startAt) === formatDate(date);
            });
            console.log(
                "[CalendarRenderer] day",
                formatDate(date),
                "events",
                dayEvents.length,
            );
            this.createDayColumn(grid, date, dayEvents);
        }
    }
    /**
     * Crée la colonne contenant les horaires.
     */
    private createTimelineColumn(grid: HTMLElement): void {
        const column = document.createElement("div");
        column.classList.add("calendar-timeline-column");
        const header = document.createElement("div");
        header.classList.add("calendar-header-cell", "calendar-time-header");
        column.appendChild(header);
        for (
            let hour = START_HOUR;
            hour < END_HOUR;
            hour += SLOT_DURATION / 60
        ) {
            const cell = document.createElement("div");
            cell.classList.add("calendar-time-cell");
            cell.textContent = `${hour.toString().padStart(2, "0")}:00`;
            column.appendChild(cell);
        }
        grid.appendChild(column);
    }
    /**
     * Crée une colonne représentant une journée.
     */
    private createDayColumn(
        grid: HTMLElement,
        date: Date,
        events: PlanningEvent[],
    ): void {
        const column = document.createElement("div");
        column.dataset.date = formatDate(date);
        column.classList.add("calendar-column");
        if (formatDate(date) === formatDate(getSelectedDate())) {
            column.classList.add("selected-planning-day");
        }
        this.createDayHeader(column, date);
        this.createDayBody(column, date, events);
        grid.appendChild(column);
    }
    /**
     * Crée l'en-tête d'une journée.
     */
    private createDayHeader(column: HTMLElement, date: Date): void {
        const header = document.createElement("div");
        header.classList.add("calendar-header-cell", "calendar-column-header");
        const dayNumber = document.createElement("span");
        dayNumber.classList.add("calendar-day-number");
        dayNumber.textContent = date.toLocaleDateString("fr-FR", {
            day: "numeric",
        });
        const dayName = document.createElement("span");
        dayName.classList.add("calendar-day-name");
        dayName.textContent = date.toLocaleDateString("fr-FR", {
            weekday: "short",
        });
        header.appendChild(dayNumber);
        header.appendChild(dayName);
        column.appendChild(header);
    }
    /**
     * Crée le corps d'une journée.
     *
     * Le corps contient :
     * - la grille horaire en arrière-plan ;
     * - une couche indépendante pour les événements.
     */
    private createDayBody(
        column: HTMLElement,
        date: Date,
        events: PlanningEvent[],
    ): void {
        const body = document.createElement("div");
        body.classList.add("calendar-column-body");
        const selectedDate = formatDate(getSelectedDate());
        for (
            let hour = START_HOUR;
            hour < END_HOUR;
            hour += SLOT_DURATION / 60
        ) {
            const slot = document.createElement("div");
            slot.classList.add("calendar-slot");
            slot.dataset.date = formatDate(date);
            slot.dataset.time = `${hour.toString().padStart(2, "0")}:00`;
            if (formatDate(date) === selectedDate) {
                slot.classList.add("selected");
            }
            body.appendChild(slot);
        }
        const eventsLayer = document.createElement("div");
        eventsLayer.classList.add("calendar-events-layer");
        this.renderEvents(eventsLayer, events);
        body.appendChild(eventsLayer);
        column.appendChild(body);
    }
    /**
     * Positionne les événements dans la couche flottante.
     *
     * La position verticale dépend :
     * - de l'heure de début ;
     * - de l'heure de fin ;
     * - du nombre de pixels représentant une heure.
     */
    private renderEvents(layer: HTMLElement, events: PlanningEvent[]): void {
        console.log("[CalendarRenderer] renderEvents", events.length);
        for (const event of events) {
            const start = new Date(event.startAt);
            const end = new Date(event.endAt);
            const startMinutes = start.getHours() * 60 + start.getMinutes();
            const endMinutes = end.getHours() * 60 + end.getMinutes();
            const top =
                ((startMinutes - START_HOUR * 60) / 60) * PIXELS_PER_HOUR;
            const durationHeight =
                ((endMinutes - startMinutes) / 60) * PIXELS_PER_HOUR;

            const height = Math.max(durationHeight, 40);
            console.log("[CalendarRenderer] placement", event.title, {
                top,
                height,
            });
            renderCalendarEvent(layer, event, {
                top,
                height,
            });
        }
    }
}
