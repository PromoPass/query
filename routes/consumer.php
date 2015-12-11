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
				LEFT JOIN Preferences on ReceivedAd.BusinessID = Preferences.BusinessID
					AND ReceivedAd.ConsumerID = Preferences.ConsumerID
				   WHERE ReceivedAd.ConsumerID = ?
				   AND IsCleared = 0
				   AND IsSaved = 0
					AND (IsBlocked IS NULL OR IsBlocked = 0)
                                ORDER BY IsSeen, IF(IsFavorite IS NULL, 0, IsFavorite) DESC, ReceivedDate DESC";
		   $tableName = "ReceivedAd";
		   echo dbGetRecords($tableName, $sql, [$ConsumerID]);
	   }	   
