<?php
class QandaInfo
{
    protected $tableName = "qanda_records";

    public function saveData($conn, $firstname, $lastname, $email, $device, $question, $sectionVal)
    {
        $sql = "INSERT INTO " . $this->tableName . " (first_name, last_name, email, user_ip, user_device, question, section, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        $ip = $_SERVER['REMOTE_ADDR'];
        $currentTimeStamp = date('Y-m-d H:i:s');
        mysqli_stmt_bind_param($stmt, 'ssssssss', $firstname, $lastname, $email, $ip, $device, $question, $sectionVal, $currentTimeStamp);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function getData($conn, $orderDesc = false, $filter = [])
    {
        $paginationData = $filter['pagination'] ?? null;
        $dataResult = [];
        $page = $paginationData['page'] ?? 1;
        $limit = $paginationData['limit'] ?? 20;
        if ($paginationData) {
            $offset = ($page - 1) * $limit;
            $filter['pagination_query'] = " LIMIT $limit OFFSET $offset ";
        }

        $conditionQuery = '';
        if (!empty($filter['conditions'])) {
            $conditions = [];
            foreach ($filter['conditions'] as $key => $value) {
                $conditions[] = "$key = '" . mysqli_real_escape_string($conn, $value) . "'";
            }
            if (!empty($conditions)) {
                $conditionQuery .= " WHERE " . implode(' AND ', $conditions);
            }
        }

        // get count    
        $countSql = "SELECT COUNT(*) as total FROM " . $this->tableName;
        $countSql .= $conditionQuery;
        $countResult = mysqli_query($conn, $countSql);
        $countRow = mysqli_fetch_assoc($countResult);

        $dataResult['total'] = $countRow['total'];
        $dataResult['page'] = $page;
        $dataResult['limit'] = $limit;
        $dataResult['pages'] = ceil($countRow['total'] / $limit);

        $sql = "SELECT * FROM " . $this->tableName;
        $sql .= $conditionQuery;
        $sql .= $filter['pagination_query'] ?? '';

        if ($orderDesc) {
            $sql .= " ORDER BY id DESC";
        }
        $result = mysqli_query($conn, $sql);
        $rows = array();
        while ($row = mysqli_fetch_array($result)) {
            $rows[] = $row;
        }

        $dataResult['data'] = $rows;

        return $dataResult;
    }

    public function exportCSV($conn, $filterOptions = [])
    {
        $filterOptions = ['conditions' => $filterOptions];

        $dataInfo = $this->getData($conn, false, $filterOptions);
        $data = $dataInfo['data'] ?? [];
        $filename = 'q_and_a_export_' . time() . '.csv';

        $csv_data = '';

        // add heders
        $header = array('ID', 'Status', 'Question', 'Time', 'Date', 'ip', 'device');

        $csv_data .= implode(',', $header) . "\n";

        foreach ($data as $row) {
            // $csv_data .= implode(',', $row) . "\n";
            $csv_data .= $row['id'] . ",";
            $csv_data .= ($row["status"] ? 'Show' : 'Hide') . ",";
            $csv_data .= '"' . str_replace('"', '""', $row["question"]) . '",';

            $createdAt = $row['created_at'];
            $createdAtTime = '';
            $createdAtDate = '';
            if ($createdAt) {
                $createdAtDateTime = new DateTime($createdAt);
                $createdAtTime = $createdAtDateTime->format("hia");
                $createdAtDate = $createdAtDateTime->format("d F");
            }

            $csv_data .= $createdAtTime . ",";
            $csv_data .= $createdAtDate . ",";
            $csv_data .= '"' . $row["user_ip"] . '",';
            $csv_data .= '"' . $row["user_device"] . '"';
            $csv_data .= "\n";
        }

        if (isset($_GET['debug']) && $_GET['debug'] === 'y') {
            echo $csv_data;
            exit;
        }
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        echo $csv_data;
    }

    public function exportCSV_old($conn, $filterOptions = [])
    {
        $filterOptions = ['conditions' => $filterOptions];

        $dataInfo = $this->getData($conn, false, $filterOptions);
        $data = $dataInfo['data'] ?? [];
        $filename = 'q_and_a_export_' . time() . '.csv';

        $csv_data = '';

        // add heders
        $header = array('ID', 'Last name', 'First name', 'Email', 'Question', 'Time', 'Date', 'Answer', 'ip', 'device');

        $csv_data .= implode(',', $header) . "\n";

        foreach ($data as $row) {
            // $csv_data .= implode(',', $row) . "\n";
            $csv_data .= $row['id'] . ",";
            // $csv_data .= ($row["status"] ? 'Show' : 'Hide') . ",";
            $csv_data .= $row["last_name"] . ",";
            $csv_data .= $row['first_name'] . ",";
            $csv_data .= $row["email"] . ",";
            $csv_data .= '"' . str_replace('"', '""', $row["question"]) . '",';

            $createdAt = $row['created_at'];
            $createdAtTime = '';
            $createdAtDate = '';
            if ($createdAt) {
                $createdAtDateTime = new DateTime($createdAt);
                $createdAtTime = $createdAtDateTime->format("hia");
                $createdAtDate = $createdAtDateTime->format("d F");
            }

            $csv_data .= $createdAtTime . ",";
            $csv_data .= $createdAtDate . ",";
            $csv_data .= (isset($row["answer"]) && is_string($row["answer"]) && !empty($row["answer"])) ? '"' . str_replace('"', '""', $row["answer"]) . '",' : '"",';
            $csv_data .= '"' . $row["user_ip"] . '",';
            $csv_data .= '"' . $row["user_device"] . '"';
            $csv_data .= "\n";
        }

        if (isset($_GET['debug']) && $_GET['debug'] === 'y') {
            echo $csv_data;
            exit;
        }
        header('Content-type: application/csv');
        header('Content-Disposition: attachment; filename=' . $filename);

        echo $csv_data;
    }
    public function updateStatus($conn, $id, $status)
    {
        $sql = "UPDATE " . $this->tableName . " SET status = ? WHERE id = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ii', $status, $id);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

    public function hideAll($conn)
    {
        $sql = "UPDATE " . $this->tableName . " SET status = ? WHERE status = ?";
        $stmt = mysqli_prepare($conn, $sql);
        $targetStatus = 0;
        $currentStatus = 1;
        mysqli_stmt_bind_param($stmt, 'ii', $targetStatus, $currentStatus);
        $result = mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        return $result;
    }

}