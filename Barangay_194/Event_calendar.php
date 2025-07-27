<div class="modal" id="eventsModal">
    <div class="modal-content">
        <button class="close-btn" id="closeEventsModal">Close</button>
        <h2 style="text-align: center;">Manage Events</h2>
        <form id="eventForm" style="margin-bottom: 20px;">
            <div style="margin-bottom: 10px;">
                <label for="eventTitle">Event Title:</label>
                <input type="text" id="eventTitle" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                <label for="startDate">Start Date:</label>
                <input type="date" id="startDate" required style="width: 100%; padding: 8px;">
            </div>
            <div style="margin-bottom: 10px;">
                <label for="endDate">End Date:</label>
                <input type="date" id="endDate" required style="width: 100%; padding: 8px;">
            </div>
            <button type="submit" style="padding: 10px 20px;">Add Event</button>
        </form>
        <div id="calendar" style="border: 1px solid #ddd; border-radius: 5px; padding: 10px; max-height: 70%; overflow-y: auto;"></div>
    </div>
</div>

<script>
    document.getElementById('manageEventsLink').addEventListener('click', function (e) {
        e.preventDefault();
        document.getElementById('eventsModal').style.display = 'flex';
    });

    document.getElementById('closeEventsModal').addEventListener('click', function () {
        document.getElementById('eventsModal').style.display = 'none';
    });

    document.addEventListener('DOMContentLoaded', function () {
        var calendarEl = document.getElementById('calendar');
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            editable: true,
            selectable: true,
            events: 'fetch_events.php',
            height: 'auto', // Ensures the calendar adjusts to available space
            dateClick: function(info) {
                document.getElementById('startDate').value = info.dateStr;
                document.getElementById('endDate').value = info.dateStr;
                document.getElementById('eventForm').style.display = 'block';
            },
            eventClick: function(info) {
                if (confirm("Do you want to edit or delete this event?")) {
                    var newTitle = prompt("Enter new event title", info.event.title);
                    if (newTitle) {
                        info.event.setProp('title', newTitle);
                    }
                } else if (confirm("Delete this event?")) {
                    info.event.remove();
                }
            }
        });

        document.getElementById('eventForm').addEventListener('submit', function(e) {
            e.preventDefault();
            var eventTitle = document.getElementById('eventTitle').value;
            var startDate = document.getElementById('startDate').value;
            var endDate = document.getElementById('endDate').value;
            calendar.addEvent({ title: eventTitle, start: startDate, end: endDate });
            alert("Event added!");
            this.reset();
            this.style.display = 'none';
        });

        calendar.render();
    });
</script>

<style>
    /* Modal styles */
    .modal {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0, 0, 0, 0.5);
        justify-content: center;
        align-items: center;
    }

    .modal-content {
        background: white;
        padding: 20px;
        border-radius: 10px;
        width: 80%;
        max-height: 90%;
        overflow: hidden;
        display: flex;
        flex-direction: column;
    }

    .close-btn {
        align-self: flex-end;
        background: red;
        color: white;
        border: none;
        padding: 5px 10px;
        border-radius: 5px;
        cursor: pointer;
    }

    #calendar {
        flex-grow: 1; /* Ensures calendar takes up remaining space */
    }
</style>
