<?php

/**
*
* @package PMRead
* @copyright (c) 2020 DeaDRoMeO ; hello-vitebsk.ru
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

if (!defined('IN_PHPBB'))
{
	exit;
}
if (empty($lang) || !is_array($lang))
{
	$lang = array();
}

$lang = array_merge($lang, array(
	'PMR'					=> '«PMRead»',
	'PMR_CONFIG'						=> 'Просмотр сообщений',
	'MSG_ID'					=> 'ID сообщения',
	'FROM'					=> 'Отправитель',
	'TO'					=> 'Получатель',
	'DATETIME'					=> 'Дата отправки',
	'SUBJECT'					=> 'Заголовок сообщения',
	'NO_MESSAGES'					=> 'Нет сообщений',
	'EXPLAIN'					=> 'Здесь вы можете просматривать все личные сообщения пользователей вашего форума',
	'TOTAL_ITEMS'		=> 'Всего: <strong>%d</strong>',
	'ACP_MARK'		=> 'Отметить',
	'PM_DELETE'		=> 'Вы точно хотите удалить выбранные сообщения?',
	'D_DELETE'		=> 'Удалить',
	
));
