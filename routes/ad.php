<?php
   function getAds() {
       $sql = "SELECT AdID, TemplateID, IsCurrent, Title, Writing, BusinessID, CreateDate, PicURL
               FROM Ad";
       $tableName = "Ad";
       echo dbGetRecords($tableName, $sql);
   }
   function getAd($AdID){
       $sql = "SELECT AdID, TemplateID, IsCurrent, Title, Writing, BusinessID, CreateDate, PicURL 
		FROM Ad
		WHERE AdID = ?";
	$tableName = "Ad";
	echo dbGetRecords($tableName, $sql, [$AdID]);
 }
   
   	function getCurrentAdID($BusinessID) {
		   $sql = "SELECT AdID
				   FROM Ad
				   WHERE BusinessID = ?
				   and IsCurrent = 1";
		   $tableName = "Ad";
		   echo dbGetRecords($tableName, $sql, [$BusinessID]);
	   }


	   
	function getAdInformation($AdID) {
		   $sql = "SELECT Title, Writing
				   FROM Ad
				   WHERE AdID = ?";
		   $tableName = "Ad";
		   dbGetRecords($tableName, $sql, [$AdID]);
	   }	   
	   /* needs to be deprecated ... can be added into Ad 
	function getAdWriting($AdID) {
		   $sql = "SELECT Writing
				   FROM Writing
				   WHERE AdID = ?";
		   $tableName = "Writing";
		   dbGetRecords($tableName, $sql, [$AdID]);
	   }	   	   */

    function setAdNotCurrent($BusinessID) { //see if works for update
		   $sql = "UPDATE Ad 
				   SET IsCurrent = 0
				   WHERE BusinessID = ?
				   AND IsCurrent = 1";
		   $tableName = "Ad";
		   dbAddRecords($sql, [$BusinessID]);	
	   }	
   
	   
   function addAd() {
   $app = \Slim\Slim::getInstance();
   $request = $app->request();
   
   $ad = json_decode($request->getBody());
   // todo: set previous iscurrent ad as not current;
    $sql = "UPDATE Ad
            SET IsCurrent = 0
            WHERE BusinessID = ?;";
   // finally do the insert
    $sql .=      "INSERT INTO Ad (TemplateID, IsCurrent, Title, Writing, BusinessID, CreateDate)
           VALUES (?, ?, ?, ?, ?, NOW())";
    echo dbAddRecords($sql, [ $ad->BusinessID,
                              $ad->TemplateID,
                              1,
                              $ad->Title,
                              $ad->Writing,
                              $ad->BusinessID ],
                              $ad   );
   }
