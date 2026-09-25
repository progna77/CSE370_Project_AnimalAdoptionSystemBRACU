
<?php

include '../includes/auth.php';
include '../includes/db.php';

$message = "";


/* =========================
   ADD VET
   ========================= */

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_vet'])) {

    $vet_name = trim($_POST['vet_name']);
    $specialization = trim($_POST['specialization']);
    $availability_status = trim($_POST['availability_status']);
    $qualification = trim($_POST['qualification']);
    $license_number = trim($_POST['license_number']);
    $email = trim($_POST['email']);
    $experienced_years = (int) $_POST['experienced_years'];
    $phone_number = trim($_POST['phone_number']);


    /* =========================
       INSERT VET
       ========================= */

    $sql = "INSERT INTO vet
            (
                vet_name,
                specialization,
                availability_status,
                qualification,
                license_number,
                email,
                experienced_years
            )
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    $stmt->bind_param(
        "ssssssi",
        $vet_name,
        $specialization,
        $availability_status,
        $qualification,
        $license_number,
        $email,
        $experienced_years
    );


    if ($stmt->execute()) {

        /*
         * Get the Vet_ID that was just created.
         */
        $vet_ID = $conn->insert_id;


        /* =========================
           INSERT PHONE NUMBER
           ========================= */

        $phone_sql = "INSERT INTO vet_phone
                      (Vet_ID, phone_number)
                      VALUES (?, ?)";

        $phone_stmt = $conn->prepare($phone_sql);

        $phone_stmt->bind_param(
            "is",
            $vet_ID,
            $phone_number
        );


        if ($phone_stmt->execute()) {

            $phone_stmt->close();
            $stmt->close();

            /*
             * POST → REDIRECT → GET
             *
             * This prevents the same vet from
             * being inserted again when the
             * page is refreshed.
             */
            header("Location: vet.php?msg=" . urlencode("Vet added successfully!"));
            exit();

        } else {

            $phone_stmt->close();
            $stmt->close();

            header("Location: vet.php?msg=" . urlencode("Vet was added, but phone number could not be saved."));
            exit();
        }

    } else {

        $error = $stmt->error;

        $stmt->close();

        header("Location: vet.php?msg=" . urlencode("Error adding vet: " . $error));
        exit();
    }
}


/* =========================
   MESSAGE
   ========================= */

if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
}


/* =========================
   GET ALL VETS
   ========================= */

$sql = "SELECT
            vet.Vet_ID,
            vet.vet_name,
            vet.specialization,
            vet.availability_status,
            vet.qualification,
            vet.license_number,
            vet.email,
            vet.experienced_years,
            vet_phone.phone_number

        FROM vet

        LEFT JOIN vet_phone
            ON vet.Vet_ID = vet_phone.Vet_ID

        ORDER BY vet.Vet_ID DESC";

$vets = $conn->query($sql);


if (!$vets) {
    die("Vet query failed: " . $conn->error);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Veterinarians</title>

    <link rel="stylesheet" href="../style.css">

    <style>

        .form-box {
            background: white;
            padding: 20px;
            margin-bottom: 30px;
            border-radius: 12px;
        }

        .form-box input,
        .form-box select {
            width: 100%;
            padding: 10px;
            margin: 6px 0 12px;
            box-sizing: border-box;
        }

        .form-box button {
            padding: 10px 20px;
            cursor: pointer;
        }

        .message {
            margin-bottom: 15px;
            font-weight: bold;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 10px;
            border: 1px solid #ddd;
            text-align: left;
        }

    </style>

</head>


<body>

<div class="container">

    <h1>🐾 Veterinary Care</h1>


    <!-- =========================
         MESSAGE
         ========================= -->

    <?php if ($message != ""): ?>

        <p class="message">
            <?php echo htmlspecialchars($message); ?>
        </p>

    <?php endif; ?>


    <!-- =========================
         ADD VET FORM
         ========================= -->

    <div class="form-box">

        <h2>Add Vet</h2>

        <form method="POST">

            <label>Vet Name</label>

            <input
                type="text"
                name="vet_name"
                required
            >


            <label>Specialization</label>

            <input
                type="text"
                name="specialization"
                required
            >


            <label>Availability Status</label>

            <input
                type="text"
                name="availability_status"
                placeholder="Please enter Available, Busy or Unavailable"
                required
            >


            <label>Qualification</label>

            <input
                type="text"
                name="qualification"
                required
            >


            <label>License Number</label>

            <input
                type="text"
                name="license_number"
                required
            >


            <label>Email</label>

            <input
                type="email"
                name="email"
                required
            >


            <label>Experienced Years</label>

            <input
                type="number"
                name="experienced_years"
                min="0"
                required
            >


            <label>Phone Number</label>

            <input
                type="text"
                name="phone_number"
                required
            >


            <button
                type="submit"
                name="add_vet"
            >
                Add Vet
            </button>

        </form>

    </div>


    <!-- =========================
         VET LIST
         ========================= -->

    <h2>Veterinarians</h2>

    <table>

        <tr>

            <th>Vet ID</th>
            <th>Name</th>
            <th>Specialization</th>
            <th>Availability</th>
            <th>Qualification</th>
            <th>License</th>
            <th>Email</th>
            <th>Experience</th>
            <th>Phone</th>

        </tr>


        <?php if ($vets && $vets->num_rows > 0): ?>

            <?php while ($row = $vets->fetch_assoc()): ?>

                <tr>

                    <td>
                        <?php echo htmlspecialchars($row['Vet_ID']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['vet_name']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['specialization']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['availability_status']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['qualification']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['license_number']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['email']); ?>
                    </td>

                    <td>
                        <?php echo htmlspecialchars($row['experienced_years']); ?>
                        years
                    </td>

                    <td>

                        <?php if (!empty($row['phone_number'])): ?>

                            <?php echo htmlspecialchars($row['phone_number']); ?>

                        <?php else: ?>

                            Not provided

                        <?php endif; ?>

                    </td>

                </tr>

            <?php endwhile; ?>

        <?php else: ?>

            <tr>

                <td colspan="9">
                    No veterinarians found.
                </td>

            </tr>

        <?php endif; ?>

    </table>


    <br>


    <a href="../dashboard.php" class="back-link">
        Back to Dashboard
    </a>

</div>

</body>

</html>

