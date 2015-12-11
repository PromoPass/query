<?php	

    function favoriteBusiness($ConsumerID, $BusinessID) {
        $sql = "INSERT INTO Preferences (IsFavorite, IsBlocked, ConsumerID, BusinessID)
                VALUES (1, 0, ?, ?)
                ON DUPLICATE KEY UPDATE
                    IsFavorite = 1,
                    IsBlocked = 0";
        $tableName = "Preferences";
        echo dbUpdateRecords($tableName, $sql, [ $ConsumerID,
                                                 $BusinessID
                                                            ]);      
    }

function blockBusiness($ConsumerID, $BusinessID) {
        $sql = "INSERT INTO Preferences (IsFavorite, IsBlocked, ConsumerID, BusinessID)
                VALUES (0, 1, ?, ?)
                ON DUPLICATE KEY UPDATE
                    IsFavorite = 0,
                    IsBlocked = 1";
        $tableName = "Preferences";
        echo dbUpdateRecords($tableName, $sql, [ $ConsumerID,
                                                 $BusinessID
                                                            ]);      
    }


   function getBlockPreference($ConsumerID, $BusinessID) {
       $sql = "SELECT IsBlocked
               FROM Preferences
		WHERE ConsumerID = ?
			And BusinessID = ?";
       $tableName = "Preferences";
       echo dbGetRecords($tableName, $sql, [$ConsumerID, $BusinessID]);
   }

	function getBlockedBusinesses($ConsumerID) {
       		$sql = "SELECT Name, PreferenceID
               		FROM Preferences
			INNER JOIN Business ON Preferences.BusinessID = Business.BusinessID
			WHERE ConsumerID = ?
				AND IsBlocked = 1
			ORDER BY Name";
       		$tableName = "Preferences";
       		echo dbGetRecords($tableName, $sql, [$ConsumerID]);
   	}

	function getFavoriteBusinesses($ConsumerID) {
       		$sql = "SELECT Name, PreferenceID
               		FROM Preferences
			INNER JOIN Business ON Preferences.BusinessID = Business.BusinessID
			WHERE ConsumerID = ?
				AND IsFavorite = 1
			ORDER BY Name";
       		$tableName = "Preferences";
       		echo dbGetRecords($tableName, $sql, [$ConsumerID]);
   	}


    function unfavoriteBusiness($PreferenceID) {
        $sql = "UPDATE Preferences
                SET IsFavorite=0
                WHERE PreferenceID=?";
        $tableName = "Preferences";
        echo dbUpdateRecords($tableName, $sql, [$PreferenceID]);
    }


    function unblockBusiness($PreferenceID) {
        $sql = "UPDATE Preferences
                SET IsBlocked=0
                WHERE PreferenceID=?";
        $tableName = "Preferences";
        echo dbUpdateRecords($tableName, $sql, [$PreferenceID]);
    }

