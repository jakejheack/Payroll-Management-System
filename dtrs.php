<?php
if(isset($_POST['submit']))
{
    $date1 = strtotime($_POST['date1']);
    $date2 = strtotime($_POST['date2']);
    $interval = $date2 - $date1;
    $playedtime = $interval / 1440;
    $day = $playedtime / 60;

    echo $interval.'<br>';
    echo $playedtime.'<br>';
    echo $day.'<br>';

    $dated1 = $_POST['date1'];
    $dated2 = $_POST['date2'];

    echo $dated1.'<br>';
    echo $dated2.'<br>';

    // do{
    //     echo $dated1.'<br>';
    //     $dated1++;
    // }while($dated2 >= $dated1);
}

?>


<table>
    <thead>
        <tr>
        <th>Date</th>
        <th>Time-in</th>
        <th>Time-out</th>
        <th colspan="2">OT</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $dates = 1;
            do{
        ?>
        <tr>
            <td><?php echo $dates?></td>
            <td><select name="" id="">
                <option value="" disabled selected>Time-in</option>
                <?php
                    $time = 0;
                    do{
                        ?>
                            <option value="<?php $time; ?>"><?php echo $time.':00'; ?></option>
                        <?php
                        $time++;
                    }while($time < 24)
                ?>
            </select></td>
            <td><select name="" id="">
                <option value="" disabled selected>Time-out</option>
                <?php
                    $time = 0;
                    do{
                        ?>
                            <option value="<?php $time; ?>"><?php echo $time.':00'; ?></option>
                        <?php
                        $time++;
                    }while($time < 24)
                ?>
            </select></td>
            <td><select name="" id="">
                <option value="" disabled selected>Time-in</option>
                <?php
                    $time = 0;
                    do{
                        ?>
                            <option value="<?php $time; ?>"><?php echo $time.':00'; ?></option>
                        <?php
                        $time++;
                    }while($time < 24)
                ?>
            </select></td>
            <td><select name="" id="">
                <option value="" disabled selected>Time-out</option>
                <?php
                    $time = 0;
                    do{
                        ?>
                            <option value="<?php $time; ?>"><?php echo $time.':00'; ?></option>
                        <?php
                        $time++;
                    }while($time < 24)
                ?>
            </select></td>
        </tr>
        <?php
            $dates++;
            }while($dates < 32)
        ?>
    </tbody>
</table>