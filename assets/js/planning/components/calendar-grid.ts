import {
    formatDate,
    getMondayOfWeek,
    getSelectedDate
} from "../core/date-utils";
import {
    START_HOUR,
    END_HOUR,
    SLOT_DURATION
} from "../core/planning-config";
import {
    createPlanningEvents
} from "../core/planning-events";

export function createCalendarGrid(
    grid: HTMLElement,
    referenceDate: Date,
    numberOfDays: number
): void
{
    grid.innerHTML = "";

    grid.style.setProperty(
        '--calendar-days',
        numberOfDays.toString()
    );

    createTimeColumn(grid);

   const startDate =
    numberOfDays === 7
        ? getMondayOfWeek(referenceDate)
        : referenceDate;

    for (let i = 0; i < numberOfDays; i++) {

        const date = new Date(startDate);

        date.setDate(
            startDate.getDate() + i
        );

        createDayColumn(
            grid,
            date
        );
    }
}

// Création de la colonne des horaires
function createTimeColumn(
    grid: HTMLElement
): void
{
    const timeColumn = document.createElement('div');

    timeColumn.classList.add('time-column');

    const header = document.createElement('div');

    header.classList.add('hours-label-header');

    timeColumn.appendChild(header);

    for (
        let hour = START_HOUR;
        hour < END_HOUR;
        hour += SLOT_DURATION / 60
    ) {

        const label = document.createElement('div');

        label.classList.add('time-label');

        label.textContent =
            `${hour.toString().padStart(2, '0')}:00`;

        timeColumn.appendChild(label);
    }

    grid.appendChild(timeColumn);
}

// Création d'une colonne journée
function createDayColumn(
    grid: HTMLElement,
    date: Date
): void
{
    const dayColumn = document.createElement('div');

    dayColumn.classList.add('planning-day-column');

    dayColumn.dataset.date =
        formatDate(date);

    const selectedDate = getSelectedDate();

    if (
        formatDate(date) === formatDate(selectedDate)
    ) {
        dayColumn.classList.add('selected-column');
    }

    const dayColumnHeader = document.createElement('div');
    dayColumnHeader.classList.add('day-column-header');

    const weekday = date.toLocaleDateString(
        'fr-FR',
        {
            weekday: 'short'
        }
    );

    const dayNumber = date.getDate();

    const headerDayTitle=document.createElement('span')
    headerDayTitle.classList.add('header-day-title')
    headerDayTitle.textContent=weekday

    const headerDayNumber=document.createElement('span')
    headerDayNumber.classList.add('header-day-number')
    headerDayNumber.textContent=dayNumber

    dayColumnHeader.appendChild(headerDayTitle)
    dayColumnHeader.appendChild(headerDayNumber)

    


    dayColumn.appendChild(dayColumnHeader);

    for (
        let hour = START_HOUR;
        hour < END_HOUR;
        hour += SLOT_DURATION / 60
    ) {

        const slot = document.createElement('div');

        slot.classList.add('time-slot');

        slot.dataset.date =
            formatDate(date);

        slot.dataset.time =
            `${hour.toString().padStart(2, '0')}:00`;

        dayColumn.appendChild(slot);
    }

    createPlanningEvents(
        dayColumn,
        date
    );

    grid.appendChild(dayColumn);
}