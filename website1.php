<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Function to sanitize input
    function sanitize_input($data) {
        return htmlspecialchars(stripslashes(trim($data)));
    }

    $errors = [];
    $fname = $lname = $email = $date = $treatment = $note = "";

    // Validate first name
    if (empty($_POST["fname"])) {
        $errors[] = "First name is required";
    } else {
        $fname = sanitize_input($_POST["fname"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $fname)) {
            $errors[] = "Only letters and spaces allowed in first name";
        }
    }

    // Validate last name
    if (empty($_POST["lname"])) {
        $errors[] = "Last name is required";
    } else {
        $lname = sanitize_input($_POST["lname"]);
        if (!preg_match("/^[a-zA-Z ]*$/", $lname)) {
            $errors[] = "Only letters and spaces allowed in last name";
        }
    }

    // Validate email
    if (empty($_POST["email"])) {
        $errors[] = "Email is required";
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Invalid email format";
        }
    }

    // Validate date (must be in the future)
    if (empty($_POST["date"])) {
        $errors[] = "Date is required";
    } else {
        $date = sanitize_input($_POST["date"]);
        if (!preg_match("/^\d{4}-\d{2}-\d{2}$/", $date) || $date < date("Y-m-d")) {
            $errors[] = "Appointment date must be in the future";
        }
    }

    // Validate treatment selection
    $valid_treatments = [
        "Dental exam", "Dental emergency", "Teeth cleaning", "Teeth whitening",
        "Extraction", "Trauma surgery", "Laser filling", "Other"
    ];
    if (empty($_POST["treatment"]) || !in_array($_POST["treatment"], $valid_treatments)) {
        $errors[] = "Invalid treatment selection";
    } else {
        $treatment = sanitize_input($_POST["treatment"]);
    }

    // Sanitize optional notes
    $note = !empty($_POST["note"]) ? sanitize_input($_POST["note"]) : "";

    // If no errors, process the form
    if (empty($errors)) {
        echo "<script>alert('Appointment submitted successfully!');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Form</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .form-container { width: 50%; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px; }
        .error { color: red; }
    </style>
</head>
<body>

<div class="form-container">
    <h2>Book an Appointment</h2>

    <?php if (!empty($errors)): ?>
        <div class="error">
            <ul>
                <?php foreach ($errors as $error) echo "<li>$error</li>"; ?>
            </ul>
        </div>
    <?php endif; ?>

    <form action="" method="POST">
        <label for="fname">First Name:</label><br>
        <input type="text" id="fname" name="fname" value="<?= htmlspecialchars($fname) ?>" required><br><br>

        <label for="lname">Last Name:</label><br>
        <input type="text" id="lname" name="lname" value="<?= htmlspecialchars($lname) ?>" required><br><br>

        <label for="email">Email:</label><br>
        <input type="email" id="email" name="email" value="<?= htmlspecialchars($email) ?>" required><br><br>

        <label for="date">Appointment Date:</label><br>
        <input type="date" id="date" name="date" value="<?= htmlspecialchars($date) ?>" required><br><br>

        <label for="treatment">Select Treatment:</label><br>
        <select id="treatment" name="treatment" required>
            <option value="">-- Select Treatment --</option>
            <?php foreach ($valid_treatments as $option): ?>
                <option value="<?= htmlspecialchars($option) ?>" <?= ($treatment === $option) ? 'selected' : '' ?>><?= $option ?></option>
            <?php endforeach; ?>
        </select><br><br>

        <label for="note">Additional Notes (Optional):</label><br>
        <textarea id="note" name="note" rows="4" cols="50"><?= htmlspecialchars($note) ?></textarea><br><br>

        <input type="submit" value="Submit">
    </form>
</div>

</body>
</html>
