import {
    formatDate,
    formatWeekRange,
    getFirstMondayOfCalendar,
    isSameWeek,
} from "../core/date-utils";

import { setSelectedDate } from "../core/planning-state";

export class MiniCalendar {
    private activeMonthLabel: HTMLElement | null = null;
    private calendarGrid: HTMLElement | null = null;
    private selectedWeekLabel: HTMLElement | null = null;

    private btnNextMonth: HTMLElement | null = null;
    private btnPrevMonth: HTMLElement | null = null;
    private btnToday: HTMLElement | null = null;

    private currentMonth = new Date();
    private selectedDate = new Date();

    private readonly today = new Date();

    public initialize(root: Element | null): void {
        if (!root) {
            return;
        }

        this.today.setHours(0, 0, 0, 0);

        this.activeMonthLabel = root.querySelector(".active-month-label");

        this.calendarGrid = root.querySelector(".mini-calendar-grid");

        this.selectedWeekLabel = root.querySelector(".selected-week-label");

        this.btnToday = root.querySelector(".btn-today");

        this.btnNextMonth = root.querySelector(".btn-next-month");

        this.btnPrevMonth = root.querySelector(".btn-previous-month");

        if (!this.activeMonthLabel || !this.calendarGrid) {
            console.error("Éléments du mini calendrier introuvables");
            return;
        }

        this.btnNextMonth?.addEventListener("click", () => this.nextMonth());

        this.btnPrevMonth?.addEventListener("click", () =>
            this.previousMonth(),
        );

        this.btnToday?.addEventListener("click", () => this.goToToday());

        setSelectedDate(this.selectedDate);

        this.renderCalendar();
    }

    private renderCalendar(): void {
        this.displayTodayButton();
        this.displayMonthLabel();
        this.displaySelectedWeek();
        this.createGrid();
    }

    private displayTodayButton(): void {
        if (!this.btnToday) {
            return;
        }

        this.btnToday.textContent = this.today.toLocaleDateString("fr-FR", {
            weekday: "short",
            day: "numeric",
            month: "long",
            year: "numeric",
        });
    }

    private displayMonthLabel(): void {
        if (!this.activeMonthLabel) {
            return;
        }

        this.activeMonthLabel.textContent =
            this.currentMonth.toLocaleDateString("fr-FR", {
                month: "long",
                year: "numeric",
            });
    }

    private displaySelectedWeek(): void {
        if (!this.selectedWeekLabel) {
            return;
        }

        this.selectedWeekLabel.textContent = formatWeekRange(this.selectedDate);
    }

    private nextMonth(): void {
        this.currentMonth.setDate(1);
        this.currentMonth.setMonth(this.currentMonth.getMonth() + 1);

        this.renderCalendar();
    }

    private previousMonth(): void {
        this.currentMonth.setDate(1);
        this.currentMonth.setMonth(this.currentMonth.getMonth() - 1);

        this.renderCalendar();
    }

    private createGrid(): void {
        if (!this.calendarGrid) {
            return;
        }

        this.calendarGrid.innerHTML = "";

        const dayLabels = ["Lun", "Mar", "Mer", "Jeu", "Ven", "Sam", "Dim"];

        dayLabels.forEach((label) => {
            const day = document.createElement("span");

            day.classList.add("calendar-day-label");

            day.textContent = label;

            this.calendarGrid?.append(day);
        });

        let dayDate = getFirstMondayOfCalendar(this.currentMonth);

        for (let i = 0; i < 42; i++) {
            const cellDate = new Date(dayDate);

            const day = document.createElement("span");

            day.classList.add("calendar-day-cell");

            day.textContent = cellDate.getDate().toString();

            day.dataset.date = formatDate(cellDate);

            if (
                isSameWeek(cellDate, this.selectedDate) &&
                formatDate(cellDate) !== formatDate(this.selectedDate)
            ) {
                day.classList.add("in-selected-week");
            }

            if (formatDate(cellDate) === formatDate(this.selectedDate)) {
                day.classList.add("selected-day");
            }

            if (formatDate(cellDate) === formatDate(this.today)) {
                day.classList.add("current-day");
            }

            if (
                cellDate.getMonth() !== this.currentMonth.getMonth() ||
                cellDate.getFullYear() !== this.currentMonth.getFullYear()
            ) {
                day.classList.add("other-month");
            }

            day.addEventListener("click", () => {
                this.selectedDate = new Date(cellDate);

                this.currentMonth = new Date(cellDate);

                setSelectedDate(this.selectedDate);

                this.renderCalendar();

                document.dispatchEvent(
                    new CustomEvent("dateSelected", {
                        detail: {
                            date: this.selectedDate,
                        },
                    }),
                );
            });

            this.calendarGrid.append(day);

            dayDate.setDate(dayDate.getDate() + 1);
        }
    }

    private goToToday(): void {
        this.selectedDate = new Date(this.today);

        this.currentMonth = new Date(this.today);

        setSelectedDate(this.selectedDate);

        this.renderCalendar();

        document.dispatchEvent(
            new CustomEvent("dateSelected", {
                detail: {
                    date: this.selectedDate,
                },
            }),
        );
    }
}
