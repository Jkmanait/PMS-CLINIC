<?php
session_start();
include('../../configuration/config.php');

// Fetch all appointments
$query = "SELECT a.id, a.patient_id, a.doctor_id, a.appointment_date, a.appointment_time, a.appointment_reason, a.appointment_status, p.patient_name, d.docname 
          FROM appointments a
          JOIN patients p ON a.patient_id = p.id
          JOIN doctor d ON a.doctor_id = d.id";
$result = $mysqli->query($query);

// Update appointment status
if (isset($_POST['update_status'])) {
    $appointment_id = $_POST['appointment_id'];
    $new_status = $_POST['new_status'];

    $update_query = "UPDATE appointments SET appointment_status = ? WHERE id = ?";
    if ($update_stmt = $mysqli->prepare($update_query)) {
        $update_stmt->bind_param('si', $new_status, $appointment_id);
        $update_stmt->execute();
        
        if ($update_stmt->affected_rows > 0) {
            $success = "Appointment status updated successfully.";
        } else {
            $err = "Failed to update appointment status. No changes made.";
        }
    } else {
        $err = "Failed to prepare the update query: " . $mysqli->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Manage Appointments</title>
    <link rel="stylesheet" href="path/to/bootstrap.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Manage Appointments</h2>
        
        <!-- Display success or error messages -->
        <?php if (isset($success)) { echo "<div class='alert alert-success'>$success</div>"; } ?>
        <?php if (isset($err)) { echo "<div class='alert alert-danger'>$err</div>"; } ?>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Appointment ID</th>
                    <th>Patient Name</th>
                    <th>Doctor Name</th>
                    <th>Date</th>
                    <th>Time</th>
                    <th>Reason</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = $result->fetch_assoc()) { ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['patient_name']; ?></td>
                    <td><?php echo $row['docname']; ?></td>
                    <td><?php echo $row['appointment_date']; ?></td>
                    <td><?php echo $row['appointment_time']; ?></td>
                    <td><?php echo $row['appointment_reason']; ?></td>
                    <td><?php echo $row['appointment_status']; ?></td>
                    <td>
                        <form method="post" action="">
                            <input type="hidden" name="appointment_id" value="<?php echo $row['id']; ?>">
                            <select name="new_status" class="form-control">
                                <option value="Approved" <?php if ($row['appointment_status'] == 'Approved') echo 'selected'; ?>>Approve</option>
                                <option value="Canceled" <?php if ($row['appointment_status'] == 'Canceled') echo 'selected'; ?>>Cancel</option>
                                <option value="Pending" <?php if ($row['appointment_status'] == 'Pending') echo 'selected'; ?>>Pending</option>
                            </select>
                            <button type="submit" name="update_status" class="btn btn-primary btn-sm mt-2">Update</button>
                        </form>
                    </td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>

    <script src="path/to/bootstrap.js"></script>
</body>
</html>
