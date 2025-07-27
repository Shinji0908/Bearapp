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

  <title>Barangay Officials</title>
  <link href="assets/fullcalendar/main.min.css" rel="stylesheet"> <!-- Local FullCalendar CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    
  <!-- FONT -->
  <link href="https://fonts.googleapis.com/css2?family=DM+Serif+Text&family=Outfit:wght@100..900&display=swap" rel="stylesheet">
  <!-- ICONS -->
  <link href="https://fonts.googleapis.com/icon?family=Material+Icons+Outlined"
      rel="stylesheet">
  <style>
body {
margin:0;
padding:0;
font-family: "Outfit", serif;
background-color: #F9F6F0;
}
.material-icons-outlined {
    vertical-align: middle;
    padding:5px;
    line-height: 1px;
    font-size: 35px;
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
.header-left h2{
    color:#f1f1f1;
}
#back:hover {
    padding: 10px;
    background-color:#4C5D70;
    cursor:pointer;
}
#logout:hover {
    padding: 10px;
    background-color:#4C5D70;
    cursor:pointer;
}
.menu-icon{
    display: none;
}
/*-----------SIDEBAR------------*/
#sidebar{
    grid-area: sidebar;
    height:100%;
    background-color: #2F5061;
    overflow-y: auto;
    transition: all 0.5s;
    -webkit-transition: all 0.5s;
}

.sidebar-title{
    display: flex;
    justify-content: space-between;
    align-items: center;
    padding: 30px,30px,40px,30px;
    margin-bottom:30px;
    color:#f1f1f1;
}

.sidebar-title > span {
    display: none;
}
#sidebar-brand { 
    vertical-align: middle;
    margin: 10px ;
    padding: 5px;
    padding-left: 0px;
    font-size:18px;
    font-weight:700;
}
#sidebar-brand:hover {
    padding-left: 0px;
    background-color:#4C5D70;
    cursor:pointer;
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
            margin-bottom: 5px;
            border-radius: 5px;
            font-size: 14px;
            color: #333;
        }

        .notification-item.unread {
            font-weight: bold;
        }

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

.sidebar-list {
    padding:  0 20px;
    margin-top: 20px;
    list-style-type: none;
}

#ep {
    padding: 20px 20px 10px   4px;
    font-size: 17px;
    color:#f1f1f1;
}

#ep:hover {
    background-color:#4C5D70;
    cursor:pointer;
}
#rd {
    padding: 20px 10px 10px 4px;
    font-size: 17px;
    color:#f1f1f1;
}

#rd:hover {
    background-color:#4C5D70;
    cursor:pointer;
}
#bo {
    padding: 20px 20px 10px   4px;
    font-size:17px;
    color:#f1f1f1;
}

#bo:hover {
    background-color:#4C5D70;
    cursor:pointer;
}
#ps {
    padding: 20px 20px 10px   4px;
    font-size:17px;
    color:#f1f1f1;
}

#ps:hover {
    background-color:#4C5D70;
    cursor:pointer;
}

#event {
    padding: 20px 20px 10px   4px;
    font-size:17px;
    color:#f1f1f1;
}

#event:hover {
    background-color:#4C5D70;
    cursor:pointer;
}
.sidebar-responsive {
    display: inline !important;
    position: absolute;
    z-index: 12 !important;
}
/*-----------MAIN------------*/
.main-container{
    grid-area: main;
    overflow-y: auto;
    padding: 20px 20px;
    color: #2F5061;
}
#BC {
    width: 50%;
    height: 50%;
    margin: 10px;
    padding: 10px 10px 10px 23%;
  }
  main {
      width: 100%;            /* Take full width of the page */
      height: 92.5vh;           /* Set height relative to viewport */
      overflow-y: scroll;     /* Enable vertical scrolling */
      overflow-x: hidden;     /* Disable horizontal scrolling */
      border: 1px solid #ccc; /* Optional: Add a border */
      padding: 10px;          /* Optional: Add some padding */
      box-sizing: border-box; 
    }
    .main-images{
      display: grid;
      grid-template-columns: repeat(3, 1fr); /* 2 columns */
      grid-template-rows: repeat(3, 1fr);    /* 4 rows */
      gap: 20px ;                            /* Space between items */
      height: 150%;
      
    }
    .img{
      display: flex;
      justify-content: center;
      align-items: center;
      overflow: hidden;
    }
    .img img{
      width: 100%;  /* Make the image responsive */
      height: auto; /* Maintain aspect ratio */
      object-fit: cover; /* Ensure images cover the space without distortion */
    }
    </style>
</head>
<body>
    <div class="grid-container">
<!-- header -->
    <header class="header">

    <div class="menu-icon" onclick="openSidebar()">
        <span class="material-icons-outlined">menu</span>
</div>
        <div class="header-left"><h2> OFFICIAL LIST OF BARANGAY COUNCILS </h2> </div>
        <div class="header-right">
            <span id="back">
            <span class="material-icons-outlined">arrow_back</span></span>
            <span id="logout">
            <span class="material-icons-outlined" >logout</span></span>
</div>
    </header>



<!-- side -->
    <aside id="sidebar">
        <div class="sidebar-title">
            <div id="sidebar-brand">
            <span class="material-icons-outlined">dashboard</span>&emsp;DASHBOARD</div>
            <div id="notification">
            <span class="material-icons-outlined"onclick="openModal()">notifications</span></div></div>
<ul class="sidebar-list"> 
<li id="ep">
<span class="material-icons-outlined">edit</span> Edit Profile</li>

<li id="rd">
<span class="material-icons-outlined">request_page</span>Request Documents</li>

<li id="bo">
<span class="material-icons-outlined">groups</span> Barangay Official</li>

<li id="ps">
<span class="material-icons-outlined">badge</span> Profile Status</li>

<li id="event">
<span class="material-icons-outlined">event</span> Events</li>
</ul>
    </aside>


<!-- main -->
    <main class=main-container>
        <div class="main-title">
            <h2> WELCOME! WE ARE THE BARANGAY 194 OFFICIALS.</H2>
            <p> We are here to serve and protect our barangay at all cost. We are happy to please you all.</p>
</div>

        <div class="Images">
            <img id="BC" src="BRGY COUNCIL.Jpg" alt="BRGY Councils"/>
        </div>
 <div class="main-images">
     
    
<div class="img">
            <img id="14" src="14.Jpg" alt="Valentines"/>
</div>
<div class="img">
            <img id="Chua" src="Chua.Jpg" alt="David Chua"/>    
</div>

<div class="img">
            <img id="K" src="Kag.Jpg" alt="Kagawad and Tanods"/>    
</div>

<div class="img">
            <img id="SKC" src="Sk Council.Jpg" alt="Sk council"/> 
</div>

<div class="img">
            <img id="BRGY" src="BRGY.Jpg" alt="Barangay Council"/>    
</div>

  

<div class="img">
            <img id="SK" src="SK.Jpg" alt="Sk council"/>    
</div>

<div class="img">
            <img id="SS" src="SS.Jpg" alt="Sk council"/>    
</div>

</div>
</div>
    </main>

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
    <div class="modal" id="calendarModal">
        <div class="modal-content">
            <button class="close" id="closeCalendarModal">Close</button>
            <div id="calendar"></div>
        </div>
    </div>
    <script src="assets/fullcalendar/index.global.js"></script>
   <script>
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
    var sidebarOpen = false;
    var sidebar = document.getElementById("sidebar");

    function openSidebar() {
        if(!sidebarOpen) {
            sidebar.classList.add("sidebar-responsive");
            sidebarOpen = true;
        }
    }
    function closeSidebar() {
        if(sidebarOpen) {
            sidebar.classList.remove("sidebar-responsive");
            sidebarOpen = false;
        }
    }

    document.getElementById("ep").addEventListener("click", function () {
      window.location.href = "edit_profile.php";});
    document.getElementById("rd").addEventListener("click", function () {
        window.location.href = "request_documents.php"; });
    document.getElementById("bo").addEventListener("click", function () {
         window.location.href = "barangay_officials.php"; });
    document.getElementById("ps").addEventListener("click", function () {
         window.location.href = "profile_status.php"; });
    document.getElementById("sidebar-brand").addEventListener("click", function () {
        window.location.href = "dashboard.php"; });
    document.getElementById("back").addEventListener("click", function () {
        window.location.href = "dashboard.php"; });
    document.getElementById("logout").addEventListener("click", function () {
            window.location.href = "logout.php"; });

            document.getElementById('event').addEventListener('click', function (e) {
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