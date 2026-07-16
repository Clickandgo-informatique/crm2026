import { loadPlanningView } from "./services/view-loader";
import { initWeekPlanning, destroyWeekPlanning } from "./views/week-view";
import {
    initThreeDaysPlanning,
    destroyThreeDaysPlanning,
} from "./views/three-days-view";
import { initMiniCalendar } from "./components/mini-calendar";
import { initViewSwitcher } from "./components/view-switcher";
import { initDayPlanning, destroyDayPlanning } from "./views/day-view";
import { initListPlanning, destroyListPlanning } from "./views/list-view";

const container = document.querySelector<HTMLElement>(
    "#planning-view-container",
);

if (container) {
    const miniCalendar = document.querySelector<HTMLElement>(
        ".mini-calendar-wrapper",
    );
    if (miniCalendar) {
        initMiniCalendar(miniCalendar);
    }
    const viewSwitcher = document.querySelector<HTMLElement>(".view-switcher");
    if (viewSwitcher) {
        initViewSwitcher(viewSwitcher);
    }
    document.addEventListener("planningViewChanged", async (event) => {
        const customEvent = event as CustomEvent;
        const view = customEvent.detail.view;
        // Détruit la vue précédente avant de charger la nouvelle
        destroyWeekPlanning();
        destroyThreeDaysPlanning();
        destroyDayPlanning();
        destroyListPlanning();

        await loadPlanningView(container, view);

        switch (view) {
            case "week":
                await initWeekPlanning(container);
                break;
            case "three-days":
                await initThreeDaysPlanning(container);
                break;
            case "day":
                await initDayPlanning(container);
                break;
            case "list":
                await initListPlanning(container);
                break;
        }
    });
    loadPlanningView(container, "week")
        .then(async () => {
            console.log("fragment semaine chargé");
            await initWeekPlanning(container);
        })
        .catch((error) => {
            console.error("Erreur chargement planning", error);
        });
}
