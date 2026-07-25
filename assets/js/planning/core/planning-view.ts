export type PlanningView = "week" | "three-days" | "day" | "list" | "resources";

export interface PlanningViewConfiguration {
    numberOfDays: number;
}

export const PLANNING_VIEW_CONFIG = {
    week: {
        type: "calendar",
        numberOfDays: 7,
    },
    "three-days": {
        type: "calendar",
        numberOfDays: 3,
    },
    day: {
        type: "calendar",
        numberOfDays: 1,
    },
    list: {
        type: "list",
        numberOfDays: 0,
    },
    resources: {
        type: "resource",
        numberOfDays: 7,
    },
};
