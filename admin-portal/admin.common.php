<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <div class="container-fluid">
        <a class="navbar-brand" href="#">Menu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="index.php<?='?t=' . time()?>">&bull; Set Page</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="page_setting.php<?='?t=' . time()?>">&bull; Page Setting</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="qanda.php<?='?t=' . time()?>">&bull; Q&A Records</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="javascript:void(0);" onclick="exportData()">&bull; Export Q&A record</a>
                </li> -->
                <!-- <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="login_password.php<?='?t=' . time()?>">&bull; Login Password</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="import_users.php<?='?t=' . time()?>">&bull; Import users</a>
                </li>
            </ul>
        </div>
    </div>
</nav>
<br>
<script>
    function exportData(type) {
        var t = new Date().getTime();
        window.location.href = 'export_data.php?t=' + t;
    }
</script>
<style>
    .container {
        text-align: center;
        width: 100%;
        padding-top: 2%;
    }

    .message-field {
        margin-top: 20px;
        font-size: 11px;
    }

    .success {
        color: green;
    }

    .error {
        color: red;
    }
</style>