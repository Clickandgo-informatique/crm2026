import type { PlanningEvent } from "./types";

/**
 * Données nécessaires au rendu d'un calendrier.
 */
export interface CalendarRenderContext {
    /**
     * Date servant de référence pour construire la vue.
     */
    referenceDate: Date;

    /**
     * Nombre de jours affichés dans la vue.
     * Exemples :
     * - semaine : 7
     * - trois jours : 3
     * - jour : 1
     */
    numberOfDays: number;

    /**
     * Liste des événements à afficher.
     */
    events: PlanningEvent[];

    //Affichage des tâches
    showTasks:boolean
}
