import { getMondayOfWeek } from "./date-functions";

let weekGrid: HTMLElement | null = null;

export function initWeeklyCalendar(root: HTMLElement): void
{
    weekGrid = root.querySelector('.weekly-calendar-wrapper');

    if (!weekGrid) {
        console.error("Weekly calendar wrapper introuvable");
        return;
    }

    console.log("Weekly calendar initialized");

    document.addEventListener('dateSelected', (event) => {
        const customEvent = event as CustomEvent<Date>;

        if (!weekGrid) {
            return;
        }

        createGrid(weekGrid, customEvent.detail);
    });

    createGrid(weekGrid, new Date());
}

function createGrid(grid: HTMLElement, referenceDate: Date): void
{
    grid.innerHTML = '';

    let currentDay = getMondayOfWeek(referenceDate);

    for (let i = 1; i <= 7; i++) {
        const dayColumn = document.createElement('div');
        dayColumn.classList.add('day-column');

        const labelDay = document.createElement('div');
        labelDay.classList.add('day-label');

        labelDay.textContent = currentDay.toLocaleDateString('fr-FR', {
            weekday: 'short',
            day: 'numeric',
            month: 'short'
        });

        dayColumn.appendChild(labelDay);
        grid.appendChild(dayColumn);

        currentDay.setDate(currentDay.getDate() + 1);
    }
}