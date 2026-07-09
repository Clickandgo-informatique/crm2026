import {
    formatWeekRange,
    getMondayOfWeek,
    formatDate
} from "./date-functions";

const START_HOUR = 0;
const END_HOUR = 24;
const SLOT_DURATION = 60;
const SLOT_HEIGHT = 60;

let calendarZoom = 1;

let weekGrid: HTMLElement | null = null;
let weekLabel: HTMLElement | null = null;
let btnPreviousWeek: HTMLElement | null = null;
let btnNextWeek: HTMLElement | null = null;

// Date de référence de la semaine affichée
let currentWeekDate: Date = new Date();

export function initWeeklyCalendar(root: HTMLElement): void
{
    weekGrid = root.querySelector<HTMLElement>('.weekly-calendar-grid');
    weekLabel = root.querySelector<HTMLElement>('#week-label');
    btnPreviousWeek = root.querySelector<HTMLElement>('#previous-week');
    btnNextWeek = root.querySelector<HTMLElement>('#next-week');

    if (!weekGrid || !weekLabel) {
        console.error("Éléments du calendrier hebdomadaire introuvables");
        return;
    }

    btnPreviousWeek?.addEventListener('click', previousWeek);
    btnNextWeek?.addEventListener('click', nextWeek);

    // Réception d'une date sélectionnée depuis le mini calendrier
    document.addEventListener('dateSelected', (event) => {
const customEvent = event as CustomEvent;

    const selectedDate = customEvent.detail.date;

    if (!(selectedDate instanceof Date)) {
        return;
    }

    currentWeekDate = new Date(selectedDate);

    renderWeek();
    });

    renderWeek();
}

// Retourne la hauteur d'une cellule selon le zoom
function getSlotHeight(): string
{
    return `${SLOT_HEIGHT * calendarZoom}px`;
}

// Change le niveau de zoom du planning
export function setCalendarZoom(value: number): void
{
    calendarZoom = value;

    document
        .querySelectorAll<HTMLElement>('.time-slot, .time-label')
        .forEach(element => {
            element.style.height = getSlotHeight();
        });
}

// Rafraîchit l'affichage de la semaine
function renderWeek(): void
{
    if (!weekGrid || !weekLabel) {
        return;
    }

    weekLabel.textContent = formatWeekRange(currentWeekDate);

    createGrid(
        weekGrid,
        currentWeekDate
    );
}

// Affiche la semaine précédente
function previousWeek(): void
{
    currentWeekDate.setDate(
        currentWeekDate.getDate() - 7
    );

    renderWeek();
}

// Affiche la semaine suivante
function nextWeek(): void
{
    currentWeekDate.setDate(
        currentWeekDate.getDate() + 7
    );

    renderWeek();
}

// Génère la grille complète
function createGrid(
    grid: HTMLElement,
    referenceDate: Date
): void
{
    grid.innerHTML = "";

    createTimeColumn(grid);

    const monday = getMondayOfWeek(referenceDate);

    // Création des sept jours de la semaine
    for (let i = 0; i < 7; i++) {

        const date = new Date(monday);

        date.setDate(
            monday.getDate() + i
        );

        createDayColumn(
            grid,
            date
        );
    }
}

// Crée la colonne des horaires
function createTimeColumn(grid: HTMLElement): void
{
    const timeColumn = document.createElement('div');

    timeColumn.classList.add('time-column');

    // Header vide pour aligner avec les jours
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

        label.style.height = getSlotHeight();

        label.textContent =
            `${hour.toString().padStart(2, '0')}:00`;

        timeColumn.appendChild(label);
    }

    grid.appendChild(timeColumn);
}

// Crée une colonne correspondant à un jour
function createDayColumn(
    grid: HTMLElement,
    date: Date
): void
{
    const dayColumn = document.createElement('div');

    dayColumn.classList.add('planning-day-column');
    dayColumn.dataset.date=formatDate(date)

    // Header du jour
    const labelDay = document.createElement('div');

    labelDay.classList.add('planning-day-label');

    const weekday = date.toLocaleDateString(
        'fr-FR',
        {
            weekday: 'short'
        }
    );

    const dayNumber = date.getDate();

    const month = date.toLocaleDateString(
        'fr-FR',
        {
            month: 'short'
        }
    );

    labelDay.innerHTML = `
        <span>${weekday}</span>
        <span class="header-day-number">${dayNumber}</span>
    `;

    dayColumn.appendChild(labelDay);

    // Création des cellules horaires
    for (
        let hour = START_HOUR;
        hour < END_HOUR;
        hour += SLOT_DURATION / 60
    ) {
        const slot = document.createElement('div');

        slot.classList.add('time-slot');
        slot.style.height = getSlotHeight();
        slot.dataset.date = formatDate(date);
        slot.dataset.time =
            `${hour.toString().padStart(2, '0')}:00`;

        dayColumn.appendChild(slot);
    }

    grid.appendChild(dayColumn);

// Applique une classe pour mettre en évidence la date choisie dans le planning
document.addEventListener('dateSelected', (event) => {

    const customEvent = event as CustomEvent;

    const selectedDate = customEvent.detail.date;

    if (!(selectedDate instanceof Date)) {
        return;
    }

    const selectedDateString = formatDate(selectedDate);
    const planningColumns = document.querySelectorAll<HTMLElement>('.planning-day-column');

    planningColumns.forEach((column) => {
        column.classList.remove('selected-column');

        if (column.dataset.date === selectedDateString) {
            console.log("dates égales");
            column.classList.add('selected-column');
        }
    });
});
}