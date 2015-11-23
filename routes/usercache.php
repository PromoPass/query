<?php
   function getProviderBusinessesbySessionToken($SessionToken) {
      $sql = "SELECT Business.BusinessID, Business.Name, Business.ProviderID, Business.EIN, Business.GimbalID
            FROM Business
            INNER JOIN Provider ON Provider.ProviderID = Business.ProviderID
            WHERE Provider.ProviderID = (SELECT user_id FROM UserCache WHERE session_token = ?)";
      $tableName = "Business";
      echo dbGetRecords($tableName, $sql, [$SessionToken]);
   }
      
   function addUserCache() {
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
       
        $usercache = json_decode($request->getBody());
       
        $sql = "INSERT INTO UserCache (session_token, user_id)
                VALUES (?, ?)";
        dbAddRecords($sql, [ $usercache->session_token,
                             $usercache->user_id ], $request->getBody()); 
    }

    function deleteUserCache($id) {
        $app = \Slim\Slim::getInstance();
        
        $sql = "DELETE FROM UserCache
                WHERE session_token = ?";
        dbDeleteRecords($sql, [ $id ]);
    }
