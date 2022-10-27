<?php
declare(strict_types = 1);

/**
 * @charset UTF-8
 *
 * Задание 2. Работа с массивами и строками.
 *
 * Есть список временных интервалов (интервалы записаны в формате чч:мм-чч:мм).
 *
 * Необходимо написать две функции:
 *
 *
 * Первая функция должна проверять временной интервал на валидность
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 * 	возвращать boolean
 *
 *
 * Вторая функция должна проверять "наложение интервалов" при попытке добавить новый интервал в список существующих
 * 	принимать она будет один параметр: временной интервал (строка в формате чч:мм-чч:мм)
 *  возвращать boolean
 *
 *  "наложение интервалов" - это когда в промежутке между началом и окончанием одного интервала,
 *   встречается начало, окончание или то и другое одновременно, другого интервала
 *
 *  пример:
 *
 *  есть интервалы
 *  	"10:00-14:00"
 *  	"16:00-20:00"
 *
 *  пытаемся добавить еще один интервал
 *  	"09:00-11:00" => произошло наложение
 *  	"11:00-13:00" => произошло наложение
 *  	"14:00-16:00" => наложения нет
 *  	"14:00-17:00" => произошло наложение
 */

# Можно использовать список:

/*$list = array (
   "10:00-14:00",
   "16:00-20:00"
);*/

$list = array (
	'09:00-11:00',
	'11:00-13:00',
	'15:00-16:00',
	'17:00-20:00',
	'20:30-21:30',
	'21:30-22:30',
);


function valid_time(string $time) {
   $time = explode('-', $time);
   $regexp = "/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/";

   if (count($time) === 2 && preg_match($regexp, $time[0]) && preg_match($regexp, $time[1])) {
      return true;
   } else {
      return false;
   }
}

function check_overlay($current_interval) {
   function _explode_times($interval) {
      $times = explode('-', $interval);

      $times = array_map(function($arr) {
         $hour = ltrim(explode(':', $arr)[0], '0');
         $min = substr(explode(':', $arr)[1], -2, 1);

         return (int)($hour.$min);
      }, $times);

      if ($times[0] > 10 && $times[1] < 100) {
         $times[1] += 240;
      }

      return $times;
   }


   global $list;
   $current_times = _explode_times($current_interval);


   foreach ($list as $key => $exist_interval) {
      $exist_times = _explode_times($exist_interval);

      if
      (
         $current_times[0] >= $exist_times[0] && $current_times[0] < $exist_times[1] ||
         $current_times[0] <= $exist_times[0] && $current_times[1] > $exist_times[0] ||
         $current_times[0] >= $exist_times[0] && $current_times[1] < $exist_times[0]
      ) {
         return true;
      }
   }

   return false;
}

$test_time = "14:00-20:00";
if (valid_time($test_time)) {
   var_dump(check_overlay($test_time));
}
?>
