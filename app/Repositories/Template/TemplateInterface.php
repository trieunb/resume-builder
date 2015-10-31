<?php
namespace App\Repositories\Template;

use App\Repositories\Repository;

interface TemplateInterface extends Repository
{
	/**
	 * Create or Update data
	 * @param  mixed $data 
	 * @param  int $id   
	 * @param int $user_id
	 * @return mixed      
	 */
    public function saveFromApi($data, $user_id);

    /**
     * Get template for user
     * @param  int $id      
     * @param  int $user_id 
     * @return mixed          
     */
    public function getDetailTemplate($id, $user_id);
}