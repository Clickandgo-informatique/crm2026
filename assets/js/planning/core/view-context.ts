import type { PlanningEvent } from "./types";

export interface CalendarRenderContext {
    referenceDate: Date;
    numberOfDays: number;
    events: PlanningEvent[];
}