import {
    loadPlanningView
} from './services/view-loader';
import {
    initWeekPlanning
} from './views/week-view';
import {
    initThreeDaysPlanning
} from './views/three-days-view';
import {
    initMiniCalendar
} from './components/mini-calendar';
import {
    initViewSwitcher
} from './components/view-switcher';
import {
    initDayPlanning
} from './views/day-view';
import {
    initListPlanning
} from './views/list-view';

const container =
    document.querySelector<HTMLElement>(
        '#planning-view-container'
    );

if (container) {

    const miniCalendar =
        document.querySelector<HTMLElement>(
            '.mini-calendar-wrapper'
        );

    if (miniCalendar) {
        initMiniCalendar(miniCalendar);
    }

    const viewSwitcher =
        document.querySelector<HTMLElement>(
            '.view-switcher'
        );

    if (viewSwitcher) {
        initViewSwitcher(viewSwitcher);
    }

    document.addEventListener(
        'planningViewChanged',
        async (event) => {

            const customEvent =
                event as CustomEvent;

            const view =
                customEvent.detail.view;

            await loadPlanningView(
                container,
                view
            );

            switch(view) {

                case 'week':
                    initWeekPlanning(container);
                    break;

                case 'three-days':
                    initThreeDaysPlanning(container);
                    break;

                    case 'day':
    initDayPlanning(container);
    break;

    case 'list':
    initListPlanning(container);
    break;
            }
        }
    );

    loadPlanningView(
        container,
        'week'
    ).then(() => {

        console.log('fragment semaine chargé');

        initWeekPlanning(container);

    }).catch(error => {

        console.error(
            'Erreur chargement planning',
            error
        );

    });

}