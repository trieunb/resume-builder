<?php
namespace App\Repositories\UserSkill;

use App\Repositories\Repository;

interface UserSkillInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
	public function saveFromApi($request, $user_id);
}