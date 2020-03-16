<?php
/**
 * Original Package
 * @license MIT
 * @author hazuki3417<hazuki3417@gmail.com>
 * @copyright 2020 hazuki3417 all rights reserved.
 * @package Selen
 */
namespace Selen\Value;

/**
 * 西暦を和暦に変換するクラス
 */
class Date
{
    /**
     * 新しい元号が増えた場合は下記の対応が必要
     * ・メンバ変数gengoに新しい元号の「名前」、「省略名」、「開始日」、「終了日」を追加
     * ・一番新しい元号の「終了日は」8桁（年月日の桁数）の9を指定すること
     * @var array 元号名、元号省略名、元号開始日、元号終了日を保持した配列。
     * @since 1.0.0
     * @version 1.0.0
     */
    private static $gengo = [
        0 => [
            'name' => '明治',
            'short_name' => 'M',
            'start_date' => 18681023,
            'end_date' => 19120729,
        ],
        1 => [
            'name' => '大正',
            'short_name' => 'T',
            'start_date' => 19120730,
            'end_date' => 19261224,
        ],
        2 => [
            'name' => '昭和',
            'short_name' => 'S',
            'start_date' => 19261225,
            'end_date' => 19890107,
        ],
        3 => [
            'name'  => '平成',
            'short_name' =>  'H',
            'start_date' => 19890108,
            'end_date' => 20190430,
        ],
        4 => [
            'name' => '令和',
            'short_name' => 'R',
            'start_date'  => 20190501,
            'end_date' => 99999999,
        ],
    ];
    /**
     * @var int 曜日変換用の配列。
     * @since 1.0.0
     * @version 1.0.0
     */
    private static $week_table = ['日', '月', '火', '水', '木', '金', '土'];
    /**
     * @var int 1分あたりの秒数を保持。 1分 = 60秒
     * @since 1.0.0
     * @version 1.0.0
     */
    const SECONDS_PER_MINUTES = 60;
    /**
     * @var int 1時間あたりの分数を保持。 1時間 = 60分
     * @since 1.0.0
     * @version 1.0.0
     */
    const MINUTES_PER_HOUR = 60;
    /**
     * @var int 1日あたりの時間を保持。 1日 = 24時間
     * @since 1.0.0
     * @version 1.0.0
     */
    const TIME_PER_HOUR = 24;

    /**
     * 静的クラスとして実装するため、インスタンスの生成は禁止
     * @since 1.0.0
     * @version 1.0.0
     */
    private function __construct()
    {
        //
    }

    /**
     * 1日の秒数を取得します。
     * @return int 1日の秒数を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getOneDaySecond()
    {
        return (self::TIME_PER_HOUR *
            self::SECONDS_PER_MINUTES *
            self::MINUTES_PER_HOUR);
    }

    /**
     * 1日の分数を取得します。
     * @return int 1日の分数を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getOneDayMinutes()
    {
        return (self::TIME_PER_HOUR *
            self::SECONDS_PER_MINUTES);
    }

    /**
     * 1日の時間を取得します。
     * @return int 1日の時間を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getOneDayHour()
    {
        return self::TIME_PER_HOUR;
    }

    /**
     * 西暦の文字列を年、月、日、日付（年+月+日）ごとに分割します。
     * @param string $date 西暦の日付を指定します。
     * @return array 引数の値が有効な西暦の場合は[year, month, day, date]形式の配列を、無効な場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     * TODO: 「2014年01月01」が通るので回収が必要
     */
    public static function dateSplitSeireki($date)
    {
        $date_tmp = [];
        if (preg_match('/^([0-9]{4})([0-9]{2})([0-9]{2})$/', $date, $match) ||
            preg_match('/^([0-9]{4})[-|\/|年]{1}([0-9]{1,2})[-|\/|月]{1}([0-9]{1,2})[日]?$/u', $date, $match)
        ) {
            $date_tmp = [
                'year' => $match[1],
                'month' => $match[2],
                'day' => $match[3],
                'date' => $match[1] . $match[2] . $match[3],
            ];
        }
        return $date_tmp;
    }

    /**
     * 和暦の文字列を元号名、年、月、日、日付（元号名+年+月+日）ごとに分割します。
     * @param string $wareki 和暦の日付を指定します。
     * @return array 引数の値が有効な和暦の場合は[gengo, year, month, day, date]形式の配列を、無効な場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function dateSplitWareki($wareki)
    {
        //元号名と略称の元号名の正規表現チェックバターンを作成
        $gengo_name_find = implode('|', array_merge(
            array_column(self::$gengo, 'name'),
            array_column(self::$gengo, 'short_name')
        ));
        //正規表現チェックパターン作成
        $format = '/^(%s)([0-9]{1,2}|元)年([0-9]{1,2})月([0-9]{1,2})日$/';
        $date_tmp = [];
        if (preg_match(sprintf($format, $gengo_name_find), $wareki, $match)) {
            $date_tmp = [
                'gengo' => $match[1],
                'year' => $match[2],
                'month' => $match[3],
                'day' => $match[4],
                'date' => $match[2] . $match[3] . $match[4],
            ];
        }
        return $date_tmp;
    }

    /**
     * 西暦形式かどうかチェックします。
     * @param string $date 西暦の日付を指定します。
     * @return bool 西暦の形式が正常な場合はtrue、異常な場合はfalseを返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function checkdateFormatSeireki($date)
    {
        return (empty(self::dateSplitSeireki($date))) ? false : true;
    }

    /**
     * 和暦形式かどうかチェックします。
     * @param string $wareki 和暦の日付を指定します。
     * @return bool 和暦の形式が正常な場合はtrue、異常な場合はfalseを返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function checkdateFormatWareki($wareki)
    {
        return (empty(self::dateSplitWareki($wareki))) ? false : true;
    }

    /**
     * 西暦形式かつ有効な日付かどうかチェックします。
     * @param string $date yyyymmdd形式の日付を指定します。
     * @return array 引数の値が有効な日付の場合は[year, month, day]、無効な場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function checkdateSeireki($date)
    {
        $date_tmp = self::dateSplitSeireki($date);
        if (!empty($date_tmp)) {
            if (checkdate($date_tmp['month'], $date_tmp['day'], $date_tmp['year'])) {
                return $date_tmp;
            }
        }
        return [];
    }

    /**
     * 和暦形式かつ有効な日付かどうかチェックします。
     * @param string $wareki 元号名yy年mm月dd日形式の日付を指定します。
     * @return array 引数の値が有効な日付の場合は[year, month, day]、無効な場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     * TODO: 回収が必要
     */
    public static function checkdateWareki($wareki)
    {
        $date_tmp = self::dateSplitWareki($wareki);
        if (!empty($date_tmp)) {
            //元号が適用された西暦年数と元号の和暦年数を加算
            if (checkdate($date_tmp['month'], $date_tmp['day'], $date_tmp['year'])) {
                return $date_tmp;
            }
        }
        return [];
    }

    /**
     * 時間形式の文字列を時間、分、秒ごとに分割します。
     * @param string $time 時間を指定します。
     * @return array 引数の値が有効な時間の場合は[hour, minutes, second]形式の配列を、無効な場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function timeSplit($time)
    {
        $time_tmp = [];
        if (preg_match('/^([0-9]{2})([0-9]{2})([0-9]{2})$/', $time, $match) ||
            preg_match('/^[午]?[前|後]?([0-9]{1,2})[時|:]{1}([0-9]{1,2})[分|:]{1}([0-9]{1,2})[秒]?$/u', $time, $match)
        ) {
            $time_tmp = [
                'hour' => $match[1],
                'minutes' => $match[2],
                'second' => $match[3],
            ];
        }
        return $time_tmp;
    }

    /**
     * 西暦を和暦に変換します。
     * @param string $seireki 西暦の日付を指定します。
     * @return array 変換に成功した場合は配列を、失敗した場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     * TODO: 返却する配列に曜日と結合した和暦を追加するか検討
     * TODO: 返却する配列の値をstringかintにするか検討
     */
    public static function convertSeirekiToWreki($seireki)
    {
        $date_tmp = self::dateSplitSeireki($seireki);
        if (!empty($date_tmp)) {
            if (checkdate($date_tmp['month'], $date_tmp['day'], $date_tmp['year'])) {
                $timestamp = strtotime($date_tmp['year'] . $date_tmp['month'] . $date_tmp['day']);
                $gengo_tmp = [];
                foreach (self::$gengo as $gengo_record) {
                    if (strtotime($gengo_record['end_date']) < $timestamp) {
                        continue;
                    }
                    $gengo_tmp['name'] = $gengo_record['name'];
                    $gengo_tmp['short_name'] = $gengo_record['short_name'];
                    $end_date = (int)mb_substr($gengo_record['start_date'], 0, 4);
                    $gengo_tmp['year'] = (string) ($date_tmp['year'] - $end_date + 1);
                    $gengo_tmp['month'] = $date_tmp['month'];
                    $gengo_tmp['day'] = $date_tmp['day'];
                    break;
                }
                return $gengo_tmp;
            }
        }
        return [];
    }

    /**
     * 和暦を西暦に変換します。
     * @param string $wareki 和暦の日付を指定します。
     * @return array 変換に成功した場合は配列を、失敗した場合は空の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     * TODO: 返却する配列に曜日と結合した西暦を追加するか検討
     * TODO: 返却する配列の値をstringかintにするか検討
     */
    public static function convertWarekiToSeireki($wareki)
    {
        $date_tmp = self::dateSplitWareki($wareki);
        if (!empty($date_tmp)) {
            foreach (self::$gengo as $gengo_record) {
                if (($gengo_record['name'] == $date_tmp['gengo']) || ($gengo_record['short_name'] == $date_tmp['gengo'])) {
                    $start_time_stamp = self::convertDateToTimestamp($gengo_record['start_date']);
                    $date_tmp['year'] = date('Y', strtotime('+' . ($date_tmp['year'] - 1) . ' year', $start_time_stamp));
                    // $date_tmp['month'] = $date_tmp['month'];
                    // $date_tmp['day'] = $date_tmp['day'];
                    unset($date_tmp['gengo']);
                    break;
                }
            }
        }
        if (checkdate($date_tmp['month'], $date_tmp['day'], $date_tmp['year'])) {
            return $date_tmp;
        }
        return [];
    }

    /**
     * 西暦の日付をtimestamp形式に変換します。
     * @param string $date 西暦の日付を指定します。
     * @return int timestamp
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function convertDateToTimestamp($date)
    {
        $date_tmp = self::dateSplitSeireki($date);
        return mktime(0, 0, 0, $date_tmp['month'], $date_tmp['day'], $date_tmp['year']);
    }

    /**
     * timestamp形式を西暦に変換します。
     * @param int $time_stamp timestampを指定します。
     * @return string 西暦の日付を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function convertTimestampToDate($time_stamp)
    {
        return date('Ymd', $time_stamp);
    }

    /**
     * timestampから日時情報を取得します。引数を省略した場合は現在の日時情報を取得します。
     * @param int $timestamp UNIXタイムスタンプを指定します。
     * @return array [year, month, day, hour, minutes, second, week]形式の配列を返します。
     * @since 1.0.0
     * @version 1.0.0
     */
    public static function getDateInfo($timestamp = null)
    {
        $timestamp_tmp = empty($timestamp) ? time() : $timestamp;
        $datetime_split = explode('-', date('Y-m-d-h-m-s-w', $timestamp_tmp));
        $datetime_tmp = [
            'year' => $datetime_split[0],
            'month' => $datetime_split[1],
            'day' => $datetime_split[2],
            'hour' => $datetime_split[3],
            'minutes' => $datetime_split[4],
            'second' => $datetime_split[5],
            'week' => self::$week_table[$datetime_split[6]],
        ];
        return $datetime_tmp;
    }
}
