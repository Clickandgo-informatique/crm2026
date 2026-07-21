export type PlanningView = "week" | "three-days" | "day" | "list";

export interface PlanningViewConfiguration {
    numberOfDays: number;
}

export const PLANNING_VIEW_CONFIG: Record<
    PlanningView,
    PlanningViewConfiguration
> = {
    week: {
        numberOfDays: 7,
    },
    "three-days": {
        numberOfDays: 3,
    },
    day: {
        numberOfDays: 1,
    },
    list: {
        numberOfDays: 0,
    },
};
