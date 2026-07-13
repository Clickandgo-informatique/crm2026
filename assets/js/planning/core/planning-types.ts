// Etat global du planning
// Ces valeurs peuvent être modifiées par l'utilisateur

let calendarZoom = 1;

// Retourne le niveau de zoom actuel
export function getCalendarZoom(): number
{
    return calendarZoom;
}

// Modifie le niveau de zoom actuel
export function setCalendarZoom(value: number): void
{
    calendarZoom = value;
}
export interface PlanningEvent {
    id: number;
    title: string;
    type: 'appointment' | 'task';
    start: string; // YYYY-MM-DD HH:mm
    end: string;   // YYYY-MM-DD HH:mm
}