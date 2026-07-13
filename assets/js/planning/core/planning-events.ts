import type {
    PlanningEvent
} from "../core/planning-types";

import {
    SLOT_HEIGHT
} from "./planning-config";

import {
    getCalendarZoom
} from "./planning-state";

import {
    formatDate
} from "./date-utils";

// Données temporaires de test
// Elles seront remplacées plus tard par un appel Symfony
const events: PlanningEvent[] = [
    {
        id: 1,
        title: "Rendez-vous client",
        type: "appointment",
        start: "2026-07-10 10:00",
        end: "2026-07-10 11:30"
    },
    {
        id: 2,
        title: "Relance devis",
        type: "task",
        start: "2026-07-11 14:00",
        end: "2026-07-11 15:00"
    },
    {
        id: 3,
        title: "Séminaire jésuite",
        type: "task",
        start: "2026-07-11 17:00",
        end: "2026-07-11 21:00"
    }
];

// Formate une heure sans dépendre du fuseau horaire
function formatEventTime(dateTime: string): string
{
    return dateTime.substring(11, 16);
}

// Ajoute les événements correspondant à une journée
export function createPlanningEvents(
    dayColumn: HTMLElement,
    date: Date
): void
{
    const dayEvents = events.filter(event =>
        event.start.startsWith(formatDate(date))
    );

    dayEvents.forEach(event => {

        const element = document.createElement('div');

        element.classList.add('planning-event');

        element.classList.add(event.type);

        element.innerHTML = `
            <span class="time-interval"><i class="fa-regular fa-clock"></i>
                ${formatEventTime(event.start)}
                -
                ${formatEventTime(event.end)}
            </span>
            <br>
            <span class="event-title">
                ${event.title}
            </span>
        `;

        const startHour = Number(
            event.start.substring(11, 13)
        );

        const startMinute = Number(
            event.start.substring(14, 16)
        );

        const endHour = Number(
            event.end.substring(11, 13)
        );

        const endMinute = Number(
            event.end.substring(14, 16)
        );

        const startPosition =
            (
                startHour * 60
                +
                startMinute
            )
            /
            60
            *
            SLOT_HEIGHT;

        const duration =
            (
                (
                    endHour * 60
                    +
                    endMinute
                )
                -
                (
                    startHour * 60
                    +
                    startMinute
                )
            )
            /
            60
            *
            SLOT_HEIGHT;

        element.style.top =
            `${startPosition * getCalendarZoom()}px`;

        element.style.height =
            `${duration * getCalendarZoom()}px`;

        dayColumn.appendChild(element);
    });
}