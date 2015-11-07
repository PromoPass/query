<?php
     function getProviders() {
       $sql = "SELECT ProviderID, FirstName, LastName, Email
               FROM Provider";
       $tableName = "Providers";
       dbGetRecords($tableName, $sql);    
   }

   function getProvider($ProviderID) {
       $sql = "SELECT ProviderID, FirstName, LastName, Email
               From Provider
               WHERE ProviderID = ?";
       $tableName = "Provider";
       dbGetRecords($tableName, $sql, [$ProviderID]);
   }

   function addProvider() {
        $app = \Slim\Slim::getInstance();
        $request = $app->request();
       
        $provider = json_decode($request->getBody());
        $sql = "INSERT INTO Provider (ProviderID, FirstName, LastName, Email)
                VALUES (?, ?, ?, ?)
                ON DUPLICATE KEY UPDATE FirstName = ?, LastName = ?, Email = ?";
        dbAddRecords($sql, [ $provider->user_id,
                             $provider->first_name,
                             $provider->last_name,
                             $provider->email,
                             $provider->first_name,
                             $provider->last_name,
                             $provider->email ],
                     $request->getBody()); 
    }
