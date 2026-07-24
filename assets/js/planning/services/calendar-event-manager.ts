import type { PlanningEvent } from "../core/types";
import type { PlanningView } from "../core/planning-view";
import { formatDate } from "../core/date-utils";

export class CalendarEventManager {
    private events: PlanningEvent[] = [];
    /**
     * Charge les événements correspondant à une vue et une date.
     */
    public async load(
        view: PlanningView,
        date: Date,
    ): Promise<PlanningEvent[]> {
        const endpoint = this.getEndpoint(view);
        const url = `${endpoint}?date=${formatDate(date)}`;
        const response = await fetch(url);
        if (!response.ok) {
            throw new Error(
                `Impossible de charger les événements (${response.status})`,
            );
        }
        const events = await response.json();
        if (!Array.isArray(events)) {
            throw new Error("La réponse serveur des événements est invalide");
        }
        this.events = events;
        return this.events;
    }
    /**
     * Retourne les événements actuellement chargés.
     */
    public getEvents(): PlanningEvent[] {
        return this.events;
    }
    /**
     * Détermine l'endpoint Symfony selon la vue active.
     */
    private getEndpoint(view: PlanningView): string {
        const endpoints: Record<PlanningView, string> = {
            week: "/planning/events/week",
            "three-days": "/planning/events/three-days",
            day: "/planning/events/day",
            list: "/planning/events/day",
        };
        return endpoints[view];
    }
}
