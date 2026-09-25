<?php

require 'auth.php';
require_login();
require 'db.php';
require 'layout.php';

$uid = $_SESSION['user_id'];

$s = $conn->prepare(
    'SELECT * FROM users WHERE user_id=?'
);

$s->bind_param(
    'i',
    $uid
);

$s->execute();

$u = $s->get_result()->fetch_assoc();

$cases = 0;

if ($u['role'] === 'volunteer') {

    $q = $conn->prepare(
        'SELECT number_of_cases_handled
         FROM volunteer_stats
         WHERE user_id=?'
    );

    $q->bind_param(
        'i',
        $uid
    );

    $q->execute();

    $x = $q->get_result()->fetch_assoc();

    $cases = $x['number_of_cases_handled'] ?? 0;
}

page_top('Profile');

?>

<div class="profile-grid">

    <aside class="card profile-side">

        <img src="assets/cat-logo.png">

        <h2>
            <?= h($u['full_name']) ?>
        </h2>

        <span class="tag">
            <?= h($u['role']) ?>
        </span>

    </aside>

    <section class="card">

        <h2>
            Account details
        </h2>

        <p>

            <b>Username:</b>
            <?= h($u['username']) ?>

            <br>

            <b>Email:</b>
            <?= h($u['email']) ?>

            <br>

            <b>Phone:</b>
            <?= h($u['phone'] ?: '—') ?>

            <br>

            <b>BRACU ID:</b>
            <?= h($u['bracu_id'] ?: '—') ?>

            <br>

            <b>Joined:</b>
            <?= h($u['join_date']) ?>

            <?php if ($u['role'] === 'volunteer'): ?>

                <br>

                <b>Cases handled:</b>
                <?= $cases ?>

            <?php endif; ?>

        </p>

    </section>

</div>

<?php

page_bottom();

?>