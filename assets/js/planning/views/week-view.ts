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
let weekEvents: any[] = [];
let currentWeekDate: Date = new Date();
let initialized = false;
export function initWeekPlanning(root: HTMLElement): void
{
    if (initialized) {
        return;
    }
    initialized = true;
    console.log('initWeekPlanning appelé');
    weekGrid =
        root.querySelector<HTMLElement>(
            '.calendar-grid'
        );
    weekLabel =
        root.querySelector<HTMLElement>(
            '#week-label'
        );
    btnPreviousWeek =
        root.querySelector<HTMLElement>(
            '#previous-week'
        );
    btnNextWeek =
        root.querySelector<HTMLElement>(
            '#next-week'
        );
    if (!weekGrid || !weekLabel) {
        console.error(
            "Éléments du planning hebdomadaire introuvables"
        );
        return;
    }
    btnPreviousWeek?.addEventListener(
        'click',
        () => {
            previousWeek();
        }
    );
    btnNextWeek?.addEventListener(
        'click',
        () => {
            nextWeek();
        }
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
async function renderWeek(): Promise<void>
{
    console.log('renderWeek appelé');
    if (!weekGrid || !weekLabel) {
        return;
    }
    await loadWeekEvents();
    weekLabel.textContent =
        formatWeekRange(
            currentWeekDate
        );
    createCalendarGrid(
        weekGrid,
        currentWeekDate,
        7,
        weekEvents
    );
}
async function loadWeekEvents(): Promise<void>
{
    const date =
        currentWeekDate
            .toISOString()
            .split('T')[0];
    try {
        const response =
            await fetch(
                `/planning/events/week?date=${date}`
            );
        if (!response.ok) {
            console.error(
                "Impossible de charger les événements"
            );
            weekEvents = [];
            return;
        }
        weekEvents =
            await response.json();
        console.log(
            'weekEvents chargés',
            weekEvents
        );
    } catch (error) {
        console.error(
            "Erreur lors du chargement des événements",
            error
        );
        weekEvents = [];
    }
}
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