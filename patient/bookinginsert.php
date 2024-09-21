<?php
session_start();
include('../configuration/config.php');

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Capture form inputs
    $appointment_date = $_POST['date'];
    $appointment_time = $_POST['time'];
    $patient_id = $_SESSION['patient_id'];  // Assuming patient is logged in and their ID is stored in session
    $doctor_id = $_POST['doctor_id'];  // Assuming the doctor is selected from a dropdown in the form
    $appointment_reason = $_POST['appointment_reason'];  // Assuming this field is added to the form
    $appointment_status = 'Pending';  // Default status for new appointments

    // SQL query to insert the appointment into the database
    $query = "INSERT INTO appointments (patient_id, doctor_id, appointment_date, appointment_time, appointment_reason, appointment_status)
              VALUES (?, ?, ?, ?, ?, ?)";

    if ($stmt = $mysqli->prepare($query)) {
        // Bind the parameters and execute the statement
        $stmt->bind_param('iissss', $patient_id, $doctor_id, $appointment_date, $appointment_time, $appointment_reason, $appointment_status);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            // If the insert was successful
            $_SESSION['success'] = "Appointment booked successfully!";
            header("Location: successpage.php"); // Redirect to a success page (you can customize this)
            exit();
        } else {
            // If the insert failed
            $_SESSION['error'] = "Failed to book appointment. Please try again.";
            header("Location: appointment.php"); // Redirect back to the booking form
            exit();
        }
    } else {
        // If the SQL preparation failed
        $_SESSION['error'] = "Database error: Unable to prepare statement.";
        header("Location: appointment.php");
        exit();
    }
}
?>
