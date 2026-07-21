import { formatDate, getMondayOfWeek } from "../core/date-utils";
import { getSelectedDate } from "../core/planning-state";
import { START_HOUR, END_HOUR, SLOT_DURATION } from "../core/planning-config";
import type { CalendarRenderContext } from "../core/calendar-context";

export class CalendarRenderer {
    /**
     * Construit la grille complète du calendrier.
     *
     * La grille est organisée par colonnes :
     * - une colonne de timeline ;
     * - une colonne par jour contenant un header et les créneaux horaires.
     */
    public async render(
        grid: HTMLElement,
        context: CalendarRenderContext,
    ): Promise<void> {
        const { referenceDate, numberOfDays } = context;

        grid.innerHTML = "";

        grid.style.setProperty("--calendar-days", numberOfDays.toString());

        const startDate =
            numberOfDays === 7 ? getMondayOfWeek(referenceDate) : referenceDate;

        this.createTimelineColumn(grid);

        for (let i = 0; i < numberOfDays; i++) {
            const date = new Date(startDate);

            date.setDate(startDate.getDate() + i);

            this.createDayColumn(grid, date);
        }
    }

    /**
     * Crée la colonne des horaires.
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
     * Crée une colonne correspondant à un jour.
     */
    private createDayColumn(grid: HTMLElement, date: Date): void {
        const column = document.createElement("div");
        column.dataset.date = formatDate(date);
        column.classList.add("calendar-column");

        if (formatDate(date) === formatDate(getSelectedDate())) {
            column.classList.add("selected-planning-day");
        }

        this.createDayHeader(column, date);

        this.createDayBody(column, date);

        grid.appendChild(column);
    }

    /**
     * Crée l'en-tête d'une colonne.
     */
    private createDayHeader(column: HTMLElement, date: Date): void {
        const header = document.createElement("div");
        header.classList.add("calendar-header-cell");
        header.classList.add("calendar-column-header");

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
     * Crée le corps d'une colonne.
     */
    private createDayBody(column: HTMLElement, date: Date): void {
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

        column.appendChild(body);
    }
}
