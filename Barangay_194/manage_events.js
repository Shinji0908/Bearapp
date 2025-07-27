document.addEventListener('DOMContentLoaded', function () {
    var calendarEl = document.getElementById('calendar');

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth', // Default view: month grid
        editable: true, // Allow dragging and resizing events
        selectable: true, // Allow selecting dates for new events
        events: 'fetch_events.php', // Fetch events from the backend

        // Event creation on date click
        dateClick: function(info) {
            var eventTitle = prompt('Enter event title:'); // Prompt to enter event title
            if (eventTitle) {
                var eventData = {
                    title: eventTitle,
                    start: info.dateStr,
                    end: info.dateStr // Assuming the event lasts for one day. Adjust as necessary.
                };

                // Send new event to the server to be saved
                fetch('add_event.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(eventData)
                }).then(response => response.json())
                  .then(data => {
                      if (data.success) {
                          calendar.addEvent(eventData); // Add event to the calendar
                      } else {
                          alert(data.message);
                      }
                  });
            }
        },

        // Event editing or deletion on event click
        eventClick: function(info) {
            var action = prompt('Do you want to edit or delete the event? Type "edit" to modify or "delete" to remove it.');
            if (action === 'edit') {
                var newTitle = prompt('Enter new event title:', info.event.title);
                if (newTitle) {
                    // Update the event title on the frontend
                    info.event.setProp('title', newTitle);

                    // Send updated event data to the server
                    fetch('edit_event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: info.event.id,
                            title: newTitle
                        })
                    }).then(response => response.json())
                      .then(data => {
                          if (!data.success) {
                              alert(data.message);
                          }
                      });
                }
            } else if (action === 'delete') {
                if (confirm('Are you sure you want to delete this event?')) {
                    // Remove the event from the frontend
                    info.event.remove();

                    // Send delete request to the server
                    fetch('delete_event.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({
                            id: info.event.id
                        })
                    }).then(response => response.json())
                      .then(data => {
                          if (!data.success) {
                              alert(data.message);
                          }
                      });
                }
            }
        }
    });

    calendar.render();
});
