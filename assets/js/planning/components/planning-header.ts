import { CalendarDays } from "./calendar-days";

export class PlanningHeader {
    private readonly days = new CalendarDays();

    public initialize(container: Element | null): void {
        if (!container) {
            return;
        }

        container.innerHTML = `
            <div class="planning-header">
                <div class="planning-header-days"></div>
            </div>
        `;

        const daysContainer = container.querySelector(".planning-header-days");

        if (daysContainer) {
            this.days.render(daysContainer);
        }
    }
}
