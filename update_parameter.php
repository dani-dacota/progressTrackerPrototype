<?php
    $parameter = $_POST['parameter'];
    $argument = $_POST['argument'];

    if ($parameter != '' or $argument= ''){
        if ($parameter == 'dueDate') {
            $my_file = 'date.txt';
            $date = $argument;
            file_put_contents($my_file, $date);
            echo 'Changed Due Date to '. $date;
        }
        if ($parameter == 'total') {
            $my_file = 'total.txt';
            $total = $argument;
            file_put_contents($my_file, $total);
            echo 'Changed Total to '. $total;
        }
    } 
    else {
        header("Refresh:0; url=index.php");
    }
?>