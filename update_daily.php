<?php
    $qs = $_POST['qs'];
    $day = $_POST['day'];

    if ($qs != '' or $day= ''){
        $my_file = 'qs.txt';
        $current = file_get_contents($my_file);
        $qsArr = explode("\n",$current);
        if ($qs != -1){
            $days = count($qsArr);
            //echo 'count:' . $days;
            if ($day > $days){
                $current .= "\n$qs";
                file_put_contents($my_file, $current);
                echo 'Added' . $qs . ' to Day#' . $day;
            }
            else {
                
                $qsArr[$day-1] = $qs;
                $current = implode("\n",$qsArr);
                file_put_contents($my_file, $current);
                echo 'Updated Day#' . $day . ' to ' . $qs;
            }
        } 
        else {
            unset($qsArr[$day-1]);
            $current = implode("\n",$qsArr);
            file_put_contents($my_file, $current);
            echo 'Deleted' .  $day;
        }
        
    } 
    else {
        header("Refresh:0; url=index.php");
    }
?>