<?php
    function getBusinesses() {
       $sql = "SELECT BusinessID, Name, ProviderID, EIN, GimbalID
               FROM Business";
       $tableName = "Business";
       echo dbGetRecords($tableName, $sql);
    }

   function getBusinessNames() {
       $sql = "SELECT Name
               FROM Business";
       $tableName = "BusinessNames";
       echo dbGetRecords($tableName, $sql);
   }

    function getBusiness($BusinessID) {
       $sql = "SELECT Business.BusinessID, Business.Name, Business.ProviderID, Business.EIN
           FROM Business
           WHERE Business.BusinessID = ?";
       $tableName = "Business";
       echo dbGetRecords($tableName, $sql, [$BusinessID]);
   }

   function getCurrentAd($BusinessID) {
       $sql = "Select AdID, Title, Writing, BusinessID, CreateDate, TemplateID, PicURL
               FROM Ad
               WHERE BusinessID = ? AND IsCurrent = 1";
       $tableName = "Ad";
       echo dbGetRecords($tableName, $sql, [$BusinessID]);
   }

   function getBusinessName($BusinessID) {
       $sql = "Select Name
               From Business
               WHERE BusinessID = ?";
       $tableName = "BusinessName";
       echo dbGetRecords($tableName, $sql, [$BusinessID]);
   }

   function getBusinessTypes($BusinessID) {
       $sql = "SELECT Type
               FROM BusinessType
               WHERE BusinessID = ?";
       $tableName = "BusinessType";
       echo dbGetRecordsArr($tableName, $sql, [$BusinessID]);
   }
   
   	function getBusinessID($GimbalID) {
		   $sql = "SELECT BusinessID
				   FROM Business
				   WHERE GimbalID = ?";
		   $tableName = "Business";
		echo dbGetRecords($tableName, $sql, [$GimbalID]);
	   }
	   
    function addBusiness() {
        $app = \Slim\Slim::getInstance();
        $request = $app->request(); 
        $business = json_decode($request->getBody(), true);
        $sql = "INSERT INTO Business (Name, ProviderID, GimbalID)
                VALUES (?, ?, ?);";
        $bindValues = [
            $business['Name'],
            $business['ProviderID'],
            $business['GimbalID' ] ];
        foreach($business['typeList'] as $type) {
           $sql .= "INSERT INTO BusinessType (BusinessID, Type)
                   VALUES (
                   (SELECT BusinessID
                    FROM Business
                    WHERE ProviderID=?
                    AND Name=? ),
                   ?
	               );";
           array_push($bindValues, $business['ProviderID'],
                                     $business['Name'],
                                     $type);
        }
        echo dbAddRecords($sql, $bindValues, $business); 
    }

   function editBusiness($BusinessID) {
       $app = \Slim\Slim::getInstance();
       $request = $app->request();
       $business = json_decode($request->getBody(), true);
       // Edit the Business
       $sql = "UPDATE Business
           SET Name = ?, GimbalID = ?
           WHERE BusinessID = ?;";
       $bindValues = [
           $business['Name'],
           $business['GimbalID'],
           $BusinessID ];
       // Add Business Types if they don't exist in database
       foreach($business['typeList'] as $type) {
           $sql .= "INSERT INTO BusinessType (BusinessID, Type)
               VALUES (?,?)
               ON DUPLICATE KEY UPDATE BusinessID = BusinessID;";
           array_push($bindValues, $BusinessID, $type);
       }

       // Delete Business Types if they don't exist in business list
       $jsonsql = "SELECT Type
                   FROM BusinessType
                   WHERE BusinessID = ?;";
       $tableName = "BusinessType"; // all this stuff should be refactored later. should. should.
       $jsonGetBusinessTypes = dbGetRecordsArr($tableName, $jsonsql, [$BusinessID]);
       $indexStart = strrpos($jsonGetBusinessTypes, "[");
       $indexEnd = strrpos($jsonGetBusinessTypes, "]");
       $typeString = preg_replace('/\s+/', '', $jsonGetBusinessTypes);
       $typeString = substr($typeString, $indexStart, -3);
       //print_r($typeString);
       $dbArr = explode("\",\"" , $typeString);
       //print_r($business['typeList']);
       $toDelete = array_diff($dbArr, $business['typeList']);
       //print_r($toDelete);
       if(sizeOf($toDelete) > 0) {
           $delSql = "";
           $del_bindValues = [];
           foreach($toDelete as $deleteType) {
               $delSql .= "DELETE FROM BusinessType
                               WHERE Type = ?
                               AND BusinessID = ?;";
                  array_push($del_bindValues, $deleteType, $BusinessID);
               }
           dbDeleteRecords($delSql, $del_bindValues);

       }
        $tableName = "Business";
        
       echo dbUpdateRecords($tableName, $sql, $bindValues);
   }
