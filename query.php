<?php
include("conn.php");

function getRequestResult() {
    global $conn;
    $requestResult = array(
        "ID"=> "1",
        "Caller"=> "",
        "status"=> "",
        "location"=> "",
        "Type"=> "",
        "locations"=> "",
        "Sender"=> "",
        "Patient"=> "",
        "reciver"=> "",
        "PatientID"=> "",
    );

    $typeMapping = array(
        1 => 'ไม่ด่วน',   
        2 => 'น้อย',     
        3 => 'ปานกลาง',   
        4 => 'ด่วน',      
        5 => 'ด่วนมาก'    
    );


    $sql = "SELECT * FROM stretcher_register WHERE stretcher_work_status_id = 1";
    error_log("Executing query: $sql");

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $found = false;

        for ($priority = 5; $priority >= 1; $priority--) {
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()) {
                if ($row['stretcher_type_id'] == $priority) {
                    $requestResult['ID'] = $row['stretcher_register_id'];
                    $requestResult['Caller'] = $row['doctor_request'];
                    $requestResult['status'] = $typeMapping[$row['stretcher_type_id']];
                    $requestResult['location'] = $row['from_note'];
                    $requestResult['Type'] = $row['stretcher_type_id'];
                    $requestResult['locations'] = $row['send_note'];
                    $requestResult['Patient'] = $row['hn'];
                    $requestResult['reciver'] = $row['ผู้รับ'];
                    $found = true;
                    break;
                }
            }
            if ($found) break;
        }
    } else {
        error_log("No rows found with stretcher_work_status_id = 1");
    }

    $stmt->close();
    return $requestResult;
}
?>
