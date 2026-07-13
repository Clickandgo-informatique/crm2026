import {
    formatLongDate
} from "../core/date-utils";
import {
    getSelectedDate
} from "../core/planning-state";

let currentListDate: Date = getSelectedDate();
let listContainer: HTMLElement | null = null;
let listLabel: HTMLElement | null = null;

export function initListPlanning(
    root: HTMLElement
): void {

    listContainer =
        root.querySelector<HTMLElement>(
            '.event-list'
        );

    listLabel =
        root.querySelector<HTMLElement>(
            '#list-label'
        );

    const previous =
        root.querySelector<HTMLElement>(
            '#previous-list'
        );

    const next =
        root.querySelector<HTMLElement>(
            '#next-list'
        );

    if (!listContainer || !listLabel) {
        return;
    }

    console.log(
        'Vue liste initialisée'
    );

    previous?.addEventListener(
        'click',
        previousList
    );

    next?.addEventListener(
        'click',
        nextList
    );

    document.addEventListener(
        'dateSelected',
        (event) => {

            console.log(
                'dateSelected reçu dans liste',
                event
            );

            const customEvent =
                event as CustomEvent;

            const selectedDate =
                customEvent.detail.date;

            console.log(
                'date reçue',
                selectedDate
            );

            if (!(selectedDate instanceof Date)) {
                return;
            }

            currentListDate =
                new Date(selectedDate);

            renderList();

        }
    );

    renderList();

}

async function renderList(): Promise<void> {

    if (!listLabel || !listContainer) {
        return;
    }

    listLabel.textContent =
        formatLongDate(
            currentListDate
        );

    await loadEvents();

}

async function loadEvents(): Promise<void>
{
    if (!listContainer) {
        return;
    }

    const date =
        formatRequestDate(
            currentListDate
        );

    const response =
        await fetch(
            `/planning/events/list?date=${date}`
        );

    if (!response.ok) {
        throw new Error(
            'Impossible de charger les événements'
        );
    }

    listContainer.innerHTML =
        await response.text();
}

function previousList(): void {

    currentListDate.setDate(
        currentListDate.getDate() - 1
    );

    renderList();

}

function nextList(): void {

    currentListDate.setDate(
        currentListDate.getDate() + 1
    );

    renderList();

}

function formatRequestDate(
    date: Date
): string {

    const year =
        date.getFullYear();

    const month =
        String(
            date.getMonth() + 1
        ).padStart(
            2,
            '0'
        );

    const day =
        String(
            date.getDate()
        ).padStart(
            2,
            '0'
        );

    return `${year}-${month}-${day}`;

}