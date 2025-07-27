<?php
include 'db_connection.php';
session_start();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Request Documents</title>
    <script>
        // JavaScript to update the form heading dynamically
        function updateDocumentType() {
            const selectElement = document.getElementById("document_type");
            const selectedDocument = selectElement.value;
            
            // Update the span content with the selected document
            document.getElementById("displayed_document_type").textContent = selectedDocument;

            // Update the hidden input field for form submission
            document.getElementById("hiddenDocument").value = selectedDocument;

            // Display the document form section
            document.getElementById("documentForm").style.display = "block";
        }

        // Close button to hide the form section
        function closeForm() {
            document.getElementById("documentForm").style.display = "none";
        }
    </script>
<style>
#documentForm {
    display: none; /* Hide the form by default */
    margin-top: 20px;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #f9f9f9;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}
#documentForm.show {
    display: block; /* Show the form when needed */
}
#backButton {
    margin-bottom: 20px;
    display: inline-block;
    padding: 10px 20px;
    background-color: #007BFF;
    color: white;
    text-decoration: none;
    border-radius: 5px;
    transition: background-color 0.3s ease, transform 0.3s ease;
}
#backButton:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}
form {
    max-width: 600px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 10px;
    background-color: #fff;
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
    transition: all 0.3s ease-in-out;
}
h1 {
    text-align: center;
    color: #333;
    font-family: 'Arial', sans-serif;
    font-size: 2em;
    margin-bottom: 20px;
}
label {
    display: block;
    margin-bottom: 10px;
    font-weight: bold;
    color: #555;
}
select, input[type="text"], input[type="number"], textarea, input[type="submit"] {
    width: 100%;
    padding: 15px;
    margin-bottom: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-sizing: border-box;
    font-size: 1em;
    transition: border-color 0.3s ease, box-shadow 0.3s ease;
}
select:focus, input[type="text"]:focus, input[type="number"]:focus, textarea:focus {
    border-color: #007BFF;
    box-shadow: 0 0 5px rgba(0, 123, 255, 0.5);
}
input[type="submit"] {
    background-color: #007BFF;
    color: white;
    border: none;
    cursor: pointer;
    transition: background-color 0.3s ease, transform 0.3s ease;
}
input[type="submit"]:hover {
    background-color: #0056b3;
    transform: scale(1.05);
}
</style>
</head>
<body>
    <!-- Back to Dashboard Button -->
    <a id="backButton" href="dashboard.php">Back to Dashboard</a>
    
    <h1>Request a Document</h1>
    <form action="request_process.php" method="POST">
        <label for="document_type">Document Type:</label>
        <select id="document_type" name="document_type" required onchange="updateDocumentType()">
            <option value="" disabled selected>Select a document</option>
            <option value="Barangay Clearance">Barangay Clearance</option>
            <option value="Barangay Certificate">Barangay Certificate</option>
            <option value="Barangay Indigence">Barangay Indigence</option>
        </select>

        <div id="documentForm">
            <h2>Fill up the form for: <span id="displayed_document_type"></span></h2>
            <input type="hidden" name="document" id="hiddenDocument" value="">

            <label for="name">Name:</label>
            <input type="text" id="name" name="name" required>

            <label for="age">Age:</label>
            <input type="number" id="age" name="age" required>

            <label for="address">Address:</label>
            <input type="text" id="address" name="address" required>

            <label for="purpose">Purpose:</label>
            <textarea id="purpose" name="purpose" rows="4" required></textarea>

            <input type="submit" value="Submit">
        </div>
    </form>
</body>
</html>