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

// Mois actuellement affiché dans le mini calendrier
let currentMonth = new Date();

// Date sélectionnée par l'utilisateur
let selectedDate = new Date();

// Date réelle du jour
const today = new Date();
today.setHours(0, 0, 0, 0);

export function initMiniCalendar(root: HTMLElement): void
{
    activeMonthLabel = root.querySelector<HTMLElement>('.active-month-label');
    calendarGrid = root.querySelector<HTMLElement>('.calendar-grid');
    selectedWeekLabel = root.querySelector<HTMLElement>('.selected-week-label');

    btnToday = root.querySelector<HTMLElement>('.btn-today');
    btnNextMonth = root.querySelector<HTMLElement>('.btn-next-month');
    btnPrevMonth = root.querySelector<HTMLElement>('.btn-previous-month');

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

    activeMonthLabel.textContent = currentMonth.toLocaleDateString('fr-FR', {
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
    currentMonth.setDate(1);
    currentMonth.setMonth(currentMonth.getMonth() + 1);

    renderCalendar();
}

function previousMonth(): void
{
    currentMonth.setDate(1);
    currentMonth.setMonth(currentMonth.getMonth() - 1);

    renderCalendar();
}

function createGrid(): void
{
    if (!calendarGrid) {
        return;
    }

    const grid = calendarGrid;

    grid.innerHTML = '';

    // Création des entêtes des jours de la semaine
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

    // Récupération du premier lundi affiché dans la grille
    let dayDate = getFirstMondayOfCalendar(currentMonth);

    // Création des 42 cellules du calendrier
    for (let i = 0; i < 42; i++) {

        const cellDate = new Date(dayDate);
        const day = document.createElement('span');

        day.classList.add('calendar-day-cell');
        day.textContent = cellDate.getDate().toString();
        day.dataset.date = formatDate(cellDate);

        // Surligne les autres jours appartenant à la semaine sélectionnée
        if (
            isSameWeek(cellDate, selectedDate)
            &&
            formatDate(cellDate) !== formatDate(selectedDate)
        ) {
            day.classList.add('in-selected-week');
        }

        // Surligne le jour choisi par l'utilisateur
        if (formatDate(cellDate) === formatDate(selectedDate)) {
            day.classList.add('selected-day');
        }

        // Surligne la date du jour
        if (formatDate(cellDate) === formatDate(today)) {
            day.classList.add('current-day');
        }

        // Grise les jours n'appartenant pas au mois affiché
        if (
            cellDate.getMonth() !== currentMonth.getMonth()
            ||
            cellDate.getFullYear() !== currentMonth.getFullYear()
        ) {
            day.classList.add('other-month');
        }

        day.addEventListener('click', () => {

            // Met à jour la date sélectionnée
            selectedDate = new Date(cellDate);

            // Affiche automatiquement le mois correspondant
            currentMonth = new Date(cellDate);

            renderCalendar();

            // Informe le calendrier hebdomadaire de la nouvelle sélection
            document.dispatchEvent(
                new CustomEvent('dateSelected', {
                    detail: {
                        date: selectedDate
                    }
                })
            );
        });

        grid.append(day);

        dayDate.setDate(dayDate.getDate() + 1);
    }
}

function goToToday(): void
{
    // Sélectionne la date actuelle
    selectedDate = new Date(today);
    currentMonth = new Date(today);

    renderCalendar();

    // Informe le calendrier hebdomadaire du retour à aujourd'hui
    document.dispatchEvent(
        new CustomEvent('dateSelected', {
            detail: {
                date: selectedDate
            }
        })
    );    
}