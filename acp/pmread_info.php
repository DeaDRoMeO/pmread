<?php

/**
*
* @package PMRead
* @copyright (c) 2020 DeaDRoMeO ; hello-vitebsk.ru
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace deadromeo\pmread\acp;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class pmread_info
{
	function module()
	{
		return array(
			'filename'	=> '\deadromeo\pmread\pmread_module',
			'title'		=> 'PMR',
			'modes'		=> array(
				'pmread_config' => array('title' => 'PMR_CONFIG', 'auth' => 'ext_deadromeo/pmread && acl_a_board', 'cat' => array('PMR')),
			),
		);
	}
}

?>