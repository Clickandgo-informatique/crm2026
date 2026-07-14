import type {
    PlanningEvent
} from "../core/planning-types";
import {
    createCalendarGrid
} from "../components/calendar-grid";
import {
    shiftCalendarDate
} from "../interaction/calendar-scroll";
import {
    formatWeekRange
} from "../core/date-utils";
import {
    initCalendarZoom
} from "../components/calendar-zoom";
let threeDaysGrid: HTMLElement | null = null;
let threeDaysLabel: HTMLElement | null = null;
let btnPrevious: HTMLElement | null = null;
let btnNext: HTMLElement | null = null;
let threeDaysEvents: PlanningEvent[] = [];
let currentThreeDaysDate: Date = new Date();
let initialized = false;
export function initThreeDaysPlanning(root: HTMLElement): void
{
    if (initialized) {
        return;
    }
    initialized = true;
    threeDaysGrid =
        root.querySelector<HTMLElement>('.calendar-grid');
    threeDaysLabel =
        root.querySelector<HTMLElement>('#three-days-label');
    btnPrevious =
        root.querySelector<HTMLElement>('#previous-three-days');
    btnNext =
        root.querySelector<HTMLElement>('#next-three-days');
    threeDaysEvents =
        JSON.parse(
            root.dataset.events ?? '[]'
        );
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
        3,
        threeDaysEvents
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