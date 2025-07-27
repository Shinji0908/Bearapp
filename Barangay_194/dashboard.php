<?php
include 'db_connection.php';
session_start();

// Ensure the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redirect to login page
    exit();
}

// Fetch unread notifications for the logged-in user
$user_id = $_SESSION['user_id'];
$notification_sql = "SELECT * FROM Notifications WHERE user_id = ? ORDER BY created_at DESC";
$notification_stmt = $conn->prepare($notification_sql);
$notification_stmt->bind_param("i", $user_id);
$notification_stmt->execute();
$notification_result = $notification_stmt->get_result();

// Count unread notifications
$unread_count = 0;
$notifications = [];
while ($notification = $notification_result->fetch_assoc()) {
    $notifications[] = $notification;
    if ($notification['status'] === 'unread') {
        $unread_count++;
    }
}
$notification_stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="assets/fullcalendar/main.min.css" rel="stylesheet"> <!-- Local FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined" rel="stylesheet">
    
    <title>Dashboard</title>
    <style>
       body {
margin:0;
padding:0;
font-family: "Outfit", serif;
background-color: #F9F6F0;
}

        .grid-container{
    display: grid;
    grid-template-columns: 260px 1fr 1fr 1fr;
    grid-template-rows: 0.2fr 3fr;
    grid-template-areas:
    "sidebar header header header"
    "sidebar main main main";
    height: 100vh;
}
/*-----------HEADER------------*/
.header{
    grid-area: header;
    height:70px;
   display: flex;
   align-items: center;
   justify-content: space-between;
  background-color: #0C2D48;
   padding: 0 30px 0 30px;
    box-shadow: 0 6px 7px 2px rgba(0,0,0,0.45);
}
#logout:hover {
    padding: 10px;
    background-color:#4C5D70;
    cursor:pointer;
}
        .sidebar {
            grid-area: sidebar;
            width: 270px;
            background-color: #2F5061;
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
            background-color: #4C5D70;
        }

        .notifications {
            margin-top: 20px;
            padding: 10px;
            background-color: #1c4e88;
            border-radius: 5px;
        }

        .notifications h3 {
            margin: 0 0 10px;
            font-size: 18px;
            text-align: center;
            color: #f1f1f1;
        }

        .notification-item {
            background-color: #f8f8f8;
            margin-bottom: 10px;
            padding: 10px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }

        .notification-item.unread {
            font-weight: bold;
        }


        /* Modal Styles */
        .modal {
            display: none; /* Hidden by default */
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

        .material-icons-outlined {
            vertical-align: middle;
            padding: 10px;
            line-height: 1px;
            font-size: 35px;
        }

        .grid-container {
            display: grid;
            grid-template-columns: 260px 1fr 1fr 1fr;
            grid-template-rows: 0.2fr 3fr;
            grid-template-areas:
                "sidebar header header header"
                "sidebar main main main";
            height: 100vh;
        }

        .header {
            grid-area: header;
            height: 70px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background-color: #0C2D48;
            padding: 0 30px 0 30px;
            box-shadow: 0 6px 7px 2px rgba(0, 0, 0, 0.45);
        }
.header-left h1{
    color:#f1f1f1;
}
        #back:hover,
        #logout:hover {
            padding: 10px;
            background-color: #4C5D70;
            cursor: pointer;
        }


        .sidebar-responsive {
            display: inline !important;
            position: absolute;
            z-index: 12 !important;
        }

        .main-container {
            grid-area: main;
            overflow-y: auto;
            padding: 20px 20px;
            color: #2F5061;
        }

        .main {
            width: 100%;
            height: 92.5vh;
            overflow-y: scroll;
            overflow-x: hidden;
            border: 1px solid #ccc;
            padding: 5px;
            box-sizing: border-box;
        }

       
    </style>
</head>
<body>
        <div class="grid-container">
    <!-- header -->
        <header class="header">
            <div class="header-left"> <H1>DASHBOARD</H1> </div>
            <div class="header-right">
                <span id="logout">
                <span class="material-icons-outlined" >logout</span></span>
    </div>
        </header>

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>MENU</h2>
            <div id="notofication">
            <span class="material-icons-outlined"onclick="openModal()">
notifications</span></div></div>
            
        
        <ul>
            <li><a href="edit_profile.php"><span class="material-icons-outlined">edit</span> Edit Profile</a></li>
            <li><a href="request_documents.php"><span class="material-icons-outlined">request_page</span> Request Documents</a></li>
            <li><a href="barangay_officials.php"><span class="material-icons-outlined">groups</span> Barangay Officials</a></li>
            <li><a href="profile_status.php"><span class="material-icons-outlined">badge</span> View Profile Status</a></li>
            <li><a href="#" id="eventLink"><span class="material-icons-outlined">event</span> Events</a></li>
        </ul>
    </aside><main class=main-container>
    <div class="main-title">
        <h1> Welcome to the Barangay 194 Dashboard.</H1>
        <p> Select an option from the menu to get started.</p>
</div></main>main>

    <main class=main-container>
    <div class="main-title">
        <h1> Welcome to the Barangay 194 Dashboard.</h1>
        <p> Select an option from the menu to get started.</p>
</div></main>

    <!-- Notification Modal -->
    <div id="notificationModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <h3>Notifications</h3>
            <div class="notifications">
            <?php foreach ($notifications as $notification): ?>
                <div class="notification-item <?= $notification['status'] === 'unread' ? 'unread' : ''; ?>" id="notification-<?= $notification['notification_id']; ?>">
                    <p><?= htmlspecialchars($notification['message']); ?></p>
                    <?php if ($notification['status'] === 'unread'): ?>
                        <button onclick="markAsRead(<?= $notification['notification_id']; ?>)">Mark as Read</button>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
            </div>
        </div>
    </div>

    <!-- Calendar Modal -->
    <div class="modal" id="calendarModal">
        <div class="modal-content">
            <button class="close" id="closeCalendarModal">Close</button>
            <div id="calendar"></div>
        </div>
    </div>

    <script src="assets/fullcalendar/index.global.js"></script>
    <script>
        // Notification JavaScript
        function openModal() {
            document.getElementById("notificationModal").style.display = "block";
        }

        function closeModal() {
            document.getElementById("notificationModal").style.display = "none";
        }

        function markAsRead(notificationId) {
            fetch('mark_as_read.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ notification_id: notificationId }),
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    const notification = document.getElementById('notification-' + notificationId);
                    notification.classList.remove('unread');
                }
            });
        }
        document.getElementById("logout").addEventListener("click", function () {
            window.location.href = "logout.php"; });
        // Calendar
        document.getElementById('eventLink').addEventListener('click', function (e) {
            e.preventDefault();
            document.getElementById('calendarModal').style.display = 'flex';
        });

        document.getElementById('closeCalendarModal').addEventListener('click', function () {
            document.getElementById('calendarModal').style.display = 'none';
        });

        document.addEventListener('DOMContentLoaded', function () {
            var calendarEl = document.getElementById('calendar');
            var calendar = new FullCalendar.Calendar(calendarEl, {
                initialView: 'dayGridMonth',
                events: 'fetch_events.php',
                eventClick: function(info) {
                    alert('Event: ' + info.event.title);
                }
            });
            calendar.render();
        });

    </script>
</body>
</html>

<?php
$conn->close();
?>