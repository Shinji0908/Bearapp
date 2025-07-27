<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in and is an Admin
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'Admin') {
    header("Location: index.php"); // Redirect to login if unauthorized
    exit();
}

// Fetch all users with resident profile information, sorted alphabetically
$sql = "SELECT u.user_id, CONCAT(u.first_name, ' ', u.last_name) AS fullname, u.email, u.account_status, u.password
        FROM users u
        LEFT JOIN residentprofiles rp ON u.user_id = rp.user_id
        ORDER BY fullname ASC";

$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View All Users</title>
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="fullcalendar/css/fullcalendar.min.css" rel="stylesheet">
    <style>
        body {
            font-family: "Outfit", serif;
            background-color: #F9F6F0;
            margin: 0;
            padding: 0;
            display: flex;
        }

        .sidebar {
            width: 300px;
            background-color: #163e66;
            color: white;
            height: 100vh;
            position: fixed;
            padding: 20px 0;
            overflow-y: auto;
        }

        .sidebar-header {
            display: flex;
            justify-content: space-between; /* Aligns text and icon to opposite sides */
            align-items: center;
            padding: 0 20px;
        }

        .sidebar-header h2 {
            margin: 0;
            font-size: 24px;
        }

        .sidebar-header i {
            cursor: pointer;
            font-size: 24px;
        }

        .sidebar ul {
            list-style-type: none;
            padding: 0;
        }

        .sidebar ul li {
            padding: 15px 20px;
            cursor: pointer;
        }

        .sidebar ul li a {
            color: white;
            text-decoration: none;
            display: block;
        }

        .sidebar ul li:hover {
            background-color: #34495e;
        }

        .main-content {
            margin-left: 300px;
            padding: 20px;
            width: 300%;
        }

        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 50px;
            box-shadow: 0 6px 10px rgba(0, 0, 0, 0.1);
            width: 1000%;
            max-width: 1180px;
            transition: transform 0.3s ease-in-out;
        }

        .container:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.2);
        }

        h2 {
    display: block;
    font-size: 1.5em;
    margin-block-start: 0.83em;
    margin-block-end: 0.83em;
    margin-inline-start: 0px;
    margin-inline-end: 0px;
    font-weight: bold;
    unicode-bidi: isolate;
}

        table {
            width: 60%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        table, th, td {
            border: 1px solid #ddd;
        }

        th, td {
            padding: 10px;
            text-align: center;
        }

        th {
            background-color: #2c3e50;
            color: white;
        }

        .view-btn, .edit-btn, .delete-btn {
            padding: 5px 10px;
            color: white;
            text-decoration: none;
        }

        .view-btn {
            background-color: #3498db;
        }

        .edit-btn {
            background-color: #f1c40f;
        }

        .delete-btn {
            background-color: #e74c3c;
        }

        .view-btn:hover, .edit-btn:hover, .delete-btn:hover {
            opacity: 0.8;
        }

        .logout-btn {
            position: absolute;
            bottom: 50px;
            left: 20px;
            padding: 10px;
            background-color: #e74c3c;
            color: white;
            border: none;
            cursor: pointer;
            width: 260px;
        }

        .logout-btn:hover {
            background-color: #c0392b;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fefefe;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 500px;
            border-radius: 10px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <div class="sidebar-header">
            <h2>Admin Dashboard</h2>
            <i class="fas fa-bars"></i>
        </div>
        <ul>
            <li><a href="pending_profiles.php">Approve Profiles</a></li>
            <li><a href="request_documents_admin.php">Request Documents</a></li>
            <li><a href="create_admin.php">Create Another Admin</a></li>
            <li><a href="view_all_users.php">View All Users</a></li>
            <li><a href="admin_logs.php">Admin Logs</a></li>
            <li><a href="#" id="manageEventsLink">Manage Events</a></li>
        </ul>
        <!-- Logout button -->
        <form action="logout.php" method="POST">
            <button type="submit" class="logout-btn">Logout</button>
        </form>
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>All Users</h2>
            <table>
                <thead>
                    <tr>
                        <th>User ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Password</th>
                        <th>Status</th>
                        <th>Edit Password</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><?= htmlspecialchars($row['user_id']) ?></td>
                                <td><?= htmlspecialchars($row['fullname']) ?></td>
                                <td><?= htmlspecialchars($row['email']) ?></td>
                                <td><?= htmlspecialchars($row['password']) ?></td>
                                <td><?= htmlspecialchars($row['account_status'] ?? 'Inactive') ?></td>
                                <td>
                                    <a href="edit_user_password.php?id=<?= $row['user_id'] ?>" class="edit-password-btn">Edit Password</a>
                                </td>
                                <td>
                                    <a href="view_user_details.php?id=<?= $row['user_id'] ?>" class="view-btn">View</a>
                                    <a href="edit_user_profile.php?id=<?= $row['user_id'] ?>" class="edit-btn">Edit</a>
                                    <a href="delete_user_profile.php?id=<?= $row['user_id'] ?>" class="delete-btn" 
                                       onclick="return confirm('Are you sure?')">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="9" style="text-align: center;">No users found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal for Manage Events -->
    <div class="modal" id="eventsModal">
        <div class="modal-content">
            <span class="close" id="closeEventsModal">&times;</span>
            <h2>Manage Events</h2>
            <div id="calendar"></div>
        </div>
    </div>

    <!-- Use local FullCalendar JS -->
    <script src="assets/fullcalendar/index.global.js"></script>
    <script>
        // JavaScript for modal functionality
        document.getElementById('manageEventsLink').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('eventsModal').style.display = 'block';
        });

        document.getElementById('closeEventsModal').addEventListener('click', function () {
            document.getElementById('eventsModal').style.display = 'none';
        });

        // Initialize FullCalendar
        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                editable: true, // Allow dragging and resizing events
                selectable: true, // Allow date selection for new events
                events: 'fetch_events.php', // Fetch events from the database

                // Event click for editing or deleting
                eventClick: function(info) {
                    var action = confirm("Do you want to edit or delete this event?");
                    if (action) {
                        var newTitle = prompt("Enter new event title", info.event.title);
                        if (newTitle) {
                            info.event.setProp('title', newTitle);
                            // Send updated data to the backend
                            var eventData = {
                                event_id: info.event.id,
                                title: newTitle,
                                start_date: info.event.start.toISOString().slice(0, 10),
                                end_date: info.event.end ? info.event.end.toISOString().slice(0, 10) : '',
                                description: info.event.extendedProps.description
                            };

                            // Send data to update event in database
                            fetch('update_event.php', {
                                method: 'POST',
                                body: new URLSearchParams(eventData),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Event updated successfully!');
                                } else {
                                    alert('Failed to update event');
                                }
                            });
                        }
                    } else {
                        if (confirm("Are you sure you want to delete this event?")) {
                            info.event.remove();
                            // Delete event from the backend
                            fetch('delete_event.php', {
                                method: 'POST',
                                body: new URLSearchParams({ event_id: info.event.id }),
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    alert('Event deleted successfully!');
                                } else {
                                    alert('Failed to delete event');
                                }
                            });
                        }
                    }
                },

                // Date click to add new event
                dateClick: function(info) {
                    var eventTitle = prompt("Enter event title: ");
                    if (eventTitle) {
                        var eventData = {
                            title: eventTitle,
                            start_date: info.dateStr,
                            end_date: info.dateStr, // Assuming a single-day event
                            description: ''
                        };
                        
                        // Add event to the calendar
                        calendar.addEvent(eventData);
                        
                        // Send data to the backend to save the event
                        fetch('add_event.php', {
                            method: 'POST',
                            body: new URLSearchParams(eventData),
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                alert("Event added!");
                            } else {
                                alert("Failed to add event");
                            }
                        });
                    }
                }
            });
            calendar.render();
        });
    </script>
</body>
</html>

<?php $conn->close(); ?>