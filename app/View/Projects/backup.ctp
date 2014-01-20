<div class="grid_12">

<br/>

<h6>
CSV backup 
<?php
    // Provide CSV backups for each year, starting in 2012.
    $year = date('Y');
    $y = 2012;
    while($y <= $year) {
        echo $this->Html->link($y, array('action' => 'csvbackup', $y)) . ' ';
        $y++;
    }
?>
- change the extension from *.csv.html to *.csv and open with Microsoft Excel.
</h6>

<h6><?php echo $this->Html->link('SQL backup', array('action' => 'sqlbackup')); ?> - used to reconstruct the database in the event of data loss.</h6>

</div>
