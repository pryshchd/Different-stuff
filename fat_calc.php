<?php
class dataHandle
    {
    	public $bicep;
    	public $tricep;
    	public $blade;
    	public $belly;
    	public $sex;

    	function getMeasurements()
    		{
            if (isset($_POST['bicep'])) $bicep = $_POST['bicep'];
            if (isset($_POST['tricep'])) $tricep = $_POST['tricep'];
            if (isset($_POST['blade'])) $blade = $_POST['blade'];
            if (isset($_POST['belly'])) $belly = $_POST['belly'];
            if (isset($_POST['sex'])) $sex = $_POST['sex'];
            $measurements = array($bicep, $tricep, $blade, $belly);
            return $measurements;
    		}
    	function getSex()
    		{
            if (isset($_POST['sex'])) $sex = $_POST['sex'];
            return $sex;
    		}
    	function getAge()
    		{
            if (isset($_POST['age'])) $age = $_POST['age'];
            return $age;
    		}		

    }
class fatCalculator
    {
    function sum() 
       {
       $measurements = dataHandle::getMeasurements();
       $result = 0;
       foreach ($measurements as $key => $value) 
           {
           $result += $value;
           }
           return $result;
        }
    function findNearestTuck()
        {
        	$fatSum = $this->sum();
        if ($fatSum < 30)
            {
            $bottomNearest = $fatSum - ($fatSum % 2);
            $topNearest = $bottomNearest + 2;
            };
        if (($fatSum >= 30) && ($fatSum <= 100))
            {
            $bottomNearest = $fatSum - ($fatSum % 5);
            $topNearest = $bottomNearest + 5;
            }
        if ($fatSum > 100)
            {
            $bottomNearest = $fatSum - ($fatSum % 10);
            $topNearest = $bottomNearest + 10;
            }
        return array('bottomNearest'=>$bottomNearest,'topNearest'=>$topNearest);
        }
    }
class fatCalculatorMale extends fatCalculator
{
	var $resultsTable = array(
20=>8.1,
22=>9.2,
24=>10.2,
26=>11.2,
28=>12.1,
30=>12.9,
35=>14.7,
40=>16.3,
45=>17.7,
50=>19,
55=>20.2,
60=>21.2,
65=>22.2,
70=>23.2,
75=>24,
80=>24.8,
85=>25.6,
90=>26.3,
95=>27,
100=>27.6,
110=>28.8,
120=>29.9,
130=>31,
140=>31.9,
150=>32.8,
160=>33.6,
170=>34.4,
180=>35.2,
190=>35.9,
200=>36.5);
	

    function calculate()
    {
    $fatSum = $this->sum();
    echo "fatSum = $fatSum". '<br/>';
    $topBottomNearestTuck = $this->findNearestTuck();
    echo "topBottomNearestTuck = $topBottomNearestTuck". '<br/>';
    $bottomTuck = $topBottomNearestTuck['bottomNearest'];
    echo "bottomTuck = $bottomTuck". '<br/>';
    $topTuck = $topBottomNearestTuck['topNearest'];
    echo "topTuck = $topTuck". '<br/>';
    $bottomPercent = array_search($bottomTuck, $resultsTable);
    echo $this->$resultstable[0];
    echo "bottomPercent = $bottomPercent". '<br/>';
    $topPercent =  array_search($topTuck, $this->resultsTable);
    $fatPercent = (($fatSum - $bottomTuck) / ($topTuck - $bottomTuck)*($topPercent - $bottomPercent)); 
    $resultoutput = $fatPercent . ' у мужика';
    return $resultoutput;
    }

       	
    
}    
class fatCalculatorFemale extends fatCalculator
{
    function calculate()
    {
    $resultoutput = (parent::calculate()) . ' у тетки';
    return $resultoutput;
    }
} 


class calcHandler
    {
	function generateAppropriateCalc()
	    {
    if (dataHandle::getSex() == 'male') 
        {
        $Person = new fatCalculatorMale;
        return $Person;
        } 
    else 
        {
        $Person = new fatCalculatorFemale;
        return $Person;
        }
    }
}

// для дебага
print_r(dataHandle::getMeasurements());
echo dataHandle::getAge() . '<br/>';
$Person = calcHandler::generateAppropriateCalc();
echo $Person -> calculate();
var_dump($Person->resultsTable);
echo <<<_END
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Калькулятор % жира в огранизме</title>
</head>
<body>
    <h1>Калькулятор процента жира в организме</h1>
    <form method="post" action="fat_calc.php">
        <p>выберите свой пол 
        	<input type="radio" name="sex" value="male" checked> мужской 
			<input type="radio" name="sex" value="female"> женский
		</p>
		<p>укажите свой возраст <input type="text" name="age"/></p>
        <p>толщина складки на бицепсе <input type="text" name="bicep"/></p>
        <p>толщина складки на трицепсе <input type="text" name="tricep"/></p>
        <p>толщина складки под лопаткой <input type="text" name="blade"/></p>
        <p>толщина складки на животе <input type="text" name="belly"/></p>
        <p><input type="submit" value ="подсчитать"/></p>     
	</form>
</body>
</html>
_END;
