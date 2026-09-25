<?php

require 'auth.php';
require_login();
require 'db.php';
require 'layout.php';

$id = (int)($_GET['id'] ?? 0);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_SESSION['role'] === 'volunteer') {

    $status = $_POST['status'] ?? 'reported';

    $s = $conn->prepare(
        'UPDATE animal
         SET status=?
          WHERE animal_id=?'
    );

    $s->bind_param(
        'si',
        $status,
        $id
    );

    $s->execute();

    $uid = $_SESSION['user_id'];

    $h = $conn->prepare(
        'INSERT INTO animal_status_history(animal_id,status,changed_by)
         VALUES(?,?,?)'
    );

    $h->bind_param(
        'isi',
        $id,
        $status,
        $uid
    );

    $h->execute();

    header(
        'Location: animal.php?id=' .
        $id .
        '&msg=' .
        urlencode('Status updated.')
    );

    exit;
}

$s = $conn->prepare(
    'SELECT a.*,u.full_name
     FROM animal a
     JOIN users u ON a.user_id=u.user_id
     WHERE animal_id=?'
);

$s->bind_param(
    'i',
    $id
);

$s->execute();

$a = $s->get_result()->fetch_assoc();

if (!$a) {

    header(
        'Location: animals.php?msg=Animal+not+found'
    );

    exit;
}

$hist = $conn->prepare(
    'SELECT h.*,u.full_name
     FROM animal_status_history h
     LEFT JOIN users u ON h.changed_by=u.user_id
     WHERE animal_id=?
     ORDER BY changed_at DESC'
);

$hist->bind_param(
    'i',
    $id
);

$hist->execute();

$history = $hist->get_result();

page_top($a['name']);

?>

<div class="profile-grid">

    <aside class="card profile-side">

        <img src="assets/cat-logo.png">

        <h2>
            <?= h($a['name']) ?>
        </h2>

        <span class="tag">
            <?= h(str_replace('_', ' ', $a['status'])) ?>
        </span>

    </aside>

    <section class="card">

        <h2>
            Animal details
        </h2>

        <p>

            <b>Species:</b>
            <?= h($a['species']) ?>

            <br>

            <b>Gender:</b>
            <?= h($a['gender']) ?>

            <br>

            <b>Age:</b>
            <?= h($a['age'] ?: 'Unknown') ?>

            <br>

            <b>Found at:</b>
            <?= h($a['location_found']) ?>

            <br>

            <b>Pattern:</b>
            <?= h($a['pattern']) ?>

            <br>

            <b>Body colour:</b>
            <?= h($a['body_colour']) ?>

            <br>

            <b>Eye colour:</b>
            <?= h($a['eye_colour']) ?>

            <br>

            <b>Registered:</b>
            <?= h($a['date_registered']) ?>
            by
            <?= h($a['full_name']) ?>

        </p>

        <div class="actions">

            <a
                class="btn"
                href="adoptions.php?animal_id=<?= $id ?>"
            >
                Request adoption
            </a>

            <a
                class="btn secondary"
                href="reports.php?animal_id=<?= $id ?>"
            >
                Create report
            </a>

        </div>

        <?php if ($_SESSION['role'] === 'volunteer'): ?>

            <hr>

            <form method="post">

                <label>
                    <b>Update status</b>
                </label>

                <select
                    name="status"
                    style="padding:10px;margin:8px"
                >

                    <option value="reported">
                        Reported
                    </option>

                    <option value="rescued">
                        Rescued
                    </option>

                    <option value="under_treatment">
                        Under treatment
                    </option>

                    <option value="available">
                        Available
                    </option>

                    <option value="adopted">
                        Adopted
                    </option>

                </select>

                <button class="btn small">
                    Update
                </button>

            </form>

        <?php endif; ?>

    </section>

</div>

<div class="section-head">

    <h2>
        Status history
    </h2>

</div>

<div class="table-wrap">

    <table>

        <tr>

            <th>
                Status
            </th>

            <th>
                Changed at
            </th>

            <th>
                Changed by
            </th>

        </tr>

        <?php while ($h = $history->fetch_assoc()): ?>

            <tr>

                <td>
                    <?= h($h['status']) ?>
                </td>

                <td>
                    <?= h($h['changed_at']) ?>
                </td>

                <td>
                    <?= h($h['full_name'] ?? 'System') ?>
                </td>

            </tr>

        <?php endwhile; ?>

    </table>

</div>

<?php

page_bottom();

?>