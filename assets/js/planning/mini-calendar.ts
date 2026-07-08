import { 
    formatDate,
    formatWeekRange,
    getFirstMondayOfCalendar,
    isSameWeek
} from "./date-functions";

let activeMonthLabel: HTMLElement | null = null;
let calendarGrid: HTMLElement | null = null;
let selectedWeekLabel: HTMLElement | null = null;

let btnNextMonth: HTMLElement | null = null;
let btnPrevMonth: HTMLElement | null = null;
let btnToday: HTMLElement | null = null;

// Mois affiché dans le mini calendrier
let currentDate = new Date();

// Date sélectionnée par l'utilisateur
let selectedDate = new Date();

// Date réelle du jour
const today = new Date();

export function initMiniCalendar(root: HTMLElement): void
{
    activeMonthLabel = root.querySelector<HTMLElement>('.active-month-label');
    calendarGrid = root.querySelector<HTMLElement>('.calendar-grid');
    selectedWeekLabel = root.querySelector<HTMLElement>('.selected-week-label');

    btnToday = root.querySelector<HTMLElement>('.btn-today');
    btnNextMonth = root.querySelector<HTMLElement>('.btnNextMonth');
    btnPrevMonth = root.querySelector<HTMLElement>('.btnPrevMonth');

    if (!activeMonthLabel || !calendarGrid) {
        console.error('Éléments du mini calendrier introuvables');
        return;
    }

    btnNextMonth?.addEventListener('click', nextMonth);
    btnPrevMonth?.addEventListener('click', previousMonth);
    btnToday?.addEventListener('click', goToToday);

    renderCalendar();
}

function renderCalendar(): void
{
    displayTodayButton();
    displayMonthLabel();
    displaySelectedWeek();
    createGrid();
}

function displayTodayButton(): void
{
    if (!btnToday) {
        return;
    }

    btnToday.textContent = today.toLocaleDateString('fr-FR', {
        weekday: 'short',
        day: 'numeric',
        month: 'long',
        year: 'numeric'
    });
}

function displayMonthLabel(): void
{
    if (!activeMonthLabel) {
        return;
    }

    activeMonthLabel.textContent = currentDate.toLocaleDateString('fr-FR', {
        month: 'long',
        year: 'numeric'
    });
}

function displaySelectedWeek(): void
{
    if (!selectedWeekLabel) {
        return;
    }

    selectedWeekLabel.textContent = formatWeekRange(selectedDate);
}

function nextMonth(): void
{
    currentDate.setDate(1);
    currentDate.setMonth(currentDate.getMonth() + 1);

    renderCalendar();
}

function previousMonth(): void
{
    currentDate.setDate(1);
    currentDate.setMonth(currentDate.getMonth() - 1);

    renderCalendar();
}

function createGrid(): void
{
    if (!calendarGrid) {
        return;
    }

    const grid = calendarGrid;

    grid.innerHTML = '';

    const dayLabels = [
        'Lun',
        'Mar',
        'Mer',
        'Jeu',
        'Ven',
        'Sam',
        'Dim'
    ];

    dayLabels.forEach(label => {
        const day = document.createElement('span');

        day.classList.add('calendar-day-label');
        day.textContent = label;

        grid.append(day);
    });

    let dayDate = getFirstMondayOfCalendar(currentDate);

    for (let i = 0; i < 42; i++) {

        const cellDate = new Date(dayDate);

        const day = document.createElement('span');

        day.classList.add('calendar-day-cell');

        day.textContent = cellDate.getDate().toString();

        day.dataset.date = formatDate(cellDate);

        if (isSameWeek(cellDate, selectedDate) && cellDate.getTime() !== selectedDate.getTime()) {
            day.classList.add('selected-week');
        }

        if (
            cellDate.getDate() === selectedDate.getDate()
            &&
            cellDate.getMonth() === selectedDate.getMonth()
            &&
            cellDate.getFullYear() === selectedDate.getFullYear()
        ) {
            day.classList.add('selected-day');
        }

        if (
            cellDate.getDate() === today.getDate()
            &&
            cellDate.getMonth() === today.getMonth()
            &&
            cellDate.getFullYear() === today.getFullYear()
        ) {
            day.classList.add('current-day');
        }

        if (
            cellDate.getMonth() !== currentDate.getMonth()
            ||
            cellDate.getFullYear() !== currentDate.getFullYear()
        ) {
            day.classList.add('other-month');
        }

        day.addEventListener('click', () => {

            selectedDate = new Date(cellDate);
            currentDate = new Date(cellDate);

            renderCalendar();

            document.dispatchEvent(
                new CustomEvent('dateSelected', {
                    detail: selectedDate
                })
            );
        });

        grid.append(day);

        dayDate.setDate(dayDate.getDate() + 1);
    }
}

function goToToday(): void
{
    selectedDate = new Date(today);
    currentDate = new Date(today);

    renderCalendar();

    document.dispatchEvent(
        new CustomEvent('dateSelected', {
            detail: selectedDate
        })
    );
}