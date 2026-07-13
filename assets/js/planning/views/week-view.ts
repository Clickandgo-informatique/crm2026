import {
    shiftCalendarDate
} from "../interaction/calendar-scroll";

import {
    formatWeekRange
} from "../core/date-utils";

import {
    createCalendarGrid
} from "../components/calendar-grid";

import {
    initCalendarZoom
} from "../components/calendar-zoom";

let weekGrid: HTMLElement | null = null;
let weekLabel: HTMLElement | null = null;
let btnPreviousWeek: HTMLElement | null = null;
let btnNextWeek: HTMLElement | null = null;

// Date de référence de la semaine affichée
let currentWeekDate: Date = new Date();

export function initWeekPlanning(root: HTMLElement): void
{
    console.log('initWeekPlanning appelé');

    weekGrid =
        root.querySelector<HTMLElement>('.calendar-grid');

    weekLabel =
        root.querySelector<HTMLElement>('#week-label');

    btnPreviousWeek =
        root.querySelector<HTMLElement>('#previous-week');

    btnNextWeek =
        root.querySelector<HTMLElement>('#next-week');

    if (!weekGrid || !weekLabel) {
        console.error(
            "Éléments du planning hebdomadaire introuvables"
        );

        return;
    }

    btnPreviousWeek?.addEventListener(
        'click',
        previousWeek
    );

    btnNextWeek?.addEventListener(
        'click',
        nextWeek
    );

    initCalendarZoom(root);

    document.addEventListener(
        'dateSelected',
        (event) => {

            const customEvent =
                event as CustomEvent;

            const selectedDate =
                customEvent.detail.date;

            if (!(selectedDate instanceof Date)) {
                return;
            }

            currentWeekDate =
                new Date(selectedDate);

            renderWeek();
        }
    );

    renderWeek();
}

// Rafraîchit l'affichage de la semaine
function renderWeek(): void
{
    console.log('renderWeek appelé');
    if (!weekGrid || !weekLabel) {
        return;
    }

    weekLabel.textContent =
        formatWeekRange(
            currentWeekDate
        );

    createCalendarGrid(
        weekGrid,
        currentWeekDate,
        7
    );
}

// Affiche la semaine précédente
function previousWeek(): void
{
    currentWeekDate =
        shiftCalendarDate(
            currentWeekDate,
            -1,
            7
        );

    renderWeek();
}

// Affiche la semaine suivante
function nextWeek(): void
{
    currentWeekDate =
        shiftCalendarDate(
            currentWeekDate,
            1,
            7
        );

    renderWeek();
}