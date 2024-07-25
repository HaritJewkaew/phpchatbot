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
    
    $sql = "SELECT * FROM stretcher_register WHERE stretcher_work_status_id = 1";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $urgentRowFound = false;
        while ($row = $result->fetch_assoc()) {
            if ($row['stretcher_type_id'] == 'ด่วนมาก') {
                $requestResult['ID'] = $row['stretcher_register_id'];
                $requestResult['Caller'] = $row['doctor_request'];
                $requestResult['status'] = $row['stretcher_type_id'];
                $requestResult['location'] = $row['from_note'];
                $requestResult['Type'] = $row['stretcher_work_result_detail'];
                $requestResult['locations'] = $row['send_note'];
                $requestResult['Patient'] = $row['hn'];
                $requestResult['reciver'] = $row['ผู้รับ'];
                $urgentRowFound = true;
                break;
            }
        }

        if (!$urgentRowFound) {
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()) {
                if ($row['stretcher_type_id'] == 'ด่วน') {
                    $requestResult['ID'] = $row['stretcher_register_id'];
                    $requestResult['Caller'] = $row['doctor_request'];
                    $requestResult['status'] = $row['stretcher_type_id'];
                    $requestResult['location'] = $row['from_note'];
                    $requestResult['Type'] = $row['stretcher_work_result_detail'];
                    $requestResult['locations'] = $row['send_note'];
                    $requestResult['Patient'] = $row['hn'];
                    $requestResult['reciver'] = $row['ผู้รับ'];
                }
            }
        }
    }

    $stmt->close();
    return $requestResult;

    $sql = "SELECT * FROM project";
    $result = $conn->query($sql);
    
    // Check if there are any results
    if ($result->num_rows > 0) {
        // Output data of each row
        while($row = $result->fetch_assoc()) {
            echo "ID: " . $requestResult['ID']. " - Name: " . $requestResult['Caller']. "<br>";
        }
    } else {
        echo "0 results";
    }
}
?>