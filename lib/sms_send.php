<?php

namespace MSergeev\Packages\Smssend\Lib;

class SmsSend
{
	private static $api_id = "5C8CB74C-0929-28E3-2FE9-C9B35F2D29BC";
	private static $default_phone = "+79654320190";
	private static $service = "http://sms.ru/";
	private static $sms_length = 160;

	//Ответы
	private static $arCodes = array(
		-1 => -1,   //Сообщение не найдено.
		100 => 100, //Сообщение принято к отправке. | Запрос выполнен.
		101 => 101, //Сообщение передается оператору
		102 => 102, //Сообщение отправлено (в пути)
		103 => 103, //Сообщение доставлено
		104 => 104, //Не может быть доставлено: время жизни истекло
		105 => 105, //Не может быть доставлено: удалено оператором
		106 => 106, //Не может быть доставлено: сбой в телефоне
		107 => 107, //Не может быть доставлено: неизвестная причина
		108 => 108, //Не может быть доставлено: отклонено
		130 => 130, //Не может быть доставлено: превышено количество сообщений на этот номер в день
		131 => 131, //Не может быть доставлено: превышено количество одинаковых сообщений на этот номер в минуту
		132 => 132, //Не может быть доставлено: превышено количество одинаковых сообщений на этот номер в день
		200 => 200, //Неправильный api_id
		201 => 201, //Не хватает средств на лицевом счету
		202 => 202, //Неправильно указан получатель
		203 => 203, //Нет текста сообщения
		204 => 204, //Имя отправителя не согласовано с администрацией
		205 => 205, //Сообщение слишком длинное (превышает 8 СМС)
		206 => 206, //Будет превышен или уже превышен дневной лимит на отправку сообщений
		207 => 207, //На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей
		208 => 208, //Параметр time указан неправильно
		209 => 209, //Вы добавили этот номер (или один из номеров) в стоп-лист
		210 => 210, //Используется GET, где необходимо использовать POST
		211 => 211, //Метод не найден
		212 => 212, //Текст сообщения необходимо передать в кодировке UTF-8 (вы передали в другой кодировке)
		220 => 220, //Сервис временно недоступен, попробуйте чуть позже.
		230 => 230, //Превышен общий лимит количества сообщений на этот номер в день.
		231 => 231, //Превышен лимит одинаковых сообщений на этот номер в минуту.
		232 => 232, //Превышен лимит одинаковых сообщений на этот номер в день.
		300 => 300, //Неправильный token (возможно истек срок действия, либо ваш IP изменился)
		301 => 301, //Неправильный пароль, либо пользователь не найден
		302 => 302  //Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)
	);
	private static $arCodesText = array(
		-1 => "Сообщение не найдено",
		100 => "Сообщение принято к отправке. | Запрос выполнен.",
		101 => "Сообщение передается оператору",
		102 => "Сообщение отправлено (в пути)",
		103 => "Сообщение доставлено",
		104 => "Не может быть доставлено: время жизни истекло",
		105 => "Не может быть доставлено: удалено оператором",
		106 => "Не может быть доставлено: сбой в телефоне",
		107 => "Не может быть доставлено: неизвестная причина",
		108 => "Не может быть доставлено: отклонено",
		130 => "Не может быть доставлено: превышено количество сообщений на этот номер в день",
		131 => "Не может быть доставлено: превышено количество одинаковых сообщений на этот номер в минуту",
		132 => "Не может быть доставлено: превышено количество одинаковых сообщений на этот номер в день",
		200 => "Неправильный api_id",
		201 => "Не хватает средств на лицевом счету",
		202 => "Неправильно указан получатель",
		203 => "Нет текста сообщения",
		204 => "Имя отправителя не согласовано с администрацией",
		205 => "Сообщение слишком длинное (превышает 8 СМС)",
		206 => "Будет превышен или уже превышен дневной лимит на отправку сообщений",
		207 => "На этот номер (или один из номеров) нельзя отправлять сообщения, либо указано более 100 номеров в списке получателей",
		208 => "Параметр time указан неправильно",
		209 => "Вы добавили этот номер (или один из номеров) в стоп-лист",
		210 => "Используется GET, где необходимо использовать POST",
		211 => "Метод не найден",
		212 => "Текст сообщения необходимо передать в кодировке UTF-8 (вы передали в другой кодировке)",
		220 => "Сервис временно недоступен, попробуйте чуть позже.",
		230 => "Превышен общий лимит количества сообщений на этот номер в день.",
		231 => "Превышен лимит одинаковых сообщений на этот номер в минуту.",
		232 => "Превышен лимит одинаковых сообщений на этот номер в день.",
		300 => "Неправильный token (возможно истек срок действия, либо ваш IP изменился)",
		301 => "Неправильный пароль, либо пользователь не найден",
		302 => "Пользователь авторизован, но аккаунт не подтвержден (пользователь не ввел код, присланный в регистрационной смс)"
	);

	/**
	 * Функция возвращает текст кода ответа по его ID
	 *
	 * @param null $code ID кода ответа
	 *
	 * @return bool|string
	 */
	public static function getCodeText ($code=null)
	{
		if (!is_null($code) && isset(static::$arCodesText[$code]))
		{
			return static::$arCodesText[$code];
		}
		else
		{
			return false;
		}
	}

	/**
	 * Совершает отправку СМС сообщения одному или нескольким получателям.
	 *
	 * @param null|string   $to         Получатель сообщения
	 * @param null|string   $text       Текст сообщения
	 * @param null|array    $multi      Массив [номер]=текст
	 * @param null|string   $from       Отправитель
	 * @param null|int      $time       Когда должно быть отправлено (не дальше чем за неделю)
	 * @param null|int|bool $translit   Флаг необходимости транслитировать сообщение
	 * @param null|int|bool $test       Флаг тестирования
	 * @param null|int      $partner_id Партнерский ID
	 *
	 * @return array
	 */
	public static function smsSend ($to=null, $text=null, $multi=null, $from=null, $time=null, $translit=null, $test=null, $partner_id=null)
	{
		$url = static::$service."sms/send?api_id=".static::$api_id;

		if (is_null($to))
		{
			$to = static::$default_phone;
		}

		$to = str_replace("+","",$to);
		if (strlen($to)<11)
		{
			return static::$arCodes[202];
		}

		if (is_null($text))
		{
			return static::$arCodes[203];
		}

		if (strlen($text)>static::$sms_length)
		{
			$text = substr($text,0,static::$sms_length);
		}
		$text = urlencode($text);

		if (is_null($multi))
		{
			$url .= "&" . $to . "&" . $text;
		}
		else
		{
			foreach ($multi as $m_number=>$m_text)
			{
				$m_number = str_replace("+","",$m_number);
				if (strlen($m_number)<11)
				{
					return static::$arCodes[202];
				}
				if (strlen($m_text)==0)
				{
					return static::$arCodes[203];
				}
				if (strlen($m_text)>static::$sms_length)
				{
					$m_text = substr($m_text,0,static::$sms_length);
				}
				$m_text = urlencode($m_text);
				$url .= "&multi[".$m_number."]=".$m_text;
			}
		}
		if (!is_null($from))
		{
			$url .= "&from=".$from;
		}
		if (!is_null($time))
		{
			$url .= "&time=".$time;
		}
		if (!is_null($translit))
		{
			if ($translit)
			{
				$url .= "&translit=1";
			}
		}
		if (!is_null($test))
		{
			if ($test)
			{
				$url .= "&test=1";
			}
		}
		if (!is_null($partner_id))
		{
			$url .= "&partner_id=".$partner_id;
		}
		$body = file_get_contents($url);
		list($code,$sms_id) = explode("\n", $body);
		if ($code == "100"){
			return array(
				"CODE" => intval($code),
				"DATA" => array(
					"SMS_ID" => $sms_id
				)
			);
		}
		else
		{
			return array("CODE"=>intval($code));
		}
		//TODO: Проверить работу
	}

	/**
	 * Отправка СМС сообщений по электронной почте (более надежно, но нет возможность отслеживать в реальном времени ошибки типа нехватки средств).
	 *
	 * @param null|string $to       Получатель сообщения
	 * @param null|string $text     Текст сообщения
	 * @param null|string $from     Отправитель сообщения
	 * @param null|string $add_subj Дополнительные заголовки
	 *
	 * @return array
	 */
	public static function smsMail ($to=null, $text=null, $from=null, $add_subj=null)
	{
		if (is_null($to))
		{
			$to = static::$default_phone;
		}
		$to = str_replace("+","",$to);
		if (strlen($to)<11)
		{
			return static::$arCodes[202];
		}
		if (is_null($text))
		{
			return static::$arCodes[203];
		}
		if (strlen($text)>static::$sms_length)
		{
			$text = substr($text,0,static::$sms_length);
		}
		if (is_null($from))
		{
			$from = "";
		}
		else
		{
			$from = "from:".$from;
		}
		if (!is_null($add_subj))
		{
			$from .= $add_subj;
		}
		mail(static::$api_id,$from,$text);
		return array("CODE"=>static::$arCodes[100]);
	}

	/**
	 * Проверка статуса отправленного сообщения.
	 *
	 * @param null|string $id Идентификатор сообщения, полученный при использовании метода sms/send
	 *
	 * @return array
	 */
	public static function smsStatus ($id=null)
	{
		$url = static::$service . "sms/status?api_id=" . static::$api_id;

		if (is_null($id))
		{
			return static::$arCodes[-1];
		}
		else
		{
			$url .= "&id=".$id;
		}

		$body=file_get_contents($url);
		list($code,$other) = explode("\n", $body);
		return array("CODE"=>intval($code));
		//TODO: Проверить работу и возвращать код результата
	}

	/**
	 * Возвращает стоимость сообщения на указанный номер и количество сообщений, необходимых для его отправки.
	 *
	 * @param null|string   $to         Номер телефона получателя
	 * @param null|string   $text       Текст сообщения в кодировке UTF-8.
	 * @param null|int|bool $translit   Переводит все русские символы в латинские.
	 *
	 * @return array
	 */
	public static function smsCost ($to=null, $text=null, $translit=null)
	{
		$url = static::$service . "sms/cost?api_id=" . static::$api_id;

		if (is_null($to))
		{
			$to = static::$default_phone;
		}
		$to = str_replace("+","",$to);
		if (strlen($to)<11)
		{
			return static::$arCodes[202];
		}
		if (is_null($text))
		{
			return static::$arCodes[203];
		}
		if (strlen($text)>static::$sms_length)
		{
			$text = substr($text,0,static::$sms_length);
		}
		$text = urlencode($text);
		$url .= "&" . $to . "&" . $text;
		if (!is_null($translit))
		{
			if ($translit)
			{
				$url .= "&translit=1";
			}
		}
		$body=file_get_contents($url);
		list($code,$cost,$num) = explode("\n", $body);
		if ($code=="100")
		{
			return array(
				"CODE" => intval($code),
				"DATA" => array(
					"COST" => $cost,
					"NUMBER" => $num
				)
			);
		}
		else
		{
			return array("CODE"=>intval($code));
		}
		//TODO: Проверить работу
	}

	/**
	 * Получение состояния баланса.
	 *
	 * @return array
	 */
	public static function myBalance ()
	{
		$url = static::$service . "my/balance?api_id=" . static::$api_id;
		$body=file_get_contents($url);

		list($code,$balance) = explode("\n", $body);
		if ($code=="100")
		{
			return array(
				"CODE" => intval($code),
				"DATA" => array("BALANCE"=>$balance)
			);
		}
		else
		{
			return array("CODE"=>intval($code));
		}
	}

	/**
	 * Получение текущего состояния вашего дневного лимита.
	 *
	 * @return array
	 */
	public static function myLimit ()
	{
		$url = static::$service . "my/limit?api_id=" . static::$api_id;

		$body=file_get_contents($url);

		list($code,$limit,$today) = explode("\n", $body);
		if ($code=="100")
		{
			return array(
				"CODE" => intval($code),
				"DATA" => array(
					"LIMIT" => $limit,
					"TODAY" => $today
				)
			);
		}
		else
		{
			return array("CODE"=>intval($code));
		}
	}

	/**
	 * Получение списка отправителей.
	 *
	 * @return array
	 */
	public static function mySenders ()
	{
		$url = static::$service . "my/senders?api_id=" . static::$api_id;

		$body=file_get_contents($url);

		$reply = array_filter(explode("\n", $body));
		$code = array_shift($reply);
		if ($code=="100")
		{
			$senders = $reply;
			return array(
				"CODE" => intval($code),
				"DATA" => array(
					"SENDERS" => $senders
				)
			);
		}
		else
		{
			return array("CODE"=>intval($code));
		}
	}

}