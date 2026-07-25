export interface PlanningEvent {
    id: number;
    title: string;
    description?: string | null;
    type: "appointment" | "task";
    status?: string;
    startAt: string;
    endAt: string;
    allDay?: boolean;
    resourceId?: number;
    calendarId?: number;
    organizationId?: number;
    dossierId?: number;
}

export interface CalendarRenderContext {
    referenceDate: Date;
    numberOfDays: number;
    events: PlanningEvent[];
    showTasks: boolean;
}

export interface PlanningResource {
    id: number;
    label: string;
    color?: string;
}
