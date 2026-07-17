import { PlanningHeader } from "../components/planning-header";
import { PlanningViewport } from "../components/planning-viewport";
import { CalendarTasks } from "../components/calendar-tasks";
import { ViewLoader } from "../services/view-loader";
import { Toolbar } from "../components/toolbar";

export class PlanningEngine {
    //
    private readonly header = new PlanningHeader();
    private readonly viewport = new PlanningViewport();
    private readonly tasks = new CalendarTasks();
    private readonly viewLoader = new ViewLoader();
    private readonly toolbar = new Toolbar();

    public async initialize(): Promise<void> {
        //
        this.toolbar.initialize(document.querySelector("#planning-toolbar"));
        //
        this.header.initialize(document.querySelector("#planning-header"));
        //
        await this.viewport.initialize(
            document.querySelector("#planning-viewport"),
        );
        
        await this.loadCurrentView();
        
        this.tasks.initialize();

        console.log("PlanningEngine initialized");
    }
    private async loadCurrentView(): Promise<void> {
        const view = "week";

        await this.viewLoader.load(view);
    }
}
