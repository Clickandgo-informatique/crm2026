export interface PlanningEvent {
    id: number;
    title: string;
    type: "appointment" | "task";
    startAt: string;
    endAt: string;
}

export interface CalendarRenderContext {
    referenceDate: Date;
    numberOfDays: number;
    events: PlanningEvent[];
    showTasks: boolean;
}