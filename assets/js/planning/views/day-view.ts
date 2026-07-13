import {
    shiftCalendarDate
} from "../interaction/calendar-scroll";

import {
    formatLongDate
} from "../core/date-utils";

import {
    createCalendarGrid
} from "../components/calendar-grid";

import {
    initCalendarZoom
} from "../components/calendar-zoom";

let dayGrid: HTMLElement | null = null;
let dayLabel: HTMLElement | null = null;

let btnPreviousDay: HTMLElement | null = null;
let btnNextDay: HTMLElement | null = null;

let currentDayDate: Date = new Date();


export function initDayPlanning(
    root: HTMLElement
): void {

    dayGrid =
        root.querySelector<HTMLElement>(
            '.calendar-grid'
        );

    dayLabel =
        root.querySelector<HTMLElement>(
            '#day-label'
        );

    btnPreviousDay =
        root.querySelector<HTMLElement>(
            '#previous-day'
        );

    btnNextDay =
        root.querySelector<HTMLElement>(
            '#next-day'
        );


    if (!dayGrid || !dayLabel) {
        return;
    }


    btnPreviousDay?.addEventListener(
        'click',
        previousDay
    );

    btnNextDay?.addEventListener(
        'click',
        nextDay
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

        currentDayDate =
            new Date(selectedDate);

        renderDay();
    }
);

    renderDay();

}


function renderDay(): void {

    if (!dayGrid || !dayLabel) {
        return;
    }


    dayLabel.textContent =
        formatLongDate(
            currentDayDate
        );


    createCalendarGrid(
        dayGrid,
        currentDayDate,
        1
    );

}


function previousDay(): void {

    currentDayDate =
        shiftCalendarDate(
            currentDayDate,
            -1,
            1
        );

    renderDay();

}


function nextDay(): void {

    currentDayDate =
        shiftCalendarDate(
            currentDayDate,
            1,
            1
        );

    renderDay();

}