<?php
/**
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen\Measurement
 */
namespace Selen\Value;

use PHPUnit\Framework\TestCase;
use Selen\Value\Date;

class DateTest extends TestCase
{
    /**
     * @var Date
     */
    protected $object;

    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        // $this->object = new Date;
    }

    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
    }

    /**
     * @covers Selen\Date::getOneDaySecond
     */
    public function testGetOneDaySecond()
    {
        //値と型が同じかチェック
        $this->assertSame(86400, Date::getOneDaySecond());
    }

    /**
     * @covers Selen\Date::getOneDayMinutes
     */
    public function testGetOneDayMinutes()
    {
        //値と型が同じかチェック
        $this->assertSame(1440, Date::getOneDayMinutes());
    }

    /**
     * @covers Selen\Date::getOneDayHour
     */
    public function testGetOneDayHour()
    {
        //値と型が同じかチェック
        $this->assertSame(24, Date::getOneDayHour());
    }

    /**
     * @covers Selen\Date::dateSplitSeireki
     * @dataProvider valueCheckDateSplitSeireki
     */
    public function testDateSplitSeireki($true_seireki, $false_seireki)
    {
        //正常系
        $true_result = Date::dateSplitSeireki($true_seireki);
        //配列のキーが存在するか
        $this->assertArrayHasKey('year', $true_result);
        $this->assertArrayHasKey('month', $true_result);
        $this->assertArrayHasKey('day', $true_result);
        $this->assertArrayHasKey('date', $true_result);
        //配列の値の型がすべて同じか
        $this->assertContainsOnly('string', $true_result);
        //配列の数が同じか
        $this->assertCount(4, $true_result);
        //値の文字の長さが同じか

        //異常系
        $false_result = Date::dateSplitSeireki($false_seireki);
        //配列が空か
        $this->assertEmpty($false_result);
    }

    /**
     * testDateSplitSeireki用dataProvider
     */
    public function valueCheckDateSplitSeireki()
    {
        return [
            'format check1'  => ['2014年05月01日', ''],
            'format check2'  => ['2014年5月01日', '2014年0501日'],
            'format check3'  => ['2014年05月1日', '201405月01日'],
            'format check4'  => ['2014年5月1日', ''],
            'format check5'  => ['2014/05/01', '2014/05/01/'],
            'format check6'  => ['2014/5/01', '2014/0501'],
            'format check7'  => ['2014/05/1', '201405/01'],
            'format check8'  => ['2014/5/1', '2014/5/1/'],
            'format check9'  => ['2014-05-01', '2014-05-01-'],
            'format check10'  => ['2014-5-01', '2014-0501'],
            'format check11'  => ['2014-05-1', '201405-01'],
            'format check12'  => ['2014-5-1', '2014-5-1-'],
            'format check13'  => ['20140501', '2014051'],
        ];
    }

    /**
     * testDateSplitWareki用dataProvider
     */
    public function valueCheckDateSplitWareki()
    {
        return [
            'kanji check1'  => ['明治01年01月01日', '明自01年01月01日'],
            'kanji check2'  => ['大正01年01月01日', '対象01年01月01日'],
            'kanji check3'  => ['昭和01年01月01日', '正和01年01月01日'],
            'kanji check4'  => ['平成01年01月01日', '兵制01年01月01日'],
            'kanji check5'  => ['令和01年01月01日', '例羽01年01月01日'],
            'alphabet check1'  => ['M01年01月01日', 'm01年01月01日'],
            'alphabet check2'  => ['T01年01月01日', 't01年01月01日'],
            'alphabet check3'  => ['S01年01月01日', 's01年01月01日'],
            'alphabet check4'  => ['H01年01月01日', 'h01年01月01日'],
            'alphabet check5'  => ['R01年01月01日', 'r01年01月01日'],
            'gannnenn check1'  => ['令和元年01月01日', '令和癌年01月01日'],
            'gannnenn check2'  => ['R元年01月01日', 'R癌年01月01日'],
            'format check1'  => ['令和01年01月01日', '01年01月01日'],
            'format check2'  => ['令和1年01月01日', '令和0101月01日'],
            'format check3'  => ['令和01年1月01日', '令和01年0101日'],
            'format check4'  => ['令和01年01月1日', '令和01年01月01'],
            'format check5'  => ['令和1年1月1日', '令和010101'],
        ];
    }

    /**
     * @covers Selen\Date::dateSplitWareki
     * @dataProvider valueCheckDateSplitWareki
     */
    public function testDateSplitWareki($true_wareki, $false_wareki)
    {
        //正常系
        $true_result = Date::dateSplitWareki($true_wareki);
        //配列のキーが存在するか
        $this->assertArrayHasKey('gengo', $true_result);
        $this->assertArrayHasKey('year', $true_result);
        $this->assertArrayHasKey('month', $true_result);
        $this->assertArrayHasKey('day', $true_result);
        $this->assertArrayHasKey('date', $true_result);
        //配列の値の型がすべて同じか
        $this->assertContainsOnly('string', $true_result);
        //配列の数が同じか
        $this->assertCount(5, $true_result);
        //値の文字の長さが同じか

        //異常系
        $false_result = Date::dateSplitWareki($false_wareki);
        //配列が空か
        $this->assertEmpty($false_result);
    }

    /**
     * @covers Selen\Date::checkdateFormatSeireki
     * @dataProvider valueCheckDateSplitSeireki
     */
    public function testCheckdateFormatSeireki($true_seireki, $false_seireki)
    {
        $this->assertTrue(Date::checkdateFormatSeireki($true_seireki));
        $this->assertFalse(Date::checkdateFormatSeireki($false_seireki));
    }

    /**
     * @covers Selen\Date::checkdateFormatWareki
     * @dataProvider valueCheckDateSplitWareki
     */
    public function testCheckdateFormatWareki($true_wareki, $false_wareki)
    {
        $this->assertTrue(Date::checkdateFormatWareki($true_wareki));
        $this->assertFalse(Date::checkdateFormatWareki($false_wareki));
    }

    /**
     * @covers Selen\Date::checkdateSeireki
     * @dataProvider valueCheckDateSplitSeireki
     */
    public function testCheckdateSeireki($true_seireki, $false_seireki)
    {
        //正常系
        $true_result = Date::checkdateSeireki($true_seireki);
        //配列のキーが存在するか
        $this->assertArrayHasKey('year', $true_result);
        $this->assertArrayHasKey('month', $true_result);
        $this->assertArrayHasKey('day', $true_result);
        $this->assertArrayHasKey('date', $true_result);
        //配列の値の型がすべて同じか
        $this->assertContainsOnly('string', $true_result);
        //配列の数が同じか
        $this->assertCount(4, $true_result);
        //値の文字の長さが同じか

        //異常系
        $false_result = Date::checkdateSeireki($false_seireki);
        //配列が空か
        $this->assertEmpty($false_result);
    }

    /**
     * @covers Selen\Date::checkdateWareki
     * @dataProvider valueCheckDateSplitWareki
     * TODO:   Implement testCheckdateWareki().
     */
    // public function testCheckdateWareki($true_wareki, $false_wareki)
    // {
    //     //正常系
    //     $true_result = Date::checkdateWareki($true_wareki);
    //     //配列のキーが存在するか
    //     $this->assertArrayHasKey('gengo', $true_result);
    //     $this->assertArrayHasKey('year', $true_result);
    //     $this->assertArrayHasKey('month', $true_result);
    //     $this->assertArrayHasKey('day', $true_result);
    //     $this->assertArrayHasKey('date', $true_result);
    //     //配列の値の型がすべて同じか
    //     $this->assertContainsOnly('string', $true_result);
    //     //配列の数が同じか
    //     $this->assertCount(5, $true_result);
    //     //値の文字の長さが同じか

    //     //異常系
    //     $false_result = Date::checkdateWareki($false_wareki);
    //     //配列が空か
    //     $this->assertEmpty($false_result);
    // }

    /**
     * @covers Selen\Date::timeSplit
     * TODO:   Implement testCheckdateWareki().
     */
    public function testTimeSplit()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Selen\Date::convertSeirekiToWreki
     * TODO:   Implement testConvertSeirekiToWreki().
     */
    public function testConvertSeirekiToWreki()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Selen\Date::convertWarekiToSeireki
     * TODO:   Implement testConvertWarekiToSeireki().
     */
    public function testConvertWarekiToSeireki()
    {
        // Remove the following lines when you implement this test.
        $this->markTestIncomplete(
            'This test has not been implemented yet.'
        );
    }

    /**
     * @covers Selen\Date::convertDateToTimestamp
     */
    public function testConvertDateToTimestamp()
    {
        //すべて文字列か
        $this->assertInternalType('int', Date::convertDateToTimestamp('20140101'));
    }

    /**
     * @covers Selen\Date::convertTimestampToDate
     */
    public function testConvertTimestampToDate()
    {
        $result = Date::convertTimestampToDate(time());
        //すべて文字列か
        $this->assertInternalType('string', $result);
        //すべて数字で8桁か
        $this->assertRegExp('/^[0-9]{8}$/', $result);
    }

    /**
     * @covers Selen\Date::getDateInfo
     */
    public function testGetDateInfo()
    {
        //正常系
        $true_result = Date::getDateInfo();
        //配列のキーが存在するか
        $this->assertArrayHasKey('year', $true_result);
        $this->assertArrayHasKey('month', $true_result);
        $this->assertArrayHasKey('day', $true_result);
        $this->assertArrayHasKey('hour', $true_result);
        $this->assertArrayHasKey('minutes', $true_result);
        $this->assertArrayHasKey('second', $true_result);
        $this->assertArrayHasKey('week', $true_result);
        //配列の値の型がすべて同じか
        $this->assertContainsOnly('string', $true_result);
        //配列の数が同じか
        $this->assertCount(7, $true_result);
        //値の文字の長さが同じか
    }
}
