<?php
    $projects = array("USMLE", "Blank");
    $projectId = $_POST["id"];
    
    if ($projects[$projectId] == "USMLE"){
        
        $progressArr = array_reverse(explode("\n",file_get_contents("qs.txt")));
        $dayNumber = count($progressArr) + 1;
        $qs = array_sum($progressArr);
        $total = file_get_contents("total.txt");
        $dateCurrent = file_get_contents("date.txt");
        $qsArr = explode("-",$dateCurrent);
        $year = $qsArr[0];
        $month = $qsArr[1];
        $day = $qsArr[2];
    
        $cdate = mktime(0, 0, 0, $month, $day, $year);
        $today = time();
        $difference = $cdate - $today;
        if ($difference < 0) { $difference = 0; }
        $difference = floor($difference/60/60/24);
        $qsLeft = $total - array_sum($progressArr);
        $qsPerDay = round(($qsLeft/$difference),1);
        
        //$dateToday = date_create();
        //$dateTodayString = date_format($dateToday,"d/m/Y");
        $hours = 0;
        $new_time = date("d-m-Y", strtotime("-$hours hours"));
        
        $progress = '';
        
        $progress .= "\n<div class=\"card\">";
        $progress .= "\n\t<h2>Day#$dayNumber</h2>";
        $progress .= "\n\t<h2 style=\"font-size:18px\">$new_time</h2>";
        $progress .= "\n\t<form id=\"progressForm\" class=\"progressForm\">";
        $progress .= "\n\t\t<div class=\"update-form\">";
        $progress .= "\n\t\t\t<p>Qs:</p>";
        $progress .= "\n\t\t\t<input type=\"number\" name=\"qs\" min=\"1\" required>";
        $progress .= "\n\t\t</div>";
        $progress .= "\n\t\t<input type=\"hidden\" name=\"day\" value=\"$dayNumber\">";
        $progress .= "\n\t\t<input type=\"hidden\" name=\"id\" value=\"$projectId\">";
        $progress .= "\n\t\t<input type=\"submit\" value=\"Add!\">";
        $progress .= "\n\t</form>";
        $progress .= "\n</div>";
        
        $delay = 0.25;
        foreach($progressArr as &$line){
          $dayNumber--;
          $hours = $hours + 24;
          $new_time = date("d-m-Y", strtotime("-$hours hours"));
          $progress .= "\n\t\t\t\t<div style=\"animation: slide 5s; animation-delay:" .$delay. "s; animation-iteration-count: 1;\" class=\"card\">";
          $progress .= "\n\t\t\t\t\t<form class=\"x-button progressForm\">";
          $progress .= "\n\t\t\t\t\t\t<input type=\"image\" src=\"x-button.png\">";
          $progress .= "\n\t\t\t\t\t\t<input type=\"hidden\" name=\"day\" value=\"$dayNumber\">";
          $progress .= "\n\t\t\t\t\t\t<input type=\"hidden\" name=\"id\" value=\"$projectId\">";
          $progress .= "\n\t\t\t\t\t\t<input type=\"hidden\" name=\"qs\" value=\"-1\">";
          $progress .= "\n\t\t\t\t\t</form>";
          $progress .= "\n\t\t\t\t\t<h2>Day#$dayNumber</h2>";
          $progress .= "\n\t\t\t\t\t<h2 style=\"font-size:18px\">$new_time</h2>";
          $progress .= "\n\t\t\t\t\t<p>Tally: $qs </p>";
          $progress .= "\n\t\t\t\t\t<meter value =\"" . $qs .  "\" max= \"$total\" min=\"0\"></meter>";
          $percent = round($qs*100/$total,1);
          $progress .= "\n\t\t\t\t\t<p>Completed: $percent%</p>";
          $progress .= "\n\t\t\t\t\t<form class=\"progressForm\">";
          $progress .= "\n\t\t\t\t\t\t<div class=\"update-form\">";
          $progress .= "\n\t\t\t\t\t\t\t<p>Qs:</p>";
          $progress .= "\n\t\t\t\t\t\t\t<input type=\"number\" name=\"qs\" min=\"1\" value=\"$line\" required>";
          $progress .= "\n\t\t\t\t\t\t</div>";
          $progress .= "\n\t\t\t\t\t\t<input type=\"hidden\" name=\"day\" value=\"$dayNumber\">";
          $progress .= "\n\t\t\t\t\t\t<input type=\"hidden\" name=\"id\" value=\"$projectId\">";
          $progress .= "\n\t\t\t\t\t\t<input type=\"submit\" value=\"Update!\">";
          $progress .= "\n\t\t\t\t\t</form>";
          $progress .= "\n\t\t\t\t</div>\n";
          $delay += 0.25;
          $qs -= $line;
        }


$xml=<<<XML
<data>
    <goal>
        <![CDATA[
            <p>Qs Left: <strong>$qsLeft</strong></p>
            <p>There are <strong>$difference</strong> days remaining</p>
            <p>Qs to complete per day: <strong>$qsPerDay</strong></p>
        ]]>
    </goal>
    <progress>
        <![CDATA[
            $progress
        ]]>
    </progress>
    <settings>
        <![CDATA[
            <div class="card">
                <form class="settingForm">
                    <p>Expected Date of Completion: <input type="date" name="argument" value="$dateCurrent" ></p>
                    <input type="hidden" name="parameter" value="dueDate">
                    <input type="hidden" name="id" value=$projectId>
                    <input type="submit" value="Update!">
                </form>
            </div>
            <div class="card">
                <form class="settingForm">
                    <p>Total Questions: <input type="number" name="argument" value="$total" min="1"></p>
                    <input type="hidden" name="parameter" value="total">
                    <input type="hidden" name="id" value=$projectId>
                    <input type="submit" value="Update!">
                </form>
            </div>
        ]]>
    </settings>
</data>
XML;

    }
    else {

$xml=<<<XML
<data>
    <goal>
        <![CDATA[<p>Select a Project</p>]]>
    </goal>
    <progress>
        <![CDATA[<p>Select a Project</p>]]>
    </progress>
    <settings>
        <![CDATA[<p>Select a Project</p>]]>
    </settings>
</data>
XML;

    }



header('Content-Type: application/xml');
print ($xml);
die();


?> 


