export class CalendarDays {
    public render(container: Element): void {
        container.innerHTML = "";

        for (let i = 0; i < 7; i++) {
            const day = document.createElement("div");

            day.className = "calendar-day-header";

            day.innerHTML = `
                <div class="day-title">
                    Jour ${i + 1}
                </div>

                <div class="day-tasks">
                </div>
            `;

            container.appendChild(day);
        }
    }
}
