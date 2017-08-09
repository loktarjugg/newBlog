<?php
/**
 * Created by PhpStorm.
 * User: jacob
 * Date: 2017/7/6
 * Time: 11:39
 */

namespace App\Traits;


use App\Models\User;

trait CanVoteTrait
{
    /**
     * 检查指定用户是否点赞过
     * @param $user
     *
     * @return bool
     */
    public function isVotedBy($user)
    {
        return $this->voters->contains($user);
    }

    /**
     * 点赞总数
     * @return int
     */
    public function countVoters()
    {
        $voters = $this->voters();

        return $voters->count();
    }


    /**
     * Set the relationships
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function voters()
    {
        return $this->morphToMany( User::class ,
            'votable',
            'votes' ,
            'votable_id' , 'user_id');
    }
}