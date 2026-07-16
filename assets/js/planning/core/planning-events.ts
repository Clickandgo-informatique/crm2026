import type { PlanningEvent } from "./planning-types";
import { SLOT_HEIGHT } from "./planning-config";
import { getCalendarZoom } from "./planning-state";
import { formatDate } from "./date-utils";
// Calcule la position verticale et la hauteur d'un événement dans la grille
function calculateEventPosition(event: PlanningEvent): {
    top: number;
    height: number;
} {
    const start = new Date(event.startAt);
    const end = new Date(event.endAt);
    const startMinutes = start.getHours() * 60 + start.getMinutes();
    const endMinutes = end.getHours() * 60 + end.getMinutes();
    return {
        top: (startMinutes / 60) * SLOT_HEIGHT * getCalendarZoom(),
        height:
            ((endMinutes - startMinutes) / 60) *
            SLOT_HEIGHT *
            getCalendarZoom(),
    };
}
// Charge et affiche les événements d'une colonne journée
export async function createPlanningEvents(
    dayColumn: HTMLElement,
    date: Date,
    events: PlanningEvent[],
): Promise<void> {
    if (!events) {
        console.warn("Aucun événement transmis à createPlanningEvents");

        return;
    }
    console.log("createPlanningEvents appelé", date, events);
    // Sélectionne uniquement les événements du jour affiché
    const dayEvents = events
        .filter((event) => event.startAt.startsWith(formatDate(date)))
        .map((event) => {
            const position = calculateEventPosition(event);
            return {
                ...event,
                ...position,
            };
        });
    console.log("events filtrés", dayEvents);
    // Aucun événement pour cette journée
    if (dayEvents.length === 0) {
        console.log("aucun événement pour cette journée");
        return;
    }
    console.log("envoi des événements au rendu Twig");
    // Demande à Symfony de générer les fragments HTML
    const response = await fetch("/planning/events/render", {
        method: "POST",
        headers: {
            "Content-Type": "application/json",
        },
        body: JSON.stringify(dayEvents),
    });
    console.log("réponse serveur", response.status);
    if (!response.ok) {
        console.error("Impossible de charger les fragments événements");
        return;
    }
    // Récupération du HTML généré par Twig
    const html = await response.text();
    console.log("fragment événement reçu", html);
    // Injection du fragment dans la colonne du jour
    dayColumn.insertAdjacentHTML("beforeend", html);
}
