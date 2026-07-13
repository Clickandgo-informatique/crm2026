import {
    createCalendarGrid
} from "../components/calendar-grid";

import {
    shiftCalendarDate
} from "../interaction/calendar-scroll";

import {
    formatWeekRange
} from "../core/date-utils";
import { initCalendarZoom } from "../components/calendar-zoom";

let threeDaysGrid: HTMLElement | null = null;
let threeDaysLabel: HTMLElement | null = null;

let btnPrevious: HTMLElement | null = null;
let btnNext: HTMLElement | null = null;

let currentThreeDaysDate: Date = new Date();

export function initThreeDaysPlanning(root: HTMLElement): void
{
    threeDaysGrid =
        root.querySelector<HTMLElement>('.calendar-grid');

    threeDaysLabel =
        root.querySelector<HTMLElement>('#three-days-label');

    btnPrevious =
        root.querySelector<HTMLElement>('#previous-three-days');

    btnNext =
        root.querySelector<HTMLElement>('#next-three-days');

    if (!threeDaysGrid) {
        console.error(
            "Grille planning 3 jours introuvable"
        );
        return;
    }

    btnPrevious?.addEventListener(
        'click',
        previousThreeDays
    );

    btnNext?.addEventListener(
        'click',
        nextThreeDays
    );

    initCalendarZoom(root);

    //Affiche le jour sélectionné dans le mini-calendrier
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

        currentThreeDaysDate =
            new Date(selectedDate);

        renderThreeDays();
    }
);

    renderThreeDays();
}

function renderThreeDays(): void
{
    if (!threeDaysGrid) {
        return;
    }

    createCalendarGrid(
        threeDaysGrid,
        currentThreeDaysDate,
        3
    );

    if (threeDaysLabel) {
        threeDaysLabel.textContent =
            formatWeekRange(currentThreeDaysDate);
    }
}

function previousThreeDays(): void
{
    currentThreeDaysDate =
        shiftCalendarDate(
            currentThreeDaysDate,
            -1,
            3
        );

    renderThreeDays();
}

function nextThreeDays(): void
{
    currentThreeDaysDate =
        shiftCalendarDate(
            currentThreeDaysDate,
            1,
            3
        );

    renderThreeDays();
}