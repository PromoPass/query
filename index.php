<?php
	require 'vendor/autoload.php';
	include 'connection.php';

    require 'routes/provider.php';
    require 'routes/usercache.php';
    require 'routes/business.php';
    require 'routes/businesstype.php';
    require 'routes/consumer.php';
    require 'routes/ad.php';
    require 'routes/receivedad.php';
    require 'routes/preferences.php';

	$app = new \Slim\Slim();

    $response = $app->response();

    $app->get('/', function() {
        echo "Welcome to Promo<i>Pass</i>'s BACK END. :)";
    });

	$app->get('/hello/:name', function($name) {
		echo "Hello, $name";
	});
    
    // URL                                
    // see google drive -> WORKING DATABASE QUERIES for what is currently working
    $app->group('/api', function() use($app) {
        // Version group
        $app->group('/v1', function() use($app) {
            
           // Provider group
           $app->group('/provider', function() use($app) {
               $app->get('/', 'getProviders');
               $app->get('/:ProviderID', 'getProvider');
               $app->get('/:ProviderID/business', 'getProviderBusinesses');
               $app->post('/', 'addProvider');
               // TODO $app->put('/', 'updateProvider'); 
           });
            
           // User Cache group
            $app->group('/usercache', function() use($app) {
                $app->get('/:SessionToken/business', 'getProviderBusinessesbySessionToken');
                $app->post('/', 'addUserCache');
                $app->delete('/:id', 'deleteUserCache');
            });

           // Consumer group
           $app->group('/consumer', function() use($app) {

           $app->get('/:ConsumerID/received', 'getReceivedAdsNotClearedOrSaved');
           $app->post('/', 'addConsumer');
           });
            
           // Device group
           $app->group('/device', function() use($app) {
               $app->get('/:DeviceID/consumer/consumer-id', 'getConsumerIDbyDevice');
           });
            
           // Business group
           $app->group('/business', function() use($app) {
               $app->get('/', 'getBusinesses');
               $app->get('/all/names', 'getBusinessNames');
               $app->get('/:BusinessID', 'getBusiness');
               $app->get('/:BusinessID/current-ad', 'getCurrentAd');
               $app->get('/:BusinessID/current-ad/id', 'getCurrentAdID');
               $app->get('/:BusinessID/name', 'getBusinessName');
               $app->get('/:BusinessID/types', 'getBusinessTypes');
               $app->get('/:GimbalID/getBusinessID', 'getBusinessID');
               
               $app->post('/', 'addBusiness');
               
               $app->put('/:BusinessID', 'editBusiness');
           });
            
           // Gimbal group
           $app->group('/gimbal', function() use($app) {
               $app->get('/:GimbalID/business/business-id', 'getBusinessID');
           });
          
           // Ad group
           $app->group('/ad', function() use($app) {
               $app->get('/', 'getAds');
               $app->get('/:AdID', 'getAd');
	                      
               $app->post('/', 'addAd');
               
               $app->put('/:AdID', 'editAd');
           });

           // Received Ad group
           $app->group('/received/ad', function() use($app) {
               $app->get('/:ReceivedAdID/save', 'saveReceivedAd');
               $app->get('/:ReceivedAdID/clear', 'clearReceivedAd');
               $app->get('/:ReceivedAdID/see', 'seeReceivedAd');
		       $app->get('/:AdID/:ConsumerID/getReceivedAd', 'getReceivedAd');
		       $app->get('/:ConsumerID/unseen', 'getUnseenReceivedAds');

               $app->post('/', 'addReceivedAd');
           });

           // Preferences group
           $app->group('/preferences', function() use($app) {
               $app->get('/consumer/:ConsumerID/business/:BusinessID/favorite', 'favoriteBusiness');
               $app->get('/consumer/:ConsumerID/business/:BusinessID/block', 'blockBusiness');
           });
        });
        
    });


	$app->run();

   




/*
	function getProviderID($Email) {
		   $sql = "SELECT ProviderID
				   FROM Provider
				   WHERE Email = ?";
		   $tableName = "Provider";
		   dbGetRecords($tableName, $sql, [$Email]);
	   }
*/

	function getBusinessIDfromProviderID($ProviderID) {
		   $sql = "SELECT BusinessID
				   FROM Business
				   WHERE ProviderID = ?";
		   $tableName = "Business";
		   dbGetRecords($tableName, $sql, [$ProviderID]);
	   }	   

/*
	   
	function addProviderEmail(){
	    $app = Slim::getInstance();
        $request = $app->request();
        $provider = json_decode($request->getBody());
        $sql = "INSERT INTO Provider (Email)
                VALUES (?)";
        dbAddRecords($sql, [ $provider->Email ]); 
	}
    
   function addAdTitle() {
        $app = Slim::getInstance();
        $request = $app->request();
        $provider = json_decode($request->getBody());
        $sql = "INSERT INTO Ad (BusinessID, Title)
                VALUES (?, ?)";
        dbAddRecords($sql, [ $provider->BusinessID,
                             $provider->Title ]); 
    }	
	
   function addAdWriting() {
        $app = Slim::getInstance();
        $request = $app->request();
        $provider = json_decode($request->getBody());
        $sql = "INSERT INTO Writing (AdID, Writing)
                VALUES (?, ?)";
        dbAddRecords($sql, [ $provider->AdID,
                             $provider->Writing ]); 
    }	
	
*/	
   function dbGetRecords($tableName, $sql, $a_bind_params = []){
       global $db;
       $query = $db->prepare($sql);
       if(!empty($a_bind_params)) {
           $query->execute($a_bind_params);
       } else {
           $query->execute();
       }
       $results = $query->fetchAll(PDO::FETCH_OBJ);
       return '{ "' . $tableName . '": ' . json_encode($results, JSON_PRETTY_PRINT) . ' }';
   }

    function dbGetRecordsArr($tableName, $sql, $a_bind_params = []) {
       global $db;
       $query = $db->prepare($sql);
       if(!empty($a_bind_params)) {
           $query->execute($a_bind_params);
       } else {
           $query->execute();
       }
       $results = $query->fetchAll(PDO::FETCH_COLUMN);
       return '{ "' . $tableName . '": ' . json_encode($results, JSON_PRETTY_PRINT) . ' }';
   }

   function dbUpdateRecords($tableName, $sql, $a_bind_params = []){
       global $db;
       $query = $db->prepare($sql);
       if(!empty($a_bind_params)) {
           $query->execute($a_bind_params);
       } else {
           $query->execute();
       }
       return '{ "' . $tableName . '" }';
   }

   function dbAddRecords($sql, $a_bind_params = [], $object) {
       global $db;
       $query = $db->prepare($sql);
       $query->execute($a_bind_params);
       return json_encode($object, JSON_PRETTY_PRINT);
   }

   function dbDeleteRecords($sql, $a_bind_params = []) {
       global $db;
       $query = $db->prepare($sql);
       $query->execute($a_bind_params);
       $app = \Slim\Slim::getInstance();
       $app->response()->status(204);
   }
