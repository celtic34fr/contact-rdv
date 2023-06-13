/** @format */

document.addEventListener("DOMContentLoaded", function () {
    let calendars = documeny.querySelectorAll(".fcalendar");
    if (calendars) {
        calendars.foreach(
            (calendar) =>
                function () {
                    let initialDate = calendar.getAttribute("data-start");
                    let fcalendar = new FullCalendar.Calendar(calendar, {
                        themeSystem: "bootstrap5",
                        initialView: "dayGridMonth",
                        timeZone: "local",
                        initialDate: initialDate,
                    });
                    fcalendar.render();
                }
        );
    }
    calendars = documeny.querySelectorAll(".lcalendar");
    if (calendars) {
        calendars.foreach(
            (calendar) =>
                function () {
                    let initialDate = calendar.getAttribute("data-start");
                    let lcalendar = new FullCalendar.Calendar(calendar, {
                        themeSystem: "bootstrap5",
                        initialView: "listWeek",
                        timeZone: "local",
                        initialDate: initialDate,
                    });
                    lcalendar.render();
                }
        );
    }
});
