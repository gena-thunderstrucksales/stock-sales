<?php

class Model_users extends CI_Model
{
	public function __construct()
	{
		parent::__construct();
	}

	public function getUserData($userId = null)
	{
		if ($userId) {
			$sql = "SELECT * FROM users WHERE id = ?";
			$query = $this->db->query($sql, array($userId));
			return $query->row_array();
		}

		$sql = "SELECT * FROM users WHERE id !=? AND active = ?";
		$query = $this->db->query($sql, array(1,1));

		return $query->result_array();
	}

	public function getUserGroup($userId = null)
	{
		if ($userId) {
			$sql = "SELECT * FROM user_group WHERE user_id = ?";
			$query = $this->db->query($sql, array($userId));
			$result = $query->row_array();

			$group_id = $result['group_id'];
			$g_sql = "SELECT * FROM groups WHERE id = ?";
			$g_query = $this->db->query($g_sql, array($group_id));
			$q_result = $g_query->row_array();
			return $q_result;
		}
	}

	public function create($data = '', $group_id = null)
	{
		if ($data && $group_id) {
			$create = $this->db->insert('users', $data);

			$user_id = $this->db->insert_id();

			$group_data = array(
				'user_id' => $user_id,
				'group_id' => $group_id
			);
			$group_data = $this->db->insert('user_group', $group_data);
			return ($create == true && $group_data) ? true : false;
		}
	}

	public function edit($data = array(), $id = null, $group_id = null)
	{
		$this->db->where('id', $id);
		$update = $this->db->update('users', $data);

		if ($group_id) {
			// user group
			$update_user_group = array('group_id' => $group_id);
			$this->db->where('user_id', $id);
			$user_group = $this->db->update('user_group', $update_user_group);
			return ($update == true && $user_group == true) ? true : false;
		}

		return ($update == true) ? true : false;
	}

	public function deactive($id)
	{
		if ($id) {
			$deactive = array('active' => 0, 'email'=> '');
			$this->db->where('id', $id);
			$update = $this->db->update('users', $deactive);
			return ($update == true) ? true : false;
		}
	}


	public function countTotalUsers()
	{
		$sql = "SELECT * FROM users";
		$query = $this->db->query($sql);
		return $query->num_rows();
	}

	public function exist_links($id)
	{
		$sql = "SELECT
		c.id_count AS  Payments,
		d.id_count AS  Prices,
		e.id_count AS  Discounts,
		f.id_count AS  Oreders

    from users a
	left join (
		  select 
		  user_id,
		  count(id) as id_count
		  from payments
		  WHERE active = 1
		  group by user_id
		) c ON c.user_id = a.id
	left join (
		  select 
		  user_id,
		  count(id) as id_count
		  from prices
		  WHERE active = 1
		  group by user_id
		) d ON d.user_id = a.id
	left join (
		  select 
		  user_id,
		  count(id) as id_count
		  from discounts
		  WHERE active = 1
		  group by user_id
		) e ON e.user_id = a.id
	left join (
		  select 
		  user_id,
		  count(id) as id_count
		  from orders
		  WHERE active = 1
		  group by user_id
		) f ON f.user_id = a.id
		  WHERE a.id = ?";

		$query = $this->db->query($sql, array($id));
		return $query->row_array();
	}
}
