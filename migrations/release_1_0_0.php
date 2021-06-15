<?php

/**
*
* @package PMRead
* @copyright (c) 2020 DeaDRoMeO ; hello-vitebsk.ru
* @license http://opensource.org/licenses/gpl-2.0.php GNU General Public License v2
*
*/

namespace deadromeo\pmread\migrations;

/**
* @ignore
*/
if (!defined('IN_PHPBB'))
{
    exit;
}

class release_1_0_0 extends \phpbb\db\migration\migration
{

	public function update_data()
	{
		return array(

			// Add new config vars
			array('config.add', array('pmread_version', '1.0.0')),		
			// Add new modules
			array('module.add', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'PMR'
			)),

			array('module.add', array(
				'acp',
				'PMR',
				array(
					'module_basename'	=> '\deadromeo\pmread\acp\pmread_module',
					'modes'	=> array('pmread_config'),
				),
			)),
		);
	}

	public function revert_data()
	{
		return array(
			array('config.remove', array('pmread_version')),
			array('module.remove', array(
				'acp',
				'PMR',
				array(
					'module_basename'	=> '\deadromeo\pmread\acp\pmread_module',
					'modes'	=> array('pmread_config'),
				),
			)),
			array('module.remove', array(
				'acp',
				'ACP_CAT_DOT_MODS',
				'PMR'
			)),
		);
	}
}