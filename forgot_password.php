<?php
require("require/layout.php");
user_header();
user_navbar();


$can_request = true;
$remaining   = 0;

if (isset($_COOKIE['forgot_timer']) && $_COOKIE['forgot_timer'] > time()) {
    $can_request = false;
    $remaining   = $_COOKIE['forgot_timer'] - time();
}


if (isset($_GET['msg'])) {
    echo '<p style="color:green;">' . $_GET['msg']. '</p>';
}
?>

<form method="POST" action="processes/general_process.php">
    <input type="email" name="email" required placeholder="Your email"><br><br>

    <input type="submit" name="send_password" value="Send Password"
           id="sendBtn" <?= $can_request ? '' : 'disabled' ?>>

    <?php if (!$can_request): ?>
        <p id="countdown" style="color:red;"></p>
        <script>
            let seconds = <?= $remaining ?>;
            const countdownEl = document.getElementById("countdown");
            const sendBtn = document.getElementById("sendBtn");
            sendBtn.disabled = true;

            function updateCountdown() {
                if (seconds <= 0) {
                    countdownEl.textContent = "You can now request again.";
                    sendBtn.disabled = false;
                    return;
                }

                const mins = Math.floor(seconds / 60);
                const secs = seconds % 60;
                countdownEl.textContent = `Please wait ${mins}:${secs < 10 ? '0' : ''}${secs} before requesting again.`;
                seconds--;
            }

            updateCountdown();
            const interval = setInterval(() => {
                updateCountdown();
                if (seconds <= 0) clearInterval(interval);
            }, 1000);
        </script>
    <?php endif; ?>
</form>

<?php
user_footer();
