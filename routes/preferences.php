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
       $tableName = "BusinessNames";
       echo dbGetRecords($tableName, $sql, [$ConsumerID, $BusinessID]);
   }

