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
        "stc_send_time"=> "",
        "stc_return_time"=> "",
    );

    $typeMapping = array(
        1 => 'ไม่ด่วน',   
        2 => 'น้อย',     
        3 => 'ปานกลาง',   
        4 => 'ด่วน',      
        5 => 'ด่วนมาก'    
    );

    $mappingDepcodeType = array(
        003 => '43 ตรวจสุขภาพ',
        005 => '42 ตรวจสุขภาพ',
        007 => '120 ห้องLA',
        010 => '14 ห้องฉุกเฉิน',
        032 => '140 X-RAY',
        047 => '50 ผู้ป่วย',
        024 => '08 เเพทย์เเผนไทย',
        027 => '35 ห้องไต',
        070 => '99 กลับบ้าน',
        126 => 'หอผู้ป่วย',
        157 => 'ward อายุรกรรม',
        170 => '002 ซักประวัติ'
    );
    
    $stcType = array(
        1 => 'นอน',
        3 => 'นั่ง',
        4 => 'นั่ง(มีล้อแล้ว)',
        5 => 'ล้อเข็นนอนออกซิเจน'
    );
    

    $sql = "SELECT * FROM stretcher_register WHERE stretcher_priority_id = 1";
    error_log("Executing query: $sql");

    $sql = "SELECT kskdepartment1.department AS send_department, kskdepartment2.department AS from_department, stretcher_type.stretcher_type_name FROM stretcher_register JOIN kskdepartment AS kskdepartment1 ON kskdepartment1.depcode = stretcher_register.send_depcode JOIN kskdepartment AS kskdepartment2 ON kskdepartment2.depcode = stretcher_register.from_depcode JOIN stretcher_type ON stretcher_type.stretcher_type_id = stretcher_register.stretcher_type_id";
    error_log("Executing query: $sql");

    $sql = "SELECT stretcher_request_staff.name FROM stretcher_request_staff JOIN stretcher_register ON stretcher_register.ผู้รับ = stretcher_request_staff.Line_name";
    error_log("Executing query: $sql");
    
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $found = false;

        for ($priority = 5; $priority >= 1; $priority--) {
            mysqli_data_seek($result, 0);
            while ($row = $result->fetch_assoc()) {
                if ($row['stretcher_work_status_id'] == $priority) {
                    $requestResult['ID'] = $row['stretcher_register_id'];
                    $requestResult['Caller'] = $row['doctor_request'];
                    $requestResult['status'] = $typeMapping[$row['stretcher_work_status_id']];
                    $requestResult['location'] = $mappingDepcodeType[$row['from_depcode']];
                    $requestResult['Type'] = $stcType[$row['stretcher_type_id']];
                    $requestResult['locations'] = $mappingDepcodeType[$row['send_depcode']];
                    $requestResult['Patient'] = $row['hn'];
                    $requestResult['reciver'] = $row['ผู้รับ'];
                    $requestResult['stc_send_time'] = $row['เวลารับ'];
                    $requestResult['stc_return_time'] = $row['เวลาส่ง'];

                    $found = true;
                    break;
                }
            }
            if ($found) break;
        }
    } else {
        error_log("No rows found with stretcher_priority_id = 1");
    }

    $stmt->close();
    return $requestResult;



}
?>
