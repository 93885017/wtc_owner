<?php
require_once 'admin_ini.php';

include_once '../class/QandaInfo.php';
$QandaInfoObj = new QandaInfo();

$filter = [];

if (isset($_POST['hide_all_btn'])) {
    $updateResult = $QandaInfoObj->hideAll($conn);
    if ($updateResult) {
        echo "<script>alert('All questions have been hidden successfully.');</script>";
    } else {
        echo "<script>alert('Error: Fail to reset the record(s).');</script>";
    }
}


$itemPerPage = 50;
$page = isset($_GET['page']) ? (int) $_GET['page'] : 1;

$filter['pagination'] = [
    'limit' => $itemPerPage,
    'page' => $page,
];

// only show active question in the listing
$filter['conditions']['status'] = '1';
$dataInfo = $QandaInfoObj->getData($conn, false, $filter);

$data = $dataInfo['data'] ?? [];
?>
<html>

<head>
    <?php require_once 'admin.header.php'; ?>
</head>
<body>
    <div class="container">
        <h1>Admin Panel</h1>
        <br>
        <?php require_once 'admin.common.php'; ?>
        <h1>Q and A listing</h1>
        <br>
        <div class="table-responsive">
            <p>Total record(s): <?= $dataInfo['total'] ?? 0 ?></p>

            <form action="" id="frm_reset_all" method="post">
                <input type="submit" name="hide_all_btn"  value="Hide All Record(s)" <?=isset($dataInfo['total']) && $dataInfo['total'] > 0 ? '' : 'disabled'?>>
            </form>
            
            <table class="table table-striped table-bordered">
                <thead class="thead-dark">
                    <tr>
                        <th>ID</th>
                        <!-- <th>First Name</th>
                        <th>Last Name</th>
                        <th>Email</th> -->
                        <td>Question</th>
                        <!-- <td>Answer</th> -->
                        <th>Created at</th>
                        <!-- <th>Updated at</th> -->
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if (count($data) > 0) {
                        foreach ($data as $row) {
                            $createdAt = $row['created_at'];
                            $updatedAt = $row['updated_at'];
                            
                            echo "<tr>";
                            echo "<td>" . $row['id'] . "<input type='hidden' name='id' value='" . $row['id'] . "'></td>";
                            // echo "<td>" . $helperObj->outputString($row['first_name']) . "</td>";
                            // echo "<td>" . $helperObj->outputString($row["last_name"]) . "</td>";

                            // echo "<td>" . $helperObj->outputString($row["email"]) . "</td>";

                            echo "<td>" . $helperObj->outputString($row["question"]) . "</td>";
                            // echo "<td>" . $helperObj->outputString($row["answer"]) . "</td>";
                            
                            echo "<td>" . $helperObj->outputString($createdAt) . "</td>";
                            // echo "<td>" . $helperObj->outputString($updatedAt) . "</td>";
                            echo "<td><button class='btn-deactivate' data-id='" . $row['id'] . "'>hide</button></td>";
                            
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='15'>No records found.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
            <?php
            $totalItems = $dataInfo['total'] ?? 0;
            $totalPages = ceil($totalItems / $itemPerPage);

            if ($totalPages > 1) {
                echo "<div class='pagination'>";

                // Show the "First" page button
                if ($page > 1) {
                    echo "<a href='?page=1'>&laquo; First</a>";
                }

                // Calculate the range of pages to display
                $start = max(1, $page - 5); // Start 5 pages before the current page
                $end = min($totalPages, $page + 4); // End 4 pages after the current page
            
                // Show the range of pages
                for ($i = $start; $i <= $end; $i++) {
                    echo "<a href='?page=$i' class='" . ($i == $page ? 'active' : '') . "'>$i</a>";
                }

                // Show the "Last" page button
                if ($page < $totalPages) {
                    echo "<a href='?page=$totalPages'>Last &raquo;</a>";
                }

                echo "</div>";
            }
            ?>
        </div>
        <script>
            $(document).ready(function () {
                $('#frm_reset_all').submit(function (e) {
                    if (!confirm('確定隱藏所有問題?')) {
                        e.preventDefault(); // Prevent form submission if user cancels
                    }
                });

                $('.btn-deactivate').click(function () {
                    var id = $(this).data('id');
                    if (confirm('Are you sure you want to hide this question?')) {
                        $.ajax({
                            url: 'qanda_action.php',
                            type: 'POST',
                            data: {
                                action: 'deactivate',
                                id: id
                            },
                            success: function (response) {
                                response = JSON.parse(response);
                                if (response.status === 'success') {
                                    alert('Question hidden successfully.');
                                    location.reload();
                                } else {
                                    alert('Error: ' + response.message);
                                }
                            },
                            error: function () {
                                alert('An error occurred while processing the request.');
                            }
                        });
                    }
                });
            });
        </script>
        <style>
        .pagination {
            display: flex;
            justify-content: center;
            /* Center the pagination */
            padding: 10px 0;
            list-style: none;
        }

        .pagination a {
            color: black;
            float: left;
            padding: 8px 16px;
            text-decoration: none;
            border: 1px solid #ddd;
            margin: 0 4px;
            border-radius: 4px;
            transition: background-color 0.3s, color 0.3s;
        }

        .pagination a:hover {
            background-color: #007bff;
            /* Hover background color */
            color: white;
            /* Hover text color */
            border-color: #007bff;
        }

        .pagination a.active {
            background-color: #007bff;
            /* Active page background color */
            color: white;
            /* Active page text color */
            border-color: #007bff;
        }
    </style>
</body>

</html>