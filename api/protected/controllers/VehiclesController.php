<?php
class VehiclesController extends Controller{
	public function actionIndex(){
		$this->render('index');
	}

	/*
	** Returns all vehicles makes and models.
	** Post Required: none
	** Url:- http://www.demo.com/index.php?r=vehicles/vehiclelist
	** Purpose:- Get vehicles makes and models
	*/
	public function actionvehiclelist(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$json = array();
		$vehicles = array();
        $makes = array();
       $makes_detail = array();

		$result= 'false';
		$response= 'none';

		$vehicles_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_vehicles')
->order('make ASC')
			->queryAll();



         	if(count($vehicles_exists)>0){


				$result = 'true';
				$response = 'Vehicles Makes, Models and Types';



				foreach($vehicles_exists as $vehicle){

                   $makes[] = trim($vehicle['make']);

				}

				$makes = array_unique($makes);
				$makes = array_values($makes);

         //$vehicles['makes'] =  $makes;
   foreach ($makes as $make){

   $make_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_vehicles')
			->where("make='".$make."'", array())
->order('model ASC')
			->queryAll();

			if(count($make_exists)>0){
			$makes_detail = array();
			foreach($make_exists as $mymake){
			unset($mymake['make']);
			unset($mymake['id']);

			$makes_detail[] = $mymake['model']."|".$mymake['category']."|".$mymake['type'];
			}
			 $vehicles['makes'][$make] = $makes_detail;
			}

   }


  //$vehicles['models'] =  $makes_detail;
}
else{
$response = 'No vehicles found';
}

		$json = array(
			'result'=> $result,
			'response'=> $response,
			'vehicles'=> $vehicles
		);

		echo json_encode($json);
		die();
	}

    	public function actionclassicvehiclelist(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$json = array();
		$vehicles = array();
        $makes = array();
       $makes_detail = array();

		$result= 'false';
		$response= 'none';

		$vehicles_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_classic_vehicles')
->order('make ASC')
			->queryAll();



         	if(count($vehicles_exists)>0){


				$result = 'true';
				$response = 'Vehicles Makes, Models and Types';



				foreach($vehicles_exists as $vehicle){

                   $makes[] = $vehicle['make'];

				}

				$makes = array_unique($makes);
				$makes = array_values($makes);


         //$vehicles['makes'] =  $makes;
   foreach ($makes as $make){

   $make_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_classic_vehicles')
			->where("make='".$make."'", array())
->order('model ASC')
			->queryAll();

			if(count($make_exists)>0){
			$makes_detail = array();
			foreach($make_exists as $mymake){
			unset($mymake['make']);
			unset($mymake['id']);
			$makes_detail[] = $mymake['model']."|".$mymake['category']."|".$mymake['type'];
			}
			 $vehicles['makes'][$make] = $makes_detail;
			}

   }


  //$vehicles['models'] =  $makes_detail;
}
else{
$response = 'No vehicles found';
}

		$json = array(
			'result'=> $result,
			'response'=> $response,
			'vehicles'=> $vehicles
		);

		echo json_encode($json);
		die();
	}

	public function actionvehiclemakes(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$json = array();
		$vehicles = array();
        $makes = array();
       $makes_detail = array();

		$result= 'false';
		$response= 'none';

		$vehicles_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_vehicles')
			->order('make asc')
			->queryAll();



         	if(count($vehicles_exists)>0){


				$result = 'true';
				$response = 'Vehicles Makes';



				foreach($vehicles_exists as $vehicle){

                   $makes[] = trim($vehicle['make']);

				}

				$makes = array_unique($makes);
				$makes = array_values($makes);





  //$vehicles['models'] =  $makes_detail;
}
else{
$response = 'No vehicle makes found';
}

		$json = array(
			'result'=> $result,
			'response'=> $response,
			'vehicle_makes'=> $makes
		);

		echo json_encode($json);
		die();
	}


public function actionvehiclemakesclassic(){

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

		$json = array();
		$vehicles = array();
        $makes = array();
       $makes_detail = array();

		$result= 'false';
		$response= 'none';

		$vehicles_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_classic_vehicles')
			->order('make asc')
			->queryAll();



         	if(count($vehicles_exists)>0){


				$result = 'true';
				$response = 'Vehicles Makes';



				foreach($vehicles_exists as $vehicle){

                   $makes[] = $vehicle['make'];

				}

				$makes = array_unique($makes);
				$makes = array_values($makes);





  //$vehicles['models'] =  $makes_detail;
}
else{
$response = 'No vehicle makes found';
}

		$json = array(
			'result'=> $result,
			'response'=> $response,
			'vehicle_makes'=> $makes
		);

		echo json_encode($json);
		die();
	}


public function actionAddVehicle()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

			$result= 'false';
			$response= 'All fields required';

$vehicle_build = Yii::app()->request->getParam('vehicle_build');

			$make = Yii::app()->request->getParam('make');
			$model = Yii::app()->request->getParam('model');
$vehicle_category = Yii::app()->request->getParam('category');
			$vehicle_type = Yii::app()->request->getParam('type');

			$vehicle = array();
			if((isset($vehicle_build) && !empty($vehicle_build)) &&
			(isset($make) && !empty($make)) &&
            (isset($model) && !empty($model)) &&
              (isset($vehicle_type) && !empty($vehicle_type)) &&
			(isset($vehicle_category) && !empty($vehicle_category)))
			 {


							try{
							    if($vehicle_build == 'classic'){
                                  	$resIns = Yii::app()->db->createCommand()
										->insert('all_classic_vehicles', array(
										'make'=>$make,
										'model'=>$model,
										'type'=>$vehicle_type,
										'category'=>$vehicle_category));
							    }
                                else{
                                  $resIns = Yii::app()->db->createCommand()
									   	->insert('all_vehicles', array(
										'make'=>$make,
										'model'=>$model,
										'type'=>$vehicle_type,
										'category'=>$vehicle_category));
                                }

							}catch(Exception $e){
                            //echo $e;
							}
                            //var_dump($resIns);
							if($resIns)
							{
								$result= 'true';
                               if($vehicle_build == 'classic') 	$response= 'Classic vehicle added successfully';
							   else $response= 'Regular vehicle added successfully';

							}
							else
							{
								$response= 'Internal error. Please try again.';
							}


			}

		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}
	
/*	
	public function actionupdatecustomervehsmle()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

			$result= 'false';
			$response= 'pass required parameters';
			
			  $all_cust_vehicles = Yii::app()->db->createCommand()
						->select('*')
						->from('customer_vehicals')
						->queryAll();

if(count($all_cust_vehicles) > 0){
    $result= 'true';
			$response= 'update successful';
			
    foreach($all_cust_vehicles as $custvehicle){
        
        if($custvehicle['vehicle_build'] == 'classic'){
              $cls_veh = Yii::app()->db->createCommand()
						->select('*')
						->from('all_classic_vehicles')
						->where("make='".$custvehicle['brand_name']."' AND model='".$custvehicle['model_name']."'", array())
						->queryAll();
			if(count($cls_veh) > 0){
			    //echo $cls_veh[0]['make']." ".$cls_veh[0]['model']." ".$cls_veh[0]['type']." ".$cls_veh[0]['category']."<br>";
			    
			    $data = array(	'vehicle_type'=>$cls_veh[0]['type'],
'vehicle_category'=>$cls_veh[0]['category']
						);

					$resUpdate = Yii::app()->db->createCommand()->update('customer_vehicals',$data,"id='".$custvehicle['id']."'");
			}
        }
        
        else{
              $reg_veh = Yii::app()->db->createCommand()
						->select('*')
						->from('all_vehicles')
						->where("make='".$custvehicle['brand_name']."' AND model='".$custvehicle['model_name']."'", array())
						->queryAll();
			if(count($reg_veh) > 0){
			    //echo $reg_veh[0]['make']." ".$reg_veh[0]['model']." ".$reg_veh[0]['type']." ".$reg_veh[0]['category']."<br>";
			    
			    $data = array(	'vehicle_type'=>$reg_veh[0]['type'],
'vehicle_category'=>$reg_veh[0]['category']
						);

					$resUpdate = Yii::app()->db->createCommand()->update('customer_vehicals',$data,"id='".$custvehicle['id']."'");
			}
        }
    }
}

						
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}
	
*/

		public function actionallcustomervehicles()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

			$result= 'false';
			$response= 'pass required parameters';
			
			  $all_cust_vehicles = Yii::app()->db->createCommand()
						->select('id')
						->from('customer_vehicals')
						->queryAll();
						
if(count($all_cust_vehicles) > 0){
    $result= 'true';
			$response= 'all vehicles';
		
}

						
		$json= array(
			'result'=> $result,
			'response'=> $response,
			'all_vehicles' => $all_cust_vehicles
		);
		echo json_encode($json);
	}

	
	
		public function actionupdatecustomervehsmle()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

$vehicle_id = Yii::app()->request->getParam('vehicle_id');

			$result= 'false';
			$response= 'pass required parameters';
			
						
						 $veh_detail = Vehicle::model()->findByPk($vehicle_id);

if(count($veh_detail) > 0){
    $result= 'true';
			$response= 'update successful';
			

        if($veh_detail->vehicle_build == 'classic'){
              $cls_veh = Yii::app()->db->createCommand()
						->select('*')
						->from('all_classic_vehicles')
						->where("id='".$veh_detail->vehicle_source_id."'", array())
						->queryAll();
			if(count($cls_veh) > 0){
			    //echo $cls_veh[0]['make']." ".$cls_veh[0]['model']." ".$cls_veh[0]['type']." ".$cls_veh[0]['category']."<br>";
			    
			    $data = array('brand_name'=>$cls_veh[0]['make'], 'model_name'=>$cls_veh[0]['model'], 'vehicle_type'=>$cls_veh[0]['type'], 'vehicle_category'=>$cls_veh[0]['category']);

					$resUpdate = Yii::app()->db->createCommand()->update('customer_vehicals',$data,"id='".$veh_detail->id."'");
			}
        }
        
        else{
              $reg_veh = Yii::app()->db->createCommand()
						->select('*')
						->from('all_vehicles')
						->where("id='".$veh_detail->vehicle_source_id."'", array())
						->queryAll();
			if(count($reg_veh) > 0){
			    //echo $reg_veh[0]['make']." ".$reg_veh[0]['model']." ".$reg_veh[0]['type']." ".$reg_veh[0]['category']."<br>";
			    
			    $data = array('brand_name'=>$reg_veh[0]['make'], 'model_name'=>$reg_veh[0]['model'], 'vehicle_type'=>$reg_veh[0]['type'], 'vehicle_category'=>$reg_veh[0]['category']);

					$resUpdate = Yii::app()->db->createCommand()->update('customer_vehicals',$data,"id='".$veh_detail->id."'");
			}
        }
 
}

						
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}
	
	
	
			public function actionupdatecustomervehsourceid()
	{

if(Yii::app()->request->getParam('key') != API_KEY){
echo "Invalid api key";
die();
}

			$result= 'false';
			$response= 'pass required parameters';
			
						
					 $all_cust_vehicles = Yii::app()->db->createCommand()
						->select('*')
						->from('customer_vehicals')
						->queryAll();

if(count($all_cust_vehicles) > 0){
    $result= 'true';
			$response= 'update successful';
			
foreach($all_cust_vehicles as $veh_detail){
  
        if($veh_detail['vehicle_build'] == 'classic'){
              $cls_veh = Yii::app()->db->createCommand()
						->select('*')
						->from('all_classic_vehicles')
						->where("make='".$veh_detail['brand_name']."' AND model='".$veh_detail['model_name']."'", array())
						->queryAll();
			if(count($cls_veh) > 0){
			    //echo $cls_veh[0]['make']." ".$cls_veh[0]['model']." ".$cls_veh[0]['type']." ".$cls_veh[0]['category']."<br>";
			    
			    $data = array('vehicle_source_id'=>$cls_veh[0]['id']);

					$resUpdate = Yii::app()->db->createCommand()->update('customer_vehicals',$data,"id='".$veh_detail['id']."'");
			}
        }
        
        else{
              $reg_veh = Yii::app()->db->createCommand()
						->select('*')
						->from('all_vehicles')
						->where("make='".$veh_detail['brand_name']."' AND model='".$veh_detail['model_name']."'", array())
						->queryAll();
			if(count($reg_veh) > 0){
			    //echo $reg_veh[0]['make']." ".$reg_veh[0]['model']." ".$reg_veh[0]['type']." ".$reg_veh[0]['category']."<br>";
			    
			    $data = array('vehicle_source_id'=>$reg_veh[0]['id']);

					$resUpdate = Yii::app()->db->createCommand()->update('customer_vehicals',$data,"id='".$veh_detail['id']."'");
			}
        }
}
 
}

						
		$json= array(
			'result'=> $result,
			'response'=> $response,
		);
		echo json_encode($json);
	}




}