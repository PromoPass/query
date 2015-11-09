<?php
    function getConsumerIDByDevice($DeviceID) {
       $sql = "SELECT ConsumerID
               FROM Consumer
               WHERE DeviceID = ?";
       $tableName = "Consumer";
       echo dbGetRecords($tableName, $sql, [$DeviceID]);
   }