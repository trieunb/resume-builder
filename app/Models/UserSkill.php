<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\belongsTo;

class UserSkill extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['*'];

	/**
	 * Table name
	 * @var string
	 */
    protected $table = 'user_skills';

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
    	return $this->belongsTo(User::class);
    }

    public function updateColumnWithClause($data, $field, &$params = []) 
    {
        $params['sql'] = '';
        foreach ($data as $value) {
            $params['sql'] .= " WHEN id = ? THEN ? ";
            $params['param'][] = $value['id']; 
            $params['param'][] = $value[$field]; 
        }
        $params['sql'] .= ' END';

        return $params;
    }

    public function updateMultiRecord($dataPrepareUpdate, array $ids)
    {
        $sql = 'UPDATE `user_skills` SET skill_name = CASE ';
        $params = ['sql' => '', 'param' => []];
        $sql .= $this->updateColumnWithClause($dataPrepareUpdate, 'skill_name', $params)['sql'];
        $sql .= ' , skill_test = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'skill_test', $params)['sql'];
        $sql .= ' , skill_test_point = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'skill_test_point', $params)['sql'];
        $sql .= ' , experience = CASE '.$this->updateColumnWithClause($dataPrepareUpdate, 'experience', $params)['sql'];
        $sql .= ' WHERE id IN ('.implode(',', $ids).')';

        \DB::update($sql, $params['param']);
    }

    public function insertMultiRecord($dataPrepareForCreate, $user_id)
    {
        // $user = User::find($user_id);
        $user_skills = [];
        foreach ($dataPrepareForCreate as $value) {
            $user_skills[] = [
                'user_id' => $user_id,
                'skill_name' => $value['skill_name'],
                'skill_test' => $value['skill_test'],
                'skill_test_point' => $value['skill_test_point'],
                'experience' => $value['experience']
            ];
        }

        $this->insert($user_skills);
        //$user->user_skills()->save($user_skills);
        
    }
}
