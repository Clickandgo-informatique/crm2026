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

//Enregistre une date actuelle
let selectedDate = new Date();

export function getSelectedDate(): Date {
    return selectedDate;
}

export function setSelectedDate(
    date: Date
): void {
    selectedDate = date;
}