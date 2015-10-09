<?php
namespace App\Repositories\UserWorkHistory;

use App\Repositories\Repository;

interface UserWorkHistoryInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
	public function save($request, $id = null, $user_id);
}