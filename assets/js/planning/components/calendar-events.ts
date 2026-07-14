export function renderCalendarEvents(
    grid: HTMLElement,
    events: any[]
): void {

    events.forEach(event => {

        const eventDate =
            new Date(event.startAt);

        const dayIndex =
            (eventDate.getDay() + 6) % 7;

        const cells =
            grid.querySelectorAll<HTMLElement>(
                '.time-slot'
            );

        const cell =
            cells[dayIndex];

        if (!cell) {
            return;
        }

        const element =
            document.createElement('div');

        element.className =
            'calendar-event';

        element.textContent =
            event.title;

        cell.appendChild(element);
    });
}