<?php	

	function addReceivedAd() {
            $app = \Slim\Slim::getInstance();
            $request = $app->request();

            $receivedAd = json_decode($request->getBody());
            $sql = "INSERT INTO ReceivedAd (AdID, ConsumerID, BusinessID)
            VALUES (?, ?, ?)";

            echo dbAddRecords($sql, 
		[ 
			$receivedAd->AdID, 
			$receivedAd->ConsumerID, 
			$receivedAd->BusinessID  
		],
            	$receivedAd);
    }

    function saveReceivedAd($ReceivedAdID) {
        $sql = "UPDATE ReceivedAd
                SET IsSaved=1, IsCleared=1
                WHERE ReceivedAdID=?";
        $tableName = "ReceivedAd";
        echo dbUpdateRecords($tableName, $sql, [$ReceivedAdID]);
    }

	function clearReceivedAd($ReceivedAdID) {       // this works

                $sql = "UPDATE ReceivedAd
                SET IsCleared = 1
                WHERE ReceivedAdID = ?";

        	$tableName = "ReceivedAd";
        	dbUpdateRecords($tableName, $sql, [$ReceivedAdID]);
        }

	function seeReceivedAd($ReceivedAdID) {       //check this
                $sql = "UPDATE ReceivedAd
                SET IsSeen = 1
                WHERE ReceivedAdID = ?";
        	$tableName = "ReceivedAd";
        	dbUpdateRecords($tableName, $sql, [$ReceivedAdID]);
        }

	function getReceivedAd($AdID, $ConsumerID) {       
        $sql = "SELECT ReceivedAdId
            FROM ReceivedAd
            INNER JOIN Preferences p1 on ReceivedAd.BusinessID = p1.BusinessID
            	AND ReceivedAd.ConsumerID = p1.ConsumerID
            WHERE AdID = ?
            AND ReceivedAd.ConsumerID = ?
            AND (p1.BusinessID IS NULL OR p1.IsBlocked = 0)";
		$tableName = "ReceivedAd";
		echo dbGetRecords($tableName, $sql, [$AdID, $ConsumerID]);
	}

    function getUnseenReceivedAds($ConsumerID) { // needs to be checked
        $sql = "SELECT ReceivedAdId, ReceivedAd.BusinessID
            FROM ReceivedAd
            INNER JOIN Preferences p1 on ReceivedAd.BusinessID = p1.BusinessID
            	AND ReceivedAd.ConsumerID = p1.ConsumerID
            WHERE ReceivedAd.IsSeen = 0
            AND ReceivedAd.ConsumerID = ?
            AND (p1.BusinessID IS NULL OR p1.IsBlocked = 0)";
        $tableName = "ReceivedAd";
		echo dbGetRecords($tableName, $sql, [$ConsumerID]);
	}
