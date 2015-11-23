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
		$sql = "SELECT BusinessID
			FROM ReceivedAd
			WHERE IsSeen = 0
				AND ConsumerID = ?";
		$tableName = "ReceivedAd";
		echo dbGetRecords($tableName, $sql, [$ConsumerID]);
	}
