<!-- Modal -->
<div class="modal fade" id="messageModal" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center" id="display-message"></div>
                <div class="btn-ok-field text-center"><button type="button" class="btn btn-secondary btn-ok"
                        data-bs-dismiss="modal"><?= $langObj->getText("ok") ?></button></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="messageModal2" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div class="text-center" id="display-message2"></div>
                <div class="btn-float-close"><button type="button" class="btn-foat-close-style" data-bs-dismiss="modal">x</button></div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modal-lg" id="messageModal3" tabindex="-1" aria-labelledby="messageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body">
                <div id="display-message3"></div>
                <div class="btn-ok-field text-center"><button type="button" class="btn btn-secondary btn-ok"
                data-bs-dismiss="modal"><?= $langObj->getText("close") ?></button></div>
            </div>
        </div>
    </div>
</div>
<script>
    function messageDisplay(msg) {
        document.getElementById('display-message').innerHTML = msg;
        var myModal = new bootstrap.Modal(document.getElementById('messageModal'), {
            keyboard: false,
        });
        myModal.show();
    }
    function messageDisplay2(msg) {
        document.getElementById('display-message2').innerHTML = msg;
        var myModal = new bootstrap.Modal(document.getElementById('messageModal2'), {
            keyboard: false,
        });
        myModal.show();
    }
    function messageDisplay3(msg) {
        document.getElementById('display-message3').innerHTML = msg;
        var myModal = new bootstrap.Modal(document.getElementById('messageModal3'), {
            keyboard: false,
        });
        myModal.show();
    }
    function policyDisplay() {
        var policyText = `<?= addslashes(file_get_contents('template/policy-content.html')) ?>`;
        messageDisplay3(policyText);
    }
</script>
<style>
    #display-message {
        font-weight: bold;
    }

    #display-message2 {
        padding: 30px 10px;
        font-size: 20px;
        font-weight: bold;
        color: black;
    }

    .btn-foat-close-style {
        background-color: #614989;
        color: white;
        border-radius: 50%;
    }

    .btn-float-close {
        position: absolute;
        top: 6%;
        right: 2%;
        line-height: 1;
        padding-top: 4px;
        font-size: 23px;
    }
</style>