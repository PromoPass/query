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

    function favoriteReceivedAd($ReceivedAdID) {
        $sql = "UPDATE ReceivedAd
                SET IsSaved = 1
                WHERE ReceivedAdID = ?";
        $tableName = "ReceivedAd";
        echo dbUpdateRecords($tableName, $sql, [$ReceivedAdID]);      
    }

    function saveReceivedAd($ReceivedAdID) {
        $sql = "UPDATE ReceivedAd
                SET IsSaved=1, IsCleared=1
                WHERE ReceivedAdID=?";
        $tableName = "ReceivedAd";
        echo dbUpdateRecords($tableName, $sql, [$ReceivedAdID]);
    }

    function blockReceivedAd($ReceivedAdID) {
        $sql = "UPDATE ReceivedAd
                SET IsCleared=1, IsBlocked=1 
                WHERE ReceivedAdID=?"; // IsBlocked will later be used
        $tableName = "ReceivedAd";     // to block incoming ads with the same businessID
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
		$sql = "SELECT ReceivedAdID
			FROM ReceivedAd
			WHERE AdID = ?
			AND ConsumerID = ?";
		$tableName = "ReceivedAd";
		echo dbGetRecords($tableName, $sql, [$AdID, $ConsumerID]);
	}

	function getUnseenReceivedAds($ConsumerID) {
        // Set new ads to IsBlocked if they are a new
        // ad from an older IsBlocked received ad
        $sql = "UPDATE ReceivedAd unseen 
                    INNER JOIN ReceivedAd seen ON
                    (unseen.ConsumerID = seen.ConsumerID
                    AND 
                     unseen.BusinessID = seen.BusinessID)
                SET unseen.IsSeen = 1, 
                    unseen.IsBlocked= 1,
                    unseen.IsCleared= 1
                WHERE unseen.ConsumerID = ?
                    AND seen.IsBlocked=1
                    AND seen.IsSeen=1
                    AND unseen.IsSeen=0
                         ;";
		$tableName = "ReceivedAd";
        dbUpdateRecords($tableName, $sql, [$ConsumerID]);
        
        // Begin Actual query
		$sql = "SELECT BusinessID
			FROM ReceivedAd
			WHERE IsSeen = 0
			AND ConsumerID = ?";
		echo dbGetRecords($tableName, $sql, [
                                                $ConsumerID]);
	}