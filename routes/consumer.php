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
		   $sql = "SELECT ReceivedAdID, AdID, ReceivedAd.BusinessID
				   FROM ReceivedAd
				INNER JOIN Preferences on ReceivedAd.BusinessID = Preferences.BusinessID
					AND ReceivedAd.ConsumerID = Preferences.ConsumerID
				   WHERE ReceivedAd.ConsumerID = ?
				   AND IsCleared = 0
				   AND IsSaved = 0
					AND (IsBlocked = null || IsBlocked = 0)
				ORDER BY IsFavorite desc, ReceivedDate DESC";
		   $tableName = "ReceivedAd";
		   echo dbGetRecords($tableName, $sql, [$ConsumerID]);
	   }	   
