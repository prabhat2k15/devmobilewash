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

		$json = array();
		$vehicles = array();
        $makes = array();
       $makes_detail = array();

		$result= 'false';
		$response= 'none';

		$vehicles_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_vehicles')
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

		$json = array();
		$vehicles = array();
        $makes = array();
       $makes_detail = array();

		$result= 'false';
		$response= 'none';

		$vehicles_exists = Yii::app()->db->createCommand()
			->select('*')
			->from('all_classic_vehicles')
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




}