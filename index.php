<?php
	require 'vendor/autoload.php';
	include 'connection.php';

    require 'routes/provider.php';
    require 'routes/usercache.php';
    require 'routes/business.php';
    require 'routes/businesstype.php';
    require 'routes/consumer.php';
    require 'routes/ad.php';

	$app = new \Slim\Slim();

    $response = $app->response();
    //$response->header("Access-Control-Allow-Headers: Origin, X-Requested-With, Content-Type, Accept");
 
    $app->get('/', function() {
        echo "Welcome to Promo<i>Pass</i>'s BACK END. :)";
    });

	$app->get('/hello/:name', function($name) {
		echo "Hello, $name";
	});
    
    // URL                                
    // /api/v1/provider                                           # returns list of all providers
    // /api/v1/provider/id/:ProviderID                            # returns the provider with the specified id
    // /api/v1/consumer/device/id/:DeviceID/id                    # returns the consumer id with the specified device id
    // /api/v1/business                                           # returns list of all businesses 
    // /api/v1/business/names                                     # returns list of all business names
    // /api/v1/business/id/:BusinessID/names                      # returns the business name with the specified business id
    // /api/v1/business/id/:BusinessID/types                      # returns the business types with the specified business id
    // /api/v1/ad/						  # returns list of all ads (TODO: need to delete this)
    // /api/v1/business/consumer/id/:ConsumerID/receivedAds/id    # returns the list of business ids with the specified consumer's id for received ads

    $app->group('/api', function() use($app) {
        // Version group
        $app->group('/v1', function() use($app) {
            
           // Provider group
           $app->group('/provider', function() use($app) {
               $app->get('/', 'getProviders');
               $app->get('/id/:ProviderID', 'getProvider');
               
               $app->post('/', 'addProvider');
               // TODO $app->put('/', 'updateProvider'); 
           });
            
           // User Cache group
            $app->group('/usercache', function() use($app) {
                $app->post('/', 'addUserCache');
                $app->delete('/:id', 'deleteUserCache');
            });

           // Consumer group
           $app->group('/consumer', function() use($app) {
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
               $app->get('/:BusinessID/name', 'getBusinessName');
               $app->get('/:BusinessID/types', 'getBusinessTypes');
               $app->post('/', 'addBusiness');
           });
            
           // Gimbal group
           $app->group('/gimbal', function() use($app) {
               $app->get('/:GimbalID/business/business-id', 'getBusinessID');
           });
          
           $app->group('/ad', function() use($app) {
               $app->get('/', 'getAds');
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
