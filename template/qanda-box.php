<?php
$minChar = FORM_MIN_CAHR;
$maxChar = FORM_MAX_CAHR;
?>
<div class="qanda-field">
    <div id="closeBtn" class="close-btn"><img src="style/images/qanda-colse-btn.jpg" border=0 /></div>
    <div class="question-field">
        <textarea id="question" name="question" class="question-input"
            placeholder="<?= $langObj->getText('Please input your question') ?>"></textarea>
    </div>
    <div>
        <!-- <div class="qanda-policy-view" onclick="openPolicy()"><u><?= $langObj->getText('Privacy Policy') ?></u> -->
    </div>
    <div id="qnadSubmit" class="qanda-btn-submit submit-button"><?= $langObj->getText('submit') ?></div>
    <div style="clear:both;"></div>
</div>
</div>
</div>
<?php
$csrf_token = $_SESSION['csrf_token'];
?>
<script>
    $(document).ready(function () {
        $("#closeBtn").click(function () {
            $('.qanda-field').hide();
        });

        $('#qandaBtn').click(function () {
            var topValue = $('#main-video').height() - $('.qanda-field').outerHeight();
            $('.qanda-field').css('top', topValue);
            $('.qanda-field').show();
            $('html, body').animate({
                scrollTop: $(".qanda-field").offset().top - 80
            }, 500);
        });

        $('#qnadSubmit').click(function () {
            var question = document.getElementById('question').value;

            if (question == '') {
                messageDisplay('<?= $langObj->getText('fill_question') ?>');
                return;
            }

            if (question.length < <?= $minChar ?>) {
                messageDisplay('<?= $langObj->getText('fill_question') ?> <?= $langObj->getText('fill_min_text_err') ?>');
                return;
            }
            if (question.length > <?= $maxChar ?>) {
                messageDisplay('<?= $langObj->getText('fill_question') ?> <?= $langObj->getText('fill_max_text_err') ?>');
                return;
            }

            $.ajax({
                type: "POST",
                url: "submit_qanda.php",
                data: {
                    question: question,
                    lang: '<?= $lang ?>',
                    section: '<?= $pageSection ?>',
                    csrf_token: '<?= $_SESSION['csrf_token'] ?>'
                },
                success: function (response) {
                    response = JSON.parse(response);
                    if (response.status == 'success') {
                        messageDisplay2(response.message);
                        $('#question').val('');
                    } else {
                        messageDisplay('<?= $langObj->getText('submit_fail') ?>');
                    }
                },
                error: function () {
                    messageDisplay('<?= $langObj->getText('submit_fail') ?>');
                }
            });
        });
    });

    function openPolicy() {
        policyDisplay();
    }
</script>
<style>
    #closeBtn img {
        width: 100%;
    }

    .question-field {
        margin-top: 3%;
    }

    .qanda-field {
        display: none;
        position: absolute;
        background-color: #dfdfdf;
        top: 33%;
        width: 35%;
        left: 32.5%;
        padding: 1%;
        font-size: 1vw;
        color: black;
    }

    .qanda-field .input-style {
        border: 1px solid #000;
        width: 100%;
        padding: 1% 2% 0.5% 2%;
        background-color: #dfdfdf;
        font-size: 0.8vw;
    }

    .qanda-field .input-style::placeholder {
        color: #000;
    }

    .qanda-field .input-lable-text {
        margin-bottom: 0.7%;
        font-size: 0.9vw;
    }

    .close-btn {
        padding: 0;
        position: absolute;
        top: 1%;
        right: 1%;
        width: 2%;
        color: white;
        cursor: pointer;
    }

    .qanda-btn-submit {
        float: right;
        border: 1px;
        border-style: solid;
        border-width: thin;
        border-color: #000;
        padding: 0.5% 1% 0 1%;
        font-size: 0.7vw;
        cursor: pointer;
        margin-top: 1.5%;
    }

    .qanda-policy-view {
        float: left;
        cursor: pointer;
        font-size: 0.7vw;
        margin-top: 2.5%;
    }

    button#qnadSubmit {
        font-size: 0.7vw;
    }

    .qanda-half-left {
        padding-right: 1%;
    }

    .qanda-half-right {
        padding-left: 1%;
    }

    .question-input {
        width: 100%;
        height: 100%;
        box-sizing: border-box;
    }

    /* Styles for mobile devices */
    @media only screen and (max-width: 759px) {

        /* CSS rules for mobile devices go here */
        .qanda-field {
            position: absolute;
            background-color: #dfdfdf;
            top: 33%;
            width: 60%;
            left: 20%;
            padding: 2%;
            font-size: 12px;
            color: black;
        }

        .question-field {
            margin-top: 5%;
        }

        .close-btn {
            padding: 0;
            position: absolute;
            top: -2%;
            right: 1%;
            width: 5%;
            color: white;
            cursor: pointer;
        }

        .qanda-btn-submit {
            float: none;
            background: white;
            font-size: 10px;
            text-align: center;
            border: 1px;
            border-style: solid;
            border-width: thin;
            border-color: #000;
            padding: 0.5% 1% 0 1%;
        }
    }
</style>