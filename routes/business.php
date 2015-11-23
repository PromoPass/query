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
       $sql = "SELECT BusinessID, Name, ProviderID, EIN, GimbalID
               FROM Business
               WHERE BusinessID = ?";
       $tableName = "Business";
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
       echo dbGetRecords($tableName, $sql, [$BusinessID]);
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
            $business['name'],
            $business['provider_id'],
            $business['gimbal_id' ] ];
        foreach($business['typeList'] as $type) {
           $sql .= "INSERT INTO BusinessType (BusinessID, Type)
                   VALUES (
                   (SELECT BusinessID
                    FROM Business
                    WHERE ProviderID=?
                    AND Name=? ),
                   ?
	               );";
           array_push($bindValues, $business['provider_id'],
                                     $business['name'],
                                     $type['value']);
        }
        echo dbAddRecords($sql, $bindValues, $business); 
    }

   