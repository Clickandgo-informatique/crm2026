/**
 * Retourne l'heure et les minutes d'un datetime ISO.
 *
 * Exemple :
 * 2026-07-22T14:30:00+02:00
 *
 * retourne :
 * 14:30
 */
export function getTimeFromDateTime(dateTime: string): string {
    return dateTime.substring(11, 16);
}

/**
 * Retourne la date YYYY-MM-DD d'un datetime ISO.
 *
 * Exemple :
 * 2026-07-22T14:30:00+02:00
 *
 * retourne :
 * 2026-07-22
 */
export function getDateFromDateTime(dateTime: string): string {
    return dateTime.substring(0, 10);
}
