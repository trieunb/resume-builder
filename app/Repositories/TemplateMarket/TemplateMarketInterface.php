<?php
namespace App\Repositories\TemplateMarket;

use App\Repositories\Repository;

interface TemplateMarketInterface extends Repository
{
	/**
	 * Get all template in market place
	 * @return mixed 
	 */
    public function getAllTemplateMarket($sortby, $order, $page, $search);
    
	public function getDetailTemplateMarket($template_id);

	/**
	 * Check title exists
	 * @param  string $title 
	 * @return bool        
	 */
	public function checkExistsTitle($title);

	/**
	 * Admin create template for market place
	 * @param  mixed $request 
	 * @param  array $data 
	 * @param  mixed $user_id 
	 * @return bool          
	 */

	public function createOrUpdateTemplateByManage($request, $data, $user_id);

	/**
	 * Search template In Market Area
	 * @param  string $name 
	 * @return        
	 */
	public function search($name);

}