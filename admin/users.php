<?php
session_start();



require("../require/dashboard_layout.php");
require("../require/layout.php");
// require("../functions/user_functions.php");

if (!isset($_SESSION['user']['email'])) {
    header("location:../login.php?Please Login First!...");
    die();
} else if (isset($_SESSION['user']['email']) and $_SESSION['user']['role_id'] == 2) {
    header("location:../index.php");
    die();
}
$type = isset($_GET['type']) ? $_GET['type'] : 'all';
//   user_navbar();
admin_header();
admin_navbar();

?>



<main class="col-12 col-md-9 dashboard p-4 mt-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="heading-2">All Accounts</h2>
        <a href="add_user.php" class="btn btn-primary btn-sm">+ New Account</a>
    </div>

    
    <div class="table-responsive" id="table-responsive">

    </div>
</main>

<div id="loadingModal" style="display: none; position: fixed; z-index: 9999; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); display: flex; justify-content: center; align-items: center;
">
    <div style="background: white; padding: 30px 40px; border-radius: 12px; box-shadow: 0 0 15px rgba(0,0,0,0.3);text-align: center;width: 200px;">
        <img src="https://i.gifer.com/ZZ5H.gif" alt="Loading..." style="width: 50px; height: 50px; margin-bottom: 15px;">
        <div style="font-size: 18px; font-weight: 600; color: #333;">Loading...</div>
    </div>
</div>



<script>
    function show_users() {
        document.getElementById('loadingModal').style.display = 'flex';

        var ajax = null;
        if (window.XMLHttpRequest) {
            ajax = new XMLHttpRequest;
        } else {
            ajax = new ActiveXObject("Microsoft.XMLHTTP");
        }
        ajax.onreadystatechange = function() {
            if (ajax.readyState === 4 && ajax.status === 200) {
                
                const container = document.getElementById("table-responsive");
                container.innerHTML = ajax.responseText;

                
                $('#blogsTable').DataTable({
                    destroy: true, 
                    responsive: true,
                    autoWidth: false,
                    lengthChange: true,
                    pageLength: 5,
                    scrollX: true
                });

                
                document.getElementById('loadingModal').style.display = 'none';
            }
        };

        ajax.open("POST", "../processes/user_process.php");
        ajax.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        ajax.send("action=show_users&type=<?= $type ?>");
    }

    
    show_users();

    function approval(user_id) {
        let approvalValue = document.getElementById("approval" + user_id).value;

        document.getElementById('loadingModal').style.display = 'flex';

        var ajax_request = null;
        if (window.XMLHttpRequest) {
            ajax_request = new XMLHttpRequest;
        } else {
            ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
        }
        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                document.getElementById("is_approved" + user_id).innerHTML = ajax_request.responseText;

                document.getElementById('loadingModal').style.display = 'none';
                show_users();
            }
        }
        ajax_request.open("POST", "../processes/user_process.php");
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        ajax_request.send("action=approval&user_id=" + user_id + "&approval_value=" + approvalValue);
    }

    function active(user_id) {
        let activeValue = document.getElementById("is_active" + user_id).innerHTML;

        var ajax_request = null;
        if (window.XMLHttpRequest) {
            ajax_request = new XMLHttpRequest;
        } else {
            ajax_request = new ActiveXObject("Microsoft.XMLHTTP");
        }

        
        document.getElementById('loadingModal').style.display = 'flex';

        ajax_request.onreadystatechange = function() {
            if (ajax_request.readyState == 4 && ajax_request.status == 200) {
                document.getElementById("is_active" + user_id).innerHTML = ajax_request.responseText;
                
                document.getElementById('loadingModal').style.display = 'none';
                show_users();
            }
        }

        ajax_request.open("POST", "../processes/user_process.php");
        ajax_request.setRequestHeader("Content-type", "application/x-www-form-urlencoded");

        ajax_request.send("action=is_active&user_id=" + user_id + "&activeValue=" + activeValue);
    }
</script>

<?php
admin_footer();
?>