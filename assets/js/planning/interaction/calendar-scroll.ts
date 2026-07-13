export function shiftCalendarDate(
    date: Date,
    direction: number,
    visibleDays: number
): Date
{
    const newDate = new Date(date);

    newDate.setDate(
        newDate.getDate() + (direction * visibleDays)
    );

    return newDate;
}