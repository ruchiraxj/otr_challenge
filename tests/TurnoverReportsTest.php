<?php

use PHPUnit\Framework\TestCase;

class GenerateCsvTest extends TestCase
{

    /**
     * @dataProvider failValidationDataProvider
     * @expectedException \Exception
     * 
     */
    public function test_validate_inputs_failure_scenarios($a, $b, $c, $d)
    {
        require_once('./configs/configs.php');
        require_once(ROOT_PATH . '/src/classes/TurnoverReport.php');

        $this->expectException(Exception::class);

        $cl = new TurnoverReport($a, $b, $c, $d);
        $res = $this->invokeMethod($cl, 'validateInputs');
        $this->assertTrue($res);
    }


    public function test_validate_inputs_success_scenarios(){
        require_once('./configs/configs.php');
        require_once(ROOT_PATH . '/src/classes/TurnoverReport.php');

        $cl = new TurnoverReport('2018-01-01', '2018-01-07', 0.21, '/reports');
        $res = $this->invokeMethod($cl, 'validateInputs');
        $this->assertTrue($res);
    }


    public function test_list_of_dates_within_range(){
        require_once('./configs/configs.php');
        require_once(ROOT_PATH . '/src/classes/TurnoverReport.php');

        $cl = new TurnoverReport('2018-01-01', '2018-01-07', 0.21, '/reports');
        $res = $this->invokeMethod($cl, 'getDatesWithin');

        $expectedResponse = ['2018-01-01','2018-01-02','2018-01-03', '2018-01-04','2018-01-05','2018-01-06','2018-01-07'];
        $this->assertEquals($expectedResponse, $res);
    }

    /**
     * @dataProvider failValidationDataProvider
     */

    public function failValidationDataProvider(): array
    {
        return [
            ['2018-01-0', '2018-01-07', 0.21, '/reports'],
            ['2018-13-01', '2018-01-07', 0.21, '/reports'],
            ['2018-12-30', '2018-01-07', 0.21, '/reports'],
            ['2018-01-0', '2018-01-07', 0.21, '/reports'],
            ['2018-01-01', '2018-01-0', 0.21, '/reports'],
            ['2018-01-01', '2018-01-07', "a", '/reports'],
            ['2018-01-07', '2018-01-07', 0, '/reports'],
            ['', '2018-01-07', "a", '/reports'],
            ['2018-01-09', '2018-01-07', 1, '/reports'],
        ];
    }

    /**
     * Call protected/private method of a class.
     *
     * @param object &$object    Instantiated object that we will run method on.
     * @param string $methodName Method name to call
     * @param array  $parameters Array of parameters to pass into method.
     *
     * @return mixed Method return.
     */
    public function invokeMethod(&$object, $methodName, array $parameters = [])
    {

        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
