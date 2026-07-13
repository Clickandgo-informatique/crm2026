// Date sélectionnée par l'utilisateur dans le planning
let selectedDate = new Date();

// Retourne une date au format YYYY-MM-DD (sans problème de fuseau horaire)
export function formatDate(date: Date): string
{
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');

    return `${year}-${month}-${day}`;
}

//Transforme une date en format long
export function formatLongDate(
    date: Date
): string {

    return date.toLocaleDateString(
        'fr-FR',
        {
            weekday: 'long',
            day: 'numeric',
            month: 'long',
            year: 'numeric'
        }
    );

}

// Recherche du lundi de départ du calendrier
export function getFirstMondayOfCalendar(displayedDate: Date): Date
{
    const firstDay = new Date(
        displayedDate.getFullYear(),
        displayedDate.getMonth(),
        1
    );

    const dayOfWeek = firstDay.getDay();
    const offset = (dayOfWeek + 6) % 7;

    firstDay.setDate(firstDay.getDate() - offset);

    return firstDay;
}

// Recherche le lundi d'une semaine donnée
export function getMondayOfWeek(date: Date): Date
{
    const monday = new Date(date);

    const dayOfWeek = monday.getDay();

    const offset = dayOfWeek === 0 ? -6 : 1 - dayOfWeek;

    monday.setDate(monday.getDate() + offset);

    return monday;
}

// Vérifie si deux dates appartiennent à la même semaine
export function isSameWeek(date1: Date, date2: Date): boolean
{
    const monday1 = getMondayOfWeek(date1);
    const monday2 = getMondayOfWeek(date2);

    return monday1.getTime() === monday2.getTime();
}

// Retourne les bornes d'une semaine
export function getWeekRange(date: Date): { monday: Date, sunday: Date }
{
    const monday = getMondayOfWeek(date);

    const sunday = new Date(monday);

    sunday.setDate(sunday.getDate() + 6);

    return {
        monday,
        sunday
    };
}

// Format texte d'une semaine
export function formatWeekRange(date: Date): string
{
    const { monday, sunday } = getWeekRange(date);

    const weekNumber = getWeekNumber(date);

    return `Sem ${weekNumber} · ${monday.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short'
    })} - ${sunday.toLocaleDateString('fr-FR', {
        day: 'numeric',
        month: 'short',
        year: 'numeric'
    })}`;
}

// Retourne le numéro ISO de la semaine
export function getWeekNumber(date: Date): number
{
    const target = new Date(date);

    target.setHours(0, 0, 0, 0);

    // Jeudi de la semaine courante
    target.setDate(
        target.getDate() + 3 - ((target.getDay() + 6) % 7)
    );

    const firstThursday = new Date(
        target.getFullYear(),
        0,
        4
    );

    return 1 + Math.round(
        (
            target.getTime() - firstThursday.getTime()
        ) / 604800000
    );
}

// Retourne la date sélectionnée actuellement
export function getSelectedDate(): Date
{
    return new Date(selectedDate);
}

// Met à jour la date sélectionnée
export function setSelectedDate(date: Date): void
{
    selectedDate = new Date(date);
}