<?php
    function addConsumer() {
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
        
        $consumer = json_decode($request->getBody());
        $sql = "INSERT INTO Consumer (DeviceID) 
                VALUES (?)";
        echo dbAddRecords($sql, [ $consumer->DeviceID
                                ],
                                $consumer);
    }
    function getConsumerIDByDevice($DeviceID) {
       $sql = "SELECT ConsumerID
               FROM Consumer
               WHERE DeviceID = ?";
       $tableName = "Consumer";
       echo dbGetRecords($tableName, $sql, [$DeviceID]);
    }
    	function getReceivedAdsNotClearedOrSaved($ConsumerID) {
		   $sql = "SELECT ReceivedAdID, AdID, BusinessID
				   FROM ReceivedAd
				   WHERE ConsumerID = ?
				   AND IsCleared = 0
				   AND IsSaved = 0
				ORDER BY ReceivedDate DESC";
		   $tableName = "ReceivedAd";
		   echo dbGetRecords($tableName, $sql, [$ConsumerID]);
	   }	   
