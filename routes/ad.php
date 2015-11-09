<?php
   function getAds() {
       $sql = "SELECT AdID, TemplateID, IsCurrent, Title, BusinessID, CreateDate
               FROM Ad";
       $tableName = "Ad";
       echo dbGetRecords($tableName, $sql);
   }
   
   	function getCurrentAd($BusinessID) {
		   $sql = "SELECT AdID
				   FROM Ad
				   WHERE BusinessID = ?
				   and IsCurrent = 1";
		   $tableName = "Ad";
		   dbGetRecords($tableName, $sql, [$BusinessID]);
	   }


	function getReceivedAdsNotClearedOrSaved($ConsumerID) {
		   $sql = "SELECT ReceivedAdID, AdID, BusinessID, ReceivedDate
				   FROM ReceivedAd
				   WHERE ConsumerID = ?
				   AND IsCleared = 0
				   AND IsSaved = 0";
		   $tableName = "ReceivedAd";
		   dbGetRecords($tableName, $sql, [$ConsumerID]);
	   }	   

	function clearReceivedAd($ReceivedAdID) {	//check this
			$sql = "UPDATE ReceivedAd
					SET IsCleared = 1
					WHERE ReceivedAdID = ?";
		   $tableName = "ReceivedAd";
		   dbGetRecords($tableName, $sql, [$ReceivedAdID]);
	   }
	   
	function getAdInformation($ReceivedAdID) {
		   $sql = "SELECT AdID, BusinessID 
				   FROM ReceivedAd
				   WHERE ReceivedAdID = ?";
		   $tableName = "ReceivedAd";
		   dbGetRecords($tableName, $sql, [$ReceivedAdID]);
	   }	

	function getAdTitle($AdID) {
		   $sql = "SELECT Title
				   FROM Ad
				   WHERE AdID = ?";
		   $tableName = "Ad";
		   dbGetRecords($tableName, $sql, [$AdID]);
	   }	   
	   
	function getAdWriting($AdID) {
		   $sql = "SELECT Writing
				   FROM Writing
				   WHERE AdID = ?";
		   $tableName = "Writing";
		   dbGetRecords($tableName, $sql, [$AdID]);
	   }	   	   

    function setAdNotCurrent($BusinessID) { //see if works for update
		   $sql = "UPDATE Ad 
				   SET IsCurrent = 0
				   WHERE BusinessID = ?
				   AND IsCurrent = 1";
		   $tableName = "Ad";
		   dbAddRecords($sql, [$BusinessID]);	
	   }	
   
    function getReceivedAds($ConsumderID) {
       $sql = "SELECT BusinessID
               FROM ReceivedAd
               WHERE ConsumerID = ? 
                   AND IsCleared = 0
                   AND IsSaved = 0
               Group by BusinessID";
           dbGetRecords($sql, [$ConsumerID]);
       }


	function insertReceivedAd($AdID,$ConsumerID,$BusinessID) { //check this
		   $sql = "INSERT INTO ReceivedAd (AdID, ConsumerID, BusinessID)
				   VALUES (?, ?, ?)";
		   $tableName = "ReceivedAd";
		   dbGetRecords($tableName, $sql, [$AdID,$ConsumerID,$BusinessID]);
	   }	