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

/**
* @package acp
*/
class pmread_module
{
var $u_action;
	 public function main($id, $mode)
	{
	global $request, $config, $phpbb_container, $db, $template, $user, $phpbb_root_path;
		$this->request = $request;
		$this->config = $config;
		$this->db = $db;
		$this->template = $template;
		$this->user = $user;
		$this->phpbb_root_path = $phpbb_root_path;	
		$this->user->add_lang('acp/common');
		$this->tpl_name = 'acp_pmread';
		$this->page_title = $this->user->lang('PMR');
		$start = $this->request->variable('start', 0);
		$total_count	= 0;
		$per_page		= 15;
		$sql = 'SELECT COUNT(msg_id) as total
			FROM ' . PRIVMSGS_TABLE . '';
		$result = $this->db->sql_query($sql);
		$row = $this->db->sql_fetchrow($result);
		$total_count = $row['total'];
		$this->db->sql_freeresult($result);
		$pagination_url = $this->u_action;
		$pagination = $phpbb_container->get('pagination');
		if ($total_count)
		{
			$pagination->generate_template_pagination($pagination_url, 'pagination', 'start', $total_count, $per_page, $start);
		}

		$sql = 'SELECT msg_id, message_subject, message_text, message_time, author_id, to_address, bbcode_uid, bbcode_bitfield, enable_bbcode, enable_magic_url, enable_smilies    
			FROM ' . PRIVMSGS_TABLE . '';			
		$result = $this->db->sql_query_limit($sql, $per_page, $start);
		while ($row = $this->db->sql_fetchrow($result))
		{
		$bbcode_options = (($row['enable_bbcode']) ? OPTION_FLAG_BBCODE : 0) + (($row['enable_smilies']) ? OPTION_FLAG_SMILIES : 0) + (($row['enable_magic_url']) ? OPTION_FLAG_LINKS : 0);
		$message_text = generate_text_for_display($row['message_text'], $row['bbcode_uid'], $row['bbcode_bitfield'], $bbcode_options);
		
			$to_array = explode(',', $row['to_address']);
			$elements = count($to_array);
			for ($i = 0; $i < $elements; $i++)
			{
				if (empty($to_array[$i]))
				{
					unset($to_array[$i]);
				}
				else
				{
					$id = trim($to_array[$i], 'u_');
					$sql = 'SELECT username
						FROM ' . USERS_TABLE . '
						WHERE user_id = "' . $id . '"';
					$id_result = $db->sql_query($sql, 600);  
					$name = $db->sql_fetchfield('username');
					$db->sql_freeresult($id_result);
				}
			}
			$from_array = explode(',', $row['author_id']);
			$elements2 = count($from_array);
			for ($i = 0; $i < $elements2; $i++)
			{
				if (empty($from_array[$i]))
				{
					unset($from_array[$i]);
				}
				else
				{
					$id2 = $from_array[$i];
					$sql = 'SELECT username
						FROM ' . USERS_TABLE . '
						WHERE user_id = "' . $id2 . '"';
					$id2_result = $db->sql_query($sql, 600);  
					$name2 = $db->sql_fetchfield('username');
					$db->sql_freeresult($id2_result);
				}
			}
			
			$this->template->assign_block_vars('message', array(
			'MSG_ID' => $row['msg_id'],
			'SBJ' => $row['message_subject'],
			'MSGT' => $message_text,
			'DATE' => $user->format_date($row['message_time']),
			'FROM' => $name2,
			'TO' => $name,
		));
		}
			
		$action = request_var('action', '');
		$mark = (isset($_REQUEST['mark'])) ? request_var('mark', array(0)) : array();
		$submit = isset($_POST['submit']);
		$form_key = 'pmsgs';
		add_form_key($form_key);
		if ($submit && sizeof($mark))
		{
			if ($action !== 'delete' && !check_form_key($form_key))
			{
				trigger_error($this->user->lang['FORM_INVALID'] . adm_back_link($this->u_action), E_USER_WARNING);
			}

			switch ($action)
			{
				case 'delete':
					if (confirm_box(true))
					{
						foreach ($mark as $msg_id)
						{
							$sql_array = array(
								'SELECT'	=> 't.user_id, t.folder_id, u.username',
								'FROM'		=> array(PRIVMSGS_TO_TABLE => 't'),
								'LEFT_JOIN'	=> array (
									array(
										'FROM'	=> array(USERS_TABLE => 'u'),
										'ON'	=> 't.user_id = u.user_id'
									)
								),
								'WHERE'		=> 't.msg_id = ' . $msg_id
							);
							$sql = $this->db->sql_build_query('SELECT', $sql_array);
							$result = $this->db->sql_query($sql);
							while ($row = $this->db->sql_fetchrow($result))
							{
								delete_pm($row['user_id'], $msg_id, $row['folder_id']);
							}
							$this->db->sql_freeresult($result);
						}
					} else {
						$s_hidden_fields = array(
							'mode'				=> $mode,
							'action'			=> $action,
							'mark'				=> $mark,
							'submit'			=> 1,
													);
						confirm_box(false, $this->user->lang['PM_DELETE'], build_hidden_fields($s_hidden_fields));
					}
				break;
			}
		}
		$option_ary = array('delete' => 'D_DELETE');
		$template->assign_vars(array(
			'PMREAD_VERSION'			=> isset($this->config['pmread_version']) ? $this->config['pmread_version'] : '',
			'TOTAL_ITEMS'		=> $this->user->lang('TOTAL_ITEMS', (int) $total_count),
			'PAGE_NUMBER'		=> $pagination->on_page($total_count, $per_page, $start),
			'PM_OP'	=> build_select($option_ary),
		));
	}
}
?>