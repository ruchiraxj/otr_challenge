<?php
require_once('../configs/configs.php');
?>

<!DOCTYPE html>
<html>

<head>
    <title>Turnover Report</title>
</head>

<body>

    <div>
        <h2>Turnover Reports</h2>

        <label>Type</label>
        <select id="type">
            <option value="1">Turnover Per Brand</option>
            <option value="2">Turnover Per Day</option>
        </select>

        <br /><br />

        <label>Start Date</label>
        <input type="text" id="start_date" name="start_date" value="<?= START_DATE ?>"></input>

        <br /><br />
        <label>End Date</label>
        <input type="text" id="end_date" name="end_date" value="<?= END_DATE ?>"></input>
        <br /><br />
        <button id="submit_data">Download Report</button>
    </div>

    <script type="text/javascript">
        document.getElementById("submit_data").addEventListener("click", generateReport);


        function generateReport() {

            let url = "<?= BASE_URL ?>";
            let s = document.getElementById("type").value;

            url = (s == 2) ? url + "/report/dailyTurnover" : url + "/report/turnoverPerBrand";

            url += "?start_date=" + document.getElementById("start_date").value + '&end_date=' + document.getElementById("end_date").value;

            var xhr = new XMLHttpRequest();
            xhr.withCredentials = true;

            xhr.addEventListener("readystatechange", function() {
                if (this.readyState === 4) {
                    let response = this.responseText;
                    console.log(response);

                    response = JSON.parse(response);
                    if(response.success == true){
                        window.open(response.data, '_blank');
                    }else{
                        alert("Message from API : " + response.message);
                    }          
                }
            });

            xhr.open("GET", url);
            xhr.setRequestHeader("Authorization", "67b1c2ab-e4cf-3ddd-81bb-c7a9b30691f1");
            xhr.setRequestHeader("Content-Type", "application/json");
            xhr.send();
        }
    </script>
</body>

</html>